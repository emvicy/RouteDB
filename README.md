
# RouteDB

a Routing via Database Module for Emvicy PHP Framework
- <a href="https://github.com/emvicy/Emvicy/tree/2.x">Emvicy2 (2.x)</a> ✅
- <a href="https://github.com/emvicy/Emvicy/tree/3.x">Emvicy3 (3.x)</a> ✅

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

## Usage

**auto-import routes** 

Once the Route Class is set in your config, all routes are automatically  
imported once to the database table `RouteDBModelDBTableRoute` on any next request.      

**resolving**

From the moment all routes are imported, all further requests will be resolved by that database table.

**force import** 

if you want to force an import of your current routes settings you can do this by calling the import command on cli:

~~~bash
php emvicy routes:dbimport
~~~
or, shorthand
~~~bash
php emvicy rtdbi
~~~


