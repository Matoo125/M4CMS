// Run: node --test tests/
const test = require('node:test')
const assert = require('node:assert/strict')
const D = require('../app/admin/assets/m4/standalone/blocks-document.js')

const schema = {
  heading: { container: null, props: { text: { default: 'Heading' } } },
  group: { container: 'blocks', props: {} },
  columns: { container: 'columns', props: { gap: { default: 'medium' } } }
}
const empty = () => ({ version: 1, blocks: [] })
const ids = (list) => list.map((b) => b.id)

function sample () {
  // root: [a, cols[ [b], [c] ], g[ d ]]
  let doc = empty()
  doc = D.insert(doc, { parent: null }, 0, D.newBlock(schema, 'heading', 'a'))
  doc = D.insert(doc, { parent: null }, 1, D.newBlock(schema, 'columns', 'cols'))
  doc = D.insert(doc, { parent: null }, 2, D.newBlock(schema, 'group', 'g'))
  doc = D.insert(doc, { parent: 'cols', column: 0 }, 0, D.newBlock(schema, 'heading', 'b'))
  doc = D.insert(doc, { parent: 'cols', column: 1 }, 0, D.newBlock(schema, 'heading', 'c'))
  doc = D.insert(doc, { parent: 'g', column: null }, 0, D.newBlock(schema, 'heading', 'd'))
  return doc
}

test('newBlock uses defaults and the right children shape', () => {
  assert.equal(D.newBlock(schema, 'heading').props.text, 'Heading')
  assert.deepEqual(D.newBlock(schema, 'columns').children, [[], []])
  assert.deepEqual(D.newBlock(schema, 'group').children, [])
  assert.throws(() => D.newBlock(schema, 'nope'))
})

test('operations never mutate their input', () => {
  const doc = sample()
  const frozen = JSON.stringify(doc)
  D.remove(doc, 'a'); D.move(doc, 'a', { parent: 'g', column: null }, 0)
  D.duplicate(doc, 'cols'); D.setProp(doc, 'a', 'text', 'x'); D.setColumnCount(doc, 'cols', 4)
  assert.equal(JSON.stringify(doc), frozen)
})

test('find locates blocks at any depth', () => {
  const doc = sample()
  assert.deepEqual(D.find(doc, 'c').loc, { parent: 'cols', column: 1 })
  assert.deepEqual(D.find(doc, 'd').loc, { parent: 'g', column: null })
  assert.equal(D.find(doc, 'zzz'), null)
})

test('insert clamps the index and ignores missing parents', () => {
  let doc = D.insert(empty(), { parent: null }, 99, D.newBlock(schema, 'heading', 'x'))
  assert.deepEqual(ids(doc.blocks), ['x'])
  assert.equal(D.insert(doc, { parent: 'ghost', column: null }, 0, D.newBlock(schema, 'heading', 'y')), doc)
})

test('remove deletes nested blocks with their children', () => {
  const doc = D.remove(sample(), 'cols')
  assert.deepEqual(ids(doc.blocks), ['a', 'g'])
  assert.equal(D.find(doc, 'b'), null)
  assert.equal(D.count(doc), 3)
})

test('move across lists and within a list', () => {
  let doc = D.move(sample(), 'a', { parent: 'cols', column: 1 }, 0)
  assert.deepEqual(ids(D.listAt(doc, { parent: 'cols', column: 1 })), ['a', 'c'])
  doc = D.move(sample(), 'a', { parent: null }, 3) // to the end of its own list
  assert.deepEqual(ids(doc.blocks), ['cols', 'g', 'a'])
  doc = D.move(sample(), 'g', { parent: null }, 0)
  assert.deepEqual(ids(doc.blocks), ['g', 'a', 'cols'])
})

test('a block cannot be moved into itself or its descendants', () => {
  const doc = sample()
  assert.equal(D.move(doc, 'cols', { parent: 'cols', column: 0 }, 0), doc)
  assert.equal(D.move(doc, 'g', { parent: 'd', column: null }, 0), doc)
})

test('shift steps by one and stops at the ends', () => {
  const doc = sample()
  assert.deepEqual(ids(D.shift(doc, 'a', 1).blocks), ['cols', 'a', 'g'])
  assert.deepEqual(ids(D.shift(doc, 'g', -1).blocks), ['a', 'g', 'cols'])
  assert.equal(D.shift(doc, 'a', -1), doc)
  assert.equal(D.shift(doc, 'g', 1), doc)
})

test('duplicate deep-copies with fresh ids right after the original', () => {
  let n = 0
  const doc = D.duplicate(sample(), 'cols', () => 'n' + (++n))
  assert.deepEqual(ids(doc.blocks), ['a', 'cols', 'n1', 'g'])
  const copy = D.find(doc, 'n1').block
  assert.deepEqual(copy.children.map((col) => ids(col)), [['n2'], ['n3']])
})

test('setProp changes one prop', () => {
  const doc = D.setProp(sample(), 'a', 'text', 'Hello')
  assert.equal(D.find(doc, 'a').block.props.text, 'Hello')
})

test('setColumnCount grows, and shrinking merges into the last column', () => {
  let doc = D.setColumnCount(sample(), 'cols', 4)
  assert.equal(D.find(doc, 'cols').block.children.length, 4)
  doc = D.setColumnCount(sample(), 'cols', 2)
  assert.equal(D.setColumnCount(doc, 'cols', 9).blocks[1].children.length, 4) // clamped
  const three = D.insert(D.setColumnCount(sample(), 'cols', 3), { parent: 'cols', column: 2 }, 0, D.newBlock(schema, 'heading', 'e'))
  const merged = D.setColumnCount(three, 'cols', 2)
  assert.deepEqual(merged.blocks[1].children.map((col) => ids(col)), [['b'], ['c', 'e']])
  assert.equal(D.setColumnCount(sample(), 'a', 3).blocks[0].id, 'a') // not a columns block: unchanged
})

test('history: undo, redo, truncation and no-op pushes', () => {
  const h = D.createHistory('A')
  h.push('B'); h.push('C')
  assert.equal(h.undo(), 'B'); assert.equal(h.undo(), 'A'); assert.equal(h.canUndo(), false)
  assert.equal(h.redo(), 'B')
  h.push('D') // drops C
  assert.equal(h.canRedo(), false); assert.equal(h.undo(), 'B')
  h.push('B'); assert.equal(h.canRedo(), true) // identical to current: nothing recorded
})

test('history coalesces rapid edits with the same key', () => {
  let t = 0
  const h = D.createHistory('A', { now: () => t, window: 800 })
  h.push('B'); t = 100; h.push('Bc', 'text'); t = 200; h.push('Bcd', 'text')
  assert.equal(h.undo(), 'B') // typing was a single step
  t = 5000; h.redo(); h.push('E', 'text'); t = 5100; h.push('F', 'other')
  assert.equal(h.undo(), 'E')
})

test('history respects its size limit', () => {
  const h = D.createHistory(0, { limit: 3 })
  ;[1, 2, 3, 4].forEach((n) => h.push(n))
  assert.equal(h.current(), 4)
  h.undo(); h.undo()
  assert.equal(h.current(), 2); assert.equal(h.canUndo(), false)
})
