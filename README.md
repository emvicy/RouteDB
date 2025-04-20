
# RouteDB

a Routing via Database Module for Emvicy2 (2.x) PHP Framework: https://github.com/emvicy/Emvicy/tree/2.x

---

## Installation

_cd into the modules folder of your `Emvicy` copy; e.g.:_
~~~bash
cd /var/www/html/modules/;
~~~

_git clone_
~~~bash
git clone --branch 1.x https://github.com/emvicy/RouteDB.git RouteDB;
~~~

_run emvicy to init module_    
~~~
cd /var/www/html/; \
php emvicy;
~~~

_set Route Class in your config_     
~~~php
/**-----------------------------------------------------------------------------------------------------------------
 * Route Class
 * it gets called via $GLOBALS['aConfig']['MVC_ROUTE_CLASS']::init();
 */
$aConfig['MVC_ROUTE_CLASS'] = '\RouteDB\Model\Route';
~~~