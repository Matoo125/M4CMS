---
currentMenu: directories
---

# Directory Structure

```
M4CMS
│   .couscous
│   __install   
│
└───app
│   │   file011.txt
│   │   file012.txt
│   │
│   └───subfolder1
│       │   file111.txt
│       │   file112.txt
│       │   ...
│   
└───public
    │   css
    │	  └─style.css
    │   .htaccess.php
```

```
├───.couscous
│   └───generated
│       ├───css
│       └───fonts
├───.idea
│   ├───dataSources
│   │   ├───446fd566-a1b0-4008-932b-e6928f1fb18e
│   │   ├───67d82618-f28d-41e1-b532-1829d5ab3e33
│   │   └───f289b6ca-207f-47a0-aeca-26315444fe85
│   └───inspectionProfiles
├───app
│   ├───config
│   ├───controllers
│   │   ├───admin
│   │   ├───api
│   │   ├───public
│   │   └───web
│   ├───core
│   ├───helper
│   ├───model
│   ├───string
│   ├───vendor
│   │   ├───composer
│   │   ├───symfony
│   │   │   └───polyfill-mbstring
│   │   │       └───Resources
│   │   │           └───unidata
│   │   └───twig
│   │       └───twig
│   │           ├───doc
│   │           │   ├───filters
│   │           │   ├───functions
│   │           │   ├───tags
│   │           │   └───tests
│   │           ├───lib
│   │           │   └───Twig
│   │           │       ├───Cache
│   │           │       ├───Error
│   │           │       ├───Extension
│   │           │       ├───Loader
│   │           │       ├───Node
│   │           │       │   └───Expression
│   │           │       │       ├───Binary
│   │           │       │       ├───Filter
│   │           │       │       ├───Test
│   │           │       │       └───Unary
│   │           │       ├───NodeVisitor
│   │           │       ├───Profiler
│   │           │       │   ├───Dumper
│   │           │       │   ├───Node
│   │           │       │   └───NodeVisitor
│   │           │       ├───Sandbox
│   │           │       ├───Test
│   │           │       ├───TokenParser
│   │           │       └───Util
│   │           └───test
│   │               └───Twig
│   │                   └───Tests
│   │                       ├───Cache
│   │                       ├───Extension
│   │                       ├───Fixtures
│   │                       │   ├───autoescape
│   │                       │   ├───errors
│   │                       │   ├───exceptions
│   │                       │   ├───expressions
│   │                       │   ├───extensions
│   │                       │   ├───filters
│   │                       │   ├───functions
│   │                       │   │   └───include
│   │                       │   ├───macros
│   │                       │   ├───regression
│   │                       │   ├───tags
│   │                       │   │   ├───autoescape
│   │                       │   │   ├───block
│   │                       │   │   ├───embed
│   │                       │   │   ├───filter
│   │                       │   │   ├───for
│   │                       │   │   ├───if
│   │                       │   │   ├───include
│   │                       │   │   ├───inheritance
│   │                       │   │   ├───macro
│   │                       │   │   ├───sandbox
│   │                       │   │   ├───set
│   │                       │   │   ├───spaceless
│   │                       │   │   ├───use
│   │                       │   │   ├───verbatim
│   │                       │   │   └───with
│   │                       │   └───tests
│   │                       ├───LegacyFixtures
│   │                       │   ├───autoescape
│   │                       │   └───functions
│   │                       ├───Loader
│   │                       │   └───Fixtures
│   │                       │       ├───inheritance
│   │                       │       ├───named
│   │                       │       ├───named_bis
│   │                       │       ├───named_final
│   │                       │       ├───named_quater
│   │                       │       ├───named_ter
│   │                       │       ├───normal
│   │                       │       ├───normal_bis
│   │                       │       ├───normal_final
│   │                       │       ├───normal_ter
│   │                       │       ├───phar
│   │                       │       └───themes
│   │                       │           ├───theme1
│   │                       │           └───theme2
│   │                       ├───Node
│   │                       │   └───Expression
│   │                       │       ├───Binary
│   │                       │       └───Unary
│   │                       ├───NodeVisitor
│   │                       ├───Profiler
│   │                       │   └───Dumper
│   │                       └───Util
│   └───view
│       ├───admin
│       │   └───index
│       ├───api
│       │   └───index
│       └───web
│           ├───index
│           └───user
├───docs
├───public
│   └───css
│       └───admin
└───__install
```