---
currentMenu: app
---

# core/App.php

App class is the base router. It parses URL string into an sanitized array.
It takes care of prefixes (public, api, admin),
sets controller (default IndexController),
sets method (default index),
requires controller and create new instance of it,
sets parameters to be passed to method,
calls the method of the selected controller if it exists

has 4 member variables
1. controller
2. method
3. method_prefix
4. $params []
