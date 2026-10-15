# Homepage

The homepage can be built from blocks in **Admin → Homepage**. Until you save blocks, each theme shows its own built-in homepage; **Reset to theme default** brings it back.

## Blocks

| BLOCK        | PROPS                                         | NOTES
|:------------ |:--------------------------------------------- |:------
| heading      | text, level (1-4), align                      |
| text         | html, align                                   | rich text, sanitized on save
| image        | image from the media library, alt, size, align|
| button       | label, url, style, align                      | only http(s), mailto, `/path` and `#anchor` links
| spacer       | height (8-200 px)                             |
| columns      | gap; 2-4 columns, each holds blocks           | use it to split a view
| group        | background colour, padding                    | container for other blocks
| latest_posts | title, count (1-12)                           | newest published posts

Click a block in the palette to add it after the selected block, drag it into the page, or use the **+ Add block** list under any column or group. Cards have buttons to move, duplicate and delete. Undo/redo, **Preview** (rendered with your active theme, without saving) and **Save** are in the toolbar.

## Storage

The document is JSON in the `settings` table under `homepage_blocks`:

```json
{"version": 1, "blocks": [
  {"id": "a1b2", "type": "heading", "props": {"text": "Welcome", "level": 1}, "children": []}
]}
```

`columns` blocks hold a list of columns in `children`, each a list of blocks; `group` holds a list of blocks. Limits: 200 blocks, 5 levels deep, 256 KB. Unknown props are dropped and unknown block types are skipped when the page is rendered.

## Customising blocks in a theme

Each block is rendered by `<type>.twig`. The theme's own `public/themes/<theme>/blocks/<type>.twig` wins over the built-in `app/blocks/<type>.twig`, so a theme can restyle any block. Partials receive `props`, `block`, and for containers `children_html` (group) or `columns_html` (a list, one entry per column). Images also get `src`, `latest_posts` gets `posts` (`title`, `url`).

To make a theme show blocks, print `{{ homepage|raw }}` in its `Home/Index.twig` when it is set. See the bundled themes.

## Adding a new block type

1. Add an entry in `BlockSchema::types()` (`app/core/BlockSchema.php`) with its props. The editor builds its palette and inspector from this.
2. Add `app/blocks/<type>.twig`.
3. Run the tests: `php tests/blocks.php` and `node --test tests/editor-document.test.js`.
