
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

---

### using with Policy

**Policy::bindOnRoute**

use Table Route to get proper DTRoute Object

~~~php
\MVC\Policy::bindOnRoute(
    // map into DTRoute object
    \RouteDB\Model\DB\Table\Route::use()->getDTRoute(
        // get DTRouteDBModelDBTableRoute object
        \RouteDB\Model\DB\Table\Route::use()->getOnPath($sRoute)
    ),
    $aRule
);
~~~

---

## Events

~~~php
Event::run('routedb.model.route.init.before', $oDTValue);
~~~
- `$oDTValue->get_mValue()`: `bool $bForceImport`: force an import true|false

~~~php
Event::run('routedb.model.route.init.after', $oDTValue);
~~~
- `$oDTValue->get_mValue()`: `bool $bForceImport`: force an import true|false

~~~php
Event::run('routedb.model.route.setImported.before');
~~~

~~~php
Event::run('routedb.model.route.setImported.after', (bool) $bPut);
~~~
- `bool $bPut`: import success true|false

~~~php
Event::run('routedb.model.route.removeImported.before', $oDTValue);
~~~
- `$oDTValue->get_mValue()`: `string $sFile`: `modules/RouteDB/.imported`

~~~php
Event::run('routedb.model.route.removeImported.after', $bUnlink);
~~~
- `bool $bUnlink`: unlink import file success true|false

~~~php
Event::run('routedb.model.route.getdataImportedIntoTableFileAbs', $oDTValue);
~~~
- `$oDTValue->get_mValue()`: `string $sFile`: `modules/RouteDB/.imported`

~~~php
Event::run('routedb.model.route.getCurrent.before', $oDTValue);
~~~
- `$oDTValue->get_mValue()`: `bool $bCacheAtRuntime`: true|false

~~~php
Event::run('routedb.model.route.getCurrent.after', $oDTRouteDBModelDBTableRoute);
~~~

~~~php
Event::run('routedb.model.route.setRoute.before', $oDTRouteDBModelDBTableRoute);
~~~

~~~php
Event::run('routedb.model.route.handleFallback.after', $oDTRoute);
~~~






























