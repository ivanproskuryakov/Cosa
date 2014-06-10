About
========================

CMS based on Symfony 2

Installation
========================

1.) Clone project and install dependencies with: <br/>
php composer.phar install<br/>
0.) Set parameters<br/>
rename parameters.yml.dev to parameters.yml<br/>
1.) Create database<br/>
doctrine:database:create<br/>
2.) Create tables<br/>
php app/console doctrine:schema:create<br/>
3.) Load initial data in database with<br/>
php app/console doctrine:fixtures:load<br/>
5.) Add Links to CSS and JS files<br/>
php app/console assets:install web --symlink

Once this steps is done you will be able to access admin section from http://yourwebsitename.dev/administration/
and frontend at http://yourwebsitename.dev/<br/><br/>

Frontend User: [frontenduser/frontenduser]<br/>
Backend User: [backenduser/backenduser]<br/>
Bug tracking
========================

Project uses [GitHub issues](https://github.com/ivanproskuryakov/Cosa/issues).
If you have found bug, please create an issue.

MIT License
-----------

License can be found [here](https://github.com/ivanproskuryakov/Cosa/blob/master/LICENSE).

Authors
-------

Aisel was originally created by [Ivan Proskuryakov](http://www.magazento.com).
See the list of [contributors](https://github.com/ivanproskuryakov/Cosa/graphs/contributors).