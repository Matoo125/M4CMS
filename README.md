[OFFICIAL WEBSITE WITH TUTORIALS AND DEMO](http://m4cms.6f.sk/)

![screenshot](https://cdn.pbrd.co/images/GOroDEZ.png "Create new Page Screenshot")


COMPOSER DEPENDENCIES:
1. [m4mvc](https://github.com/Matoo125/M4Admin) (M4MVC Framework)
2. [Image intervention](http://image.intervention.io/) (Image Manipulation)


Whole application was rewritten again, this time from VueJS to twig templates.I made page lodating in administration asynchronic, so UX is not worse, than in Vue. The biggest advantage is, that now it will be much easier to create plugins. 

For now, it is possible to use CMS and create custom themes easily with twig. I'm still working on good plugin management.

TODO
- gallery needs some settings for classes and structure so it can work with different templates


to clone repo
```
git clone https://github.com/Matoo125/M4CMS.git
cd M4CMS
:: to install dependencies [(composer needs to be installed)](https://getcomposer.org/download/)
cd app
composer install
```
2 websites are running on this. Official Documentation and [Slovak Vegan](http://slovakvegan.eu)
