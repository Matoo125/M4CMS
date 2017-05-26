# Welcome to M4MVC Framework Documentation

M4MVC [Framework](https://en.wikipedia.org/wiki/Software_framework) is using as the name suggests [Model, View, Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture. Main Framework functionality can be found in [app/core](https://github.com/Matoo125/M4CMS/tree/master/app/core) folder, but there are also important [app/helper](https://github.com/Matoo125/M4CMS/tree/master/app/helper ) methods and [config](https://github.com/Matoo125/M4CMS/tree/master/app/config) files.

There are 3 core files:

- [App](Framework/app.md)
- [Model](Framework/model.md)
- [Controller](Framework/controller.md)


Also it is worth noting here, that this application us taking advantage of composer autoloading feature and all classes are loaded by it. 

```json
"autoload": {
  "psr-4": {
    "app\\":""
  }
}
// app/composer.json
```

We are also using composer for all the dependencies

| Dependency         | Version | Description        |
| ------------------ | ------- | ------------------ |
| twig/twig          | ^2.0    | Template Engine    |
| intervention/image | ^2.3    | Image manipulation |