/*
 * Homepage block editor UI. Plain JS, no build step.
 * Needs blocks-document.js, jQuery and toastr (loaded by the admin head).
 * Drag and drop uses SortableJS, loaded on demand; every action also has a
 * button/select so the editor works if the CDN is unreachable.
 */
(function () {
  var D = window.M4Blocks.doc
  var SORTABLE_URL = 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js'
  var cleanup = []

  function el (tag, attrs, children) {
    var node = document.createElement(tag)
    Object.keys(attrs || {}).forEach(function (k) {
      if (k === 'class') node.className = attrs[k]
      else if (k === 'text') node.textContent = attrs[k]
      else if (k.slice(0, 2) === 'on') node.addEventListener(k.slice(2), attrs[k])
      else if (attrs[k] !== null && attrs[k] !== undefined) node.setAttribute(k, attrs[k])
    })
    ;(children || []).forEach(function (c) { if (c) node.appendChild(c) })
    return node
  }

  function loadSortable (cb) {
    if (window.Sortable) return cb(window.Sortable)
    var s = document.createElement('script')
    s.src = SORTABLE_URL
    s.onload = function () { cb(window.Sortable) }
    s.onerror = function () { cb(null) }
    document.head.appendChild(s)
  }

  function plain (html) {
    var d = document.createElement('div'); d.innerHTML = html
    return (d.textContent || '').trim()
  }

  function mount (opts) {
    cleanup.forEach(function (fn) { fn() }); cleanup = []

    var schema = opts.schema
    var urls = opts.urls
    var types = Object.keys(schema)
    var history = D.createHistory(opts.document)
    var state = { doc: opts.document, selected: null, savedJson: JSON.stringify(opts.document), busy: false }
    var mediaPaths = {} // media id -> folder/filename, for thumbnails
    var sortables = [] // canvas lists, rebuilt on every render
    var paletteSortable = null
    var sortableLib = null
    var root = opts.root
    root.innerHTML = ''
    root.className = 'm4be'

    var status = el('span', { class: 'm4be-status' })
    var undoBtn = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Undo', onclick: function () { go(history.undo()) } })
    var redoBtn = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Redo', onclick: function () { go(history.redo()) } })
    var previewBtn = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Preview', onclick: preview })
    var resetBtn = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Reset to theme default', onclick: reset })
    var saveBtn = el('button', { type: 'button', class: 'btn btn-primary', text: 'Save', onclick: save })
    var palette = el('div', { class: 'm4be-palette' })
    var canvas = el('div', { class: 'm4be-canvas' })
    var inspector = el('div', { class: 'm4be-inspector' })
    var previewBox = el('div', { class: 'm4be-preview', hidden: 'hidden' })

    root.appendChild(el('div', { class: 'm4be-toolbar' }, [undoBtn, redoBtn, previewBtn, resetBtn, status, saveBtn]))
    root.appendChild(el('div', { class: 'm4be-grid' }, [
      el('div', { class: 'm4be-col' }, [el('h4', { text: 'Blocks' }), palette]),
      el('div', { class: 'm4be-col m4be-col-main' }, [el('h4', { text: 'Homepage' }), canvas]),
      el('div', { class: 'm4be-col' }, [el('h4', { text: 'Settings' }), inspector])
    ]))
    root.appendChild(previewBox)

    // ---------------------------------------------------------------- state
    function isDirty () { return JSON.stringify(state.doc) !== state.savedJson }

    // apply an edit; `key` lets rapid edits of one field be a single undo step
    function change (doc, key, opts2) {
      if (doc === state.doc) return
      state.doc = doc
      history.push(doc, key)
      refresh(opts2 || {})
    }

    function go (doc) {
      state.doc = doc
      if (state.selected && !D.find(doc, state.selected)) state.selected = null
      refresh({ inspector: true })
    }

    function refresh (what) {
      renderCanvas()
      if (what.inspector !== false) renderInspector()
      undoBtn.disabled = !history.canUndo()
      redoBtn.disabled = !history.canRedo()
      status.textContent = isDirty() ? 'Unsaved changes' : 'All changes saved'
      status.className = 'm4be-status' + (isDirty() ? ' is-dirty' : '')
    }

    function select (id) {
      state.selected = id
      renderCanvas(); renderInspector()
    }

    // ------------------------------------------------------------- palette
    function renderPalette () {
      palette.innerHTML = ''
      types.forEach(function (type) {
        var item = el('button', { type: 'button', class: 'm4be-palette-item', 'data-new-type': type, text: schema[type].label })
        item.addEventListener('click', function () { addAfterSelection(type) })
        palette.appendChild(item)
      })
      if (paletteSortable) paletteSortable.destroy()
      paletteSortable = sortableLib ? sortableLib.create(palette, {
        group: { name: 'blocks', pull: 'clone', put: false }, sort: false, animation: 120, forceFallback: true, fallbackOnBody: true
      }) : null
    }

    function addAfterSelection (type) {
      var block = D.newBlock(schema, type)
      var hit = state.selected && D.find(state.doc, state.selected)
      var next = hit ? D.insert(state.doc, hit.loc, hit.index + 1, block)
                     : D.insert(state.doc, { parent: null }, state.doc.blocks.length, block)
      state.selected = block.id
      change(next, null, { inspector: true })
    }

    // -------------------------------------------------------------- canvas
    function summary (block) {
      var p = block.props
      switch (block.type) {
        case 'heading': return p.text
        case 'text': return plain(p.html).slice(0, 80)
        case 'image': return p.image_id ? 'Image #' + p.image_id : 'No image chosen'
        case 'button': return p.label
        case 'spacer': return p.height + 'px'
        case 'latest_posts': return p.count + ' posts'
        default: return ''
      }
    }

    function renderCanvas () {
      sortables.forEach(function (s) { s.destroy() }); sortables = []
      canvas.innerHTML = ''
      canvas.appendChild(renderList(state.doc.blocks, { parent: null }))
      enableSortables()
    }

    function listAttrs (loc) {
      return { class: 'm4be-list', 'data-parent': loc.parent || '', 'data-column': loc.column === undefined || loc.column === null ? '' : loc.column }
    }

    function renderList (blocks, loc) {
      var list = el('div', listAttrs(loc))
      blocks.forEach(function (b) { list.appendChild(renderCard(b)) })
      if (!blocks.length) list.appendChild(el('div', { class: 'm4be-empty', text: 'Drop blocks here' }))
      var wrap = el('div', { class: 'm4be-listwrap' }, [list, addSelect(loc, blocks.length)])
      return wrap
    }

    function addSelect (loc, size) {
      var select = el('select', { class: 'm4be-add form-control', 'aria-label': 'Add block here' }, [el('option', { value: '', text: '+ Add block...' })])
      types.forEach(function (t) { select.appendChild(el('option', { value: t, text: schema[t].label })) })
      select.addEventListener('change', function () {
        if (!select.value) return
        var block = D.newBlock(schema, select.value)
        state.selected = block.id
        change(D.insert(state.doc, loc, size, block), null, { inspector: true })
      })
      return select
    }

    function renderCard (block) {
      var def = schema[block.type]
      var card = el('div', { class: 'm4be-card' + (block.id === state.selected ? ' is-selected' : ''), 'data-id': block.id })
      var buttons = [
        ['↑', 'Move up', function () { change(D.shift(state.doc, block.id, -1)) }],
        ['↓', 'Move down', function () { change(D.shift(state.doc, block.id, 1)) }],
        ['⧉', 'Duplicate', function () { change(D.duplicate(state.doc, block.id)) }],
        ['✕', 'Delete', function () {
          if (state.selected === block.id) state.selected = null
          change(D.remove(state.doc, block.id), null, { inspector: true })
        }]
      ].map(function (b) {
        return el('button', { type: 'button', class: 'm4be-iconbtn', title: b[1], 'aria-label': b[1], text: b[0], onclick: function (e) { e.stopPropagation(); b[2]() } })
      })
      var head = el('div', { class: 'm4be-cardhead' }, [
        el('span', { class: 'm4be-handle', title: 'Drag to move', text: '⋮⋮' }),
        el('strong', { text: def ? def.label : block.type }),
        el('span', { class: 'm4be-summary', text: summary(block) }),
        el('span', { class: 'm4be-actions' }, buttons)
      ])
      head.addEventListener('click', function () { select(block.id) })
      card.appendChild(head)

      if (def && def.container === 'blocks') {
        card.appendChild(el('div', { class: 'm4be-body' }, [renderList(block.children, { parent: block.id, column: null })]))
      } else if (def && def.container === 'columns') {
        var cols = el('div', { class: 'm4be-columns' })
        block.children.forEach(function (col, i) {
          cols.appendChild(el('div', { class: 'm4be-column' }, [renderList(col, { parent: block.id, column: i })]))
        })
        card.appendChild(el('div', { class: 'm4be-body' }, [cols]))
      }
      return card
    }

    function locOf (listEl) {
      var parent = listEl.getAttribute('data-parent')
      var col = listEl.getAttribute('data-column')
      return { parent: parent || null, column: col === '' ? null : parseInt(col, 10) }
    }

    function enableSortables () {
      if (!sortableLib) return
      var lists = canvas.querySelectorAll('.m4be-list')
      Array.prototype.forEach.call(lists, function (listEl) {
        sortables.push(sortableLib.create(listEl, {
          group: { name: 'blocks', pull: true, put: true },
          handle: '.m4be-handle', draggable: '.m4be-card', animation: 120,
          forceFallback: true, fallbackOnBody: true, swapThreshold: 0.65,
          onAdd: function (evt) { // a new block dragged in from the palette
            var type = evt.item.getAttribute('data-new-type')
            if (!type) return
            evt.item.parentNode.removeChild(evt.item)
            var block = D.newBlock(schema, type)
            state.selected = block.id
            change(D.insert(state.doc, locOf(evt.to), evt.newIndex, block), null, { inspector: true })
          },
          onEnd: function (evt) { // an existing block moved
            var id = evt.item.getAttribute('data-id')
            if (!id) return
            var next = D.move(state.doc, id, locOf(evt.to), evt.newIndex)
            if (next === state.doc) { refresh({}) ; return } // refused: redraw the original
            change(next)
          }
        }))
      })
    }

    // ----------------------------------------------------------- inspector
    function renderInspector () {
      inspector.innerHTML = ''
      var hit = state.selected && D.find(state.doc, state.selected)
      if (!hit) {
        inspector.appendChild(el('p', { class: 'text-muted', text: 'Select a block to edit it.' }))
        return
      }
      var block = hit.block
      var def = schema[block.type]
      inspector.appendChild(el('h5', { text: def.label }))

      if (def.container === 'columns') {
        var count = el('select', { class: 'form-control' })
        ;[2, 3, 4].forEach(function (n) { count.appendChild(el('option', { value: n, text: n + ' columns', selected: block.children.length === n ? 'selected' : null })) })
        count.addEventListener('change', function () { change(D.setColumnCount(state.doc, block.id, parseInt(count.value, 10)), null, { inspector: false }) })
        inspector.appendChild(field('Columns', count))
      }

      Object.keys(def.props).forEach(function (name) {
        var propDef = def.props[name]
        var value = block.props[name]
        var set = function (v, coalesce) {
          change(D.setProp(state.doc, block.id, name, v), coalesce ? block.id + ':' + name : null, { inspector: false })
        }
        inspector.appendChild(field(label(name), control(propDef, value, set, block)))
      })
    }

    function label (name) { var s = name.replace(/_/g, ' '); return s.charAt(0).toUpperCase() + s.slice(1) }

    function field (text, control) {
      return el('div', { class: 'form-group m4be-field' }, [el('label', { class: 'control-label', text: text }), control])
    }

    function control (propDef, value, set, block) {
      switch (propDef.type) {
        case 'string': case 'url':
          return el('input', { type: 'text', class: 'form-control', value: value || '', maxlength: propDef.max || 2000,
            oninput: function (e) { set(e.target.value, true) } })
        case 'int':
          return el('input', { type: 'number', class: 'form-control', value: value, min: propDef.min, max: propDef.max,
            oninput: function (e) { var n = parseInt(e.target.value, 10); if (!isNaN(n)) set(n, true) } })
        case 'enum':
          var select = el('select', { class: 'form-control' })
          propDef.values.forEach(function (v) { select.appendChild(el('option', { value: v, text: v, selected: v === value ? 'selected' : null })) })
          select.addEventListener('change', function () { set(select.value) })
          return select
        case 'color':
          var picker = el('input', { type: 'color', value: value || '#ffffff', oninput: function (e) { set(e.target.value, true) } })
          var clear = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'None', onclick: function () { set(''); renderInspector() } })
          return el('div', {}, [picker, clear, el('span', { class: 'm4be-hint', text: value || 'no background' })])
        case 'html':
          return htmlControl(value, set)
        case 'media':
          return mediaControl(value, set)
      }
      return el('span', { text: String(value) })
    }

    // small contenteditable; the server sanitizes whatever is saved
    function htmlControl (value, set) {
      var area = el('div', { class: 'm4be-rich form-control', contenteditable: 'true' })
      area.innerHTML = value || ''
      area.addEventListener('input', function () { set(area.innerHTML, true) })
      var tools = [['B', 'bold'], ['I', 'italic'], ['• List', 'insertUnorderedList']].map(function (t) {
        return el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: t[0],
          onmousedown: function (e) { e.preventDefault(); document.execCommand(t[1]); set(area.innerHTML, true) } })
      })
      tools.push(el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Link', onmousedown: function (e) {
        e.preventDefault()
        var url = window.prompt('Link address (https://...)')
        if (url) { document.execCommand('createLink', false, url); set(area.innerHTML, true) }
      } }))
      return el('div', {}, [el('div', { class: 'm4be-richtools' }, tools), area])
    }

    function mediaControl (value, set) {
      var thumb = el('img', { class: 'm4be-thumb', alt: '' })
      var showThumb = function (id) {
        var path = mediaPaths[id]
        if (path) { thumb.src = '/public/uploads/' + path; thumb.hidden = false } else { thumb.hidden = true }
      }
      showThumb(value)
      var choose = el('button', { type: 'button', class: 'btn btn-primary pmd-btn-flat', text: 'Choose image', 'data-target': '#ImageSelector',
        onclick: function () {
          if (!window.m4 || !window.m4.imageSelector) { toastr.error('The media picker is not available on this page.'); return }
          window.m4.imageSelector.modal(function (data) {
            mediaPaths[data.id] = data.folder + '/' + data.filename
            set(parseInt(data.id, 10)); renderInspector()
            $('#ImageSelector').modal('hide')
          })
        } })
      var clear = el('button', { type: 'button', class: 'btn btn-default pmd-btn-flat', text: 'Remove', onclick: function () { set(null); renderInspector() } })
      return el('div', {}, [thumb, el('div', {}, [choose, clear])])
    }

    // ------------------------------------------------------- save / preview
    function request (url, data) {
      return $.ajax({ type: 'post', url: url, data: data })
    }

    function save () {
      if (state.busy) return
      state.busy = true; saveBtn.disabled = true
      request(urls.save, { document: JSON.stringify(state.doc) }).done(function (r) {
        if (r.status === 'ERROR') return toastr.error(r.message)
        toastr.success(r.message)
        state.doc = r.document // server-normalized copy
        history = D.createHistory(r.document)
        state.savedJson = JSON.stringify(r.document)
        if (state.selected && !D.find(state.doc, state.selected)) state.selected = null
        refresh({ inspector: true })
      }).fail(function () { toastr.error('Request failed') })
        .always(function () { state.busy = false; saveBtn.disabled = false })
    }

    var resetArmed = null
    function reset () {
      if (!resetArmed) {
        resetBtn.textContent = 'Click again to confirm'
        resetArmed = setTimeout(function () { resetArmed = null; resetBtn.textContent = 'Reset to theme default' }, 3000)
        return
      }
      clearTimeout(resetArmed); resetArmed = null; resetBtn.textContent = 'Reset to theme default'
      request(urls.reset, {}).done(function (r) {
        if (r.status === 'ERROR') return toastr.error(r.message)
        toastr.success(r.message)
        var empty = { version: 1, blocks: [] }
        state.doc = empty; history = D.createHistory(empty); state.savedJson = JSON.stringify(empty); state.selected = null
        refresh({ inspector: true })
      }).fail(function () { toastr.error('Request failed') })
    }

    function preview () {
      previewBox.hidden = false
      previewBox.innerHTML = ''
      var name = 'm4be-frame'
      var close = el('button', { type: 'button', class: 'btn btn-default', text: 'Close preview', onclick: function () { previewBox.hidden = true; previewBox.innerHTML = '' } })
      var frame = el('iframe', { name: name, class: 'm4be-frame', title: 'Homepage preview' })
      var input = el('input', { type: 'hidden', name: 'document' })
      input.value = JSON.stringify(state.doc)
      var form = el('form', { method: 'post', action: urls.preview, target: name, hidden: 'hidden' }, [input])
      previewBox.appendChild(close); previewBox.appendChild(frame); previewBox.appendChild(form)
      form.submit()
    }

    // ------------------------------------------------------------ globals
    function onKey (e) {
      var t = e.target
      if (t && (t.isContentEditable || /^(INPUT|TEXTAREA|SELECT)$/.test(t.tagName))) return
      if (!(e.ctrlKey || e.metaKey)) return
      var k = e.key.toLowerCase()
      if (k === 'z' && !e.shiftKey) { e.preventDefault(); go(history.undo()) }
      else if (k === 'y' || (k === 'z' && e.shiftKey)) { e.preventDefault(); go(history.redo()) }
      else if (k === 's') { e.preventDefault(); save() }
    }
    function onLeave (e) { if (isDirty()) { e.preventDefault(); e.returnValue = '' } }
    document.addEventListener('keydown', onKey)
    window.addEventListener('beforeunload', onLeave)
    cleanup.push(function () {
      document.removeEventListener('keydown', onKey)
      window.removeEventListener('beforeunload', onLeave)
      sortables.forEach(function (s) { s.destroy() })
      if (paletteSortable) paletteSortable.destroy()
    })

    // thumbnails for images chosen in an earlier session
    $.get('/api/media/list').done(function (r) {
      ;(r.media || []).forEach(function (m) { mediaPaths[m.id] = m.folder + '/' + m.filename })
      renderInspector()
    })

    renderPalette(); refresh({ inspector: true })
    loadSortable(function (lib) {
      if (!lib) { status.textContent += ' (drag and drop unavailable, use the buttons)'; return }
      sortableLib = lib
      renderPalette(); renderCanvas()
    })
  }

  window.M4Blocks.mount = mount
})()
