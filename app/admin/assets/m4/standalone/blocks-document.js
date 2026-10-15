/*
 * Pure functions for the homepage block document, used by the editor UI.
 * Every operation returns a new document and never mutates its input.
 *
 * A "location" says where a list of blocks lives:
 *   {parent: null}                  the top level
 *   {parent: id, column: null}      children of a "group" style block
 *   {parent: id, column: n}         column n of a "columns" block
 */
(function (root, factory) {
  if (typeof module === 'object' && module.exports) module.exports = factory()
  else { root.M4Blocks = root.M4Blocks || {}; root.M4Blocks.doc = factory() }
})(this, function () {

  function clone (value) { return JSON.parse(JSON.stringify(value)) }

  function genId () {
    var bytes = new Uint8Array(6)
    if (typeof crypto !== 'undefined' && crypto.getRandomValues) crypto.getRandomValues(bytes)
    else for (var i = 0; i < 6; i++) bytes[i] = Math.floor(Math.random() * 256)
    return Array.prototype.map.call(bytes, function (b) { return ('0' + b.toString(16)).slice(-2) }).join('')
  }

  function newBlock (schema, type, id) {
    var def = schema[type]
    if (!def) throw new Error('Unknown block type: ' + type)
    var props = {}
    Object.keys(def.props).forEach(function (name) { props[name] = clone(def.props[name].default) })
    var children = def.container === 'columns' ? [[], []] : []
    return { id: id || genId(), type: type, props: props, children: children }
  }

  function listAt (doc, loc) {
    if (!loc || loc.parent === null || loc.parent === undefined) return doc.blocks
    var found = find(doc, loc.parent)
    if (!found) return null
    var children = found.block.children
    if (loc.column === null || loc.column === undefined) return children
    return children[loc.column] || null
  }

  // depth-first search; returns {block, loc, index} or null
  function find (doc, id) {
    function walk (list, loc) {
      for (var i = 0; i < list.length; i++) {
        var block = list[i]
        if (block.id === id) return { block: block, loc: loc, index: i }
        var kids = block.children || []
        var isColumns = kids.length && Array.isArray(kids[0])
        for (var c = 0; c < kids.length; c++) {
          var hit = isColumns
            ? walk(kids[c], { parent: block.id, column: c })
            : (c === 0 ? walk(kids, { parent: block.id, column: null }) : null)
          if (hit) return hit
        }
      }
      return null
    }
    return walk(doc.blocks, { parent: null })
  }

  function containsId (block, id) {
    if (block.id === id) return true
    var kids = block.children || []
    for (var i = 0; i < kids.length; i++) {
      var item = kids[i]
      if (Array.isArray(item)) {
        for (var j = 0; j < item.length; j++) if (containsId(item[j], id)) return true
      } else if (containsId(item, id)) return true
    }
    return false
  }

  function insert (doc, loc, index, block) {
    var next = clone(doc)
    var list = listAt(next, loc)
    if (!list) return doc
    var at = Math.max(0, Math.min(index, list.length))
    list.splice(at, 0, clone(block))
    return next
  }

  function remove (doc, id) {
    var next = clone(doc)
    var hit = find(next, id)
    if (!hit) return doc
    listAt(next, hit.loc).splice(hit.index, 1)
    return next
  }

  function move (doc, id, loc, index) {
    var hit = find(doc, id)
    if (!hit) return doc
    // a block cannot go inside itself
    if (loc.parent !== null && loc.parent !== undefined && containsId(hit.block, loc.parent)) return doc

    var next = clone(doc)
    var from = find(next, id)
    var block = listAt(next, from.loc).splice(from.index, 1)[0]
    var target = listAt(next, loc)
    if (!target) return doc

    var sameList = from.loc.parent === (loc.parent === undefined ? null : loc.parent) &&
      (from.loc.column === undefined ? null : from.loc.column) === (loc.column === undefined ? null : loc.column)
    var at = sameList && from.index < index ? index - 1 : index
    target.splice(Math.max(0, Math.min(at, target.length)), 0, block)
    return next
  }

  // step a block one place within its list; returns doc unchanged at the ends
  function shift (doc, id, delta) {
    var hit = find(doc, id)
    if (!hit) return doc
    var to = hit.index + delta
    var size = listAt(doc, hit.loc).length
    if (to < 0 || to >= size) return doc
    return move(doc, id, hit.loc, delta > 0 ? to + 1 : to)
  }

  function reId (block, gen) {
    block.id = gen()
    var kids = block.children || []
    kids.forEach(function (item) {
      if (Array.isArray(item)) item.forEach(function (b) { reId(b, gen) })
      else reId(item, gen)
    })
  }

  function duplicate (doc, id, gen) {
    var hit = find(doc, id)
    if (!hit) return doc
    var copy = clone(hit.block)
    reId(copy, gen || genId)
    return insert(doc, hit.loc, hit.index + 1, copy)
  }

  function setProp (doc, id, name, value) {
    var next = clone(doc)
    var hit = find(next, id)
    if (!hit) return doc
    hit.block.props[name] = value
    return next
  }

  // grow or shrink a columns block (2-4); removed columns merge into the last one kept
  function setColumnCount (doc, id, count) {
    var n = Math.max(2, Math.min(4, count))
    var next = clone(doc)
    var hit = find(next, id)
    if (!hit || !Array.isArray(hit.block.children[0])) return doc
    var cols = hit.block.children
    while (cols.length < n) cols.push([])
    while (cols.length > n) {
      var gone = cols.pop()
      cols[cols.length - 1] = cols[cols.length - 1].concat(gone)
    }
    return next
  }

  function count (doc) {
    var total = 0
    function walk (list) {
      list.forEach(function (b) {
        total++
        ;(b.children || []).forEach(function (item) {
          if (Array.isArray(item)) walk(item); else walk([item])
        })
      })
    }
    walk(doc.blocks)
    return total
  }

  /*
   * Undo / redo over document snapshots. push() with the same coalesce key
   * within `window` ms replaces the latest entry, so typing is one undo step.
   */
  function createHistory (initial, options) {
    options = options || {}
    var limit = options.limit || 100
    var windowMs = options.window || 800
    var now = options.now || function () { return Date.now() }
    var stack = [initial]
    var pos = 0
    var lastKey = null
    var lastTime = 0

    return {
      current: function () { return stack[pos] },
      push: function (doc, key) {
        if (JSON.stringify(doc) === JSON.stringify(stack[pos])) return
        var t = now()
        if (key && key === lastKey && t - lastTime < windowMs && pos > 0) {
          stack[pos] = doc
        } else {
          stack = stack.slice(0, pos + 1)
          stack.push(doc)
          if (stack.length > limit) stack.shift(); else pos++
        }
        lastKey = key || null
        lastTime = t
      },
      undo: function () { if (pos > 0) { pos--; lastKey = null } return stack[pos] },
      redo: function () { if (pos < stack.length - 1) { pos++; lastKey = null } return stack[pos] },
      canUndo: function () { return pos > 0 },
      canRedo: function () { return pos < stack.length - 1 }
    }
  }

  return {
    genId: genId, newBlock: newBlock, listAt: listAt, find: find, containsId: containsId,
    insert: insert, remove: remove, move: move, shift: shift, duplicate: duplicate,
    setProp: setProp, setColumnCount: setColumnCount, count: count, createHistory: createHistory
  }
})
