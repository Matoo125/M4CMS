# app/core/Controller.php

[This](https://github.com/Matoo125/M4CMS/blob/master/app/core/Controller.php) is [Abstract](http://php.net/manual/en/language.oop5.abstract.php) Controller [Class](http://php.net/manual/en/language.oop5.php) which is intended to be extended by every controller. It might also be extended by Controller which is extended by another Controller. It has two methods. It can instantiates model and calls corresponding view. For views we are using [twig](https://twig.sensiolabs.org/) files. We are passing some global variables to every twig template .

```
session      -> $_SESSION
sessionclass -> new app/helper/Session
slugifilter  -> slugify helper function
lang         -> active language array
url          -> active url array
```

If you don't want to use twig for some reason, you can create your own Abstract Controller with custom view function and use different template engine or just plain PHP. Also you can just write another method to this controller and create push request.

If you want to use this as an API just use API module or don't create views and it will render JSON automatically. 

### Parameters

Also you can specify view with `$this->view = 'new/view/path'` and it will be called instead of matching exact paths.

To pass data to template you do `$this->data = []` from controller. This array can be nested multiple levels. Then just use it from twig.

Third parameter `$this->model` is used to store instance of model. 