---
currentMenu: app
---

# core/App.php

App class is the base router. It parses URL string into an sanitized array.
It takes care of modules (public, api, admin),
sets controller (default Index),
sets method (default index),
requires controller and create new instance of it,
sets parameters to be passed to method,
calls the method of the selected controller if it exists
calls the view if exists (based on module/controller/method.twig  path)


has 4 properties
1. controller
2. method
3. module
4. $params []
