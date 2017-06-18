# core/App.php

[App](https://github.com/Matoo125/M4CMS/blob/master/app/core/App.php) is the most important file of this application. It functions as a router: parses URL, creates instance of [Controller](Framework/Controller), calls Method and matches View (or just outputs JSON for API module or if view is not found).

We are using [front controller](https://en.wikipedia.org/wiki/Front_controller) pattern, which means all the requests are directed to [public/index.php]( https://github.com/Matoo125/M4CMS/blob/master/public/index.php ) file, which then initialize App class and it 

1. Parses URL (it creates array from URL string [received as `$_GET['url']` parameter] and sanitizes it)

   > To make this work we need to have configured URL rewriting. 
   >
   > Then [.htaccess](https://github.com/Matoo125/M4CMS/blob/master/public/.htaccess) will handle requests like /admin/post/edit, but in reality request looks like index.php?url=admin/post/edit . 
   >
   > So we can easily access it as GET parameter.

2. Sets Module (module is represented as folder in the controllers), (first parameter of URL)

3. Initializes Controller (if it does not exists it will call default controller), (first parameter of URL for default module, Otherwise second)

4. Calls Method (again if not set it will call default method and it will call 404 page if index method does not exists ), (second or third parameter of the URL, if not set default method will be used, if there are still some remaining parts of URL array, they will be passed as parameters with method call and can be caught in method itself)

5. Calls view based on `module/controller/method` path in `app/views` folder

#### Properties

- module [default: web],[sets module to be used], [build in: api, admin, web]
- controller [default: index], [sets the controller to be initialized]
- method [default: index], [sets the method to be called]
- params [default: empty array], [parameters to be passed to method]

#### Example Requests

| Route                                    | Description                              |
| ---------------------------------------- | ---------------------------------------- |
| /module/controller/method/param1/.../paramN | Ideal URL Structure                      |
| /admin/posts/edit/my-first-post          | Calls `edit` method with `my-first-post` parameter of `posts` controller in `admin` module and matches view. |
| /api/posts/edit/my-second-post           | The same as before, but `returns JSON`   |
| /posts/edit-my-third-post                | This one `uses the default Module [web]`, so it does not have to be in url. |
| /                                        | This calls default module, default controller and default method with no parameters. |

