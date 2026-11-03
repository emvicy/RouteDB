# Changelog

## [Unreleased]

### Backlog

**bug**

### In Progress

**added**

- `\RouteDB\Model\DB\Table\Route::getOnPath`: returns a DTRouteDBModelDBTableRoute object on a given, defined Route path
- `\RouteDB\Model\DB\Table\Route::getDTRoute`: returns a DTRoute Object on a given DTRouteDBModelDBTableRoute object
- command for emvicy console: `php emvicy routes:dbimport`|`php emvicy rtdbi` => imports Route config into database table `RouteDBModelDBTableRoute` for new.
- Events

**changed**

- `\RouteDB\Model\Route::autoImportIntoDatabase`
- `\RouteDB\Model\Route::getCurrent`
- `\RouteDB\Model\Route::handleFallback`
- `etc/event/routedb.php`: log file renamed to `routedb.log`
- `Model/DB/Table/Route.php`: +Field `'pathParamArray'  => "tinytext NULL COMMENT ''",`
- `DataType/DTRouteDBModelDBTableRoute.php`

**deprecated**
**removed**  
**fixed**  
**security**

---

## [Releases]

### [1.0] - 2026-11-03, https://github.com/emvicy/RouteDB/releases/tag/1.0

**added**

- `\RouteDB\Model\DB\Table\Route::getOnPath`: returns a DTRouteDBModelDBTableRoute object on a given, defined Route path
- `\RouteDB\Model\DB\Table\Route::getDTRoute`: returns a DTRoute Object on a given DTRouteDBModelDBTableRoute object
- command for emvicy console: `php emvicy routes:dbimport`|`php emvicy rtdbi` => imports Route config into database table `RouteDBModelDBTableRoute` for new.
- Events

**changed**

- `\RouteDB\Model\Route::autoImportIntoDatabase`
- `\RouteDB\Model\Route::getCurrent`
- `\RouteDB\Model\Route::handleFallback`
- `etc/event/routedb.php`: log file renamed to `routedb.log`
- `Model/DB/Table/Route.php`: +Field `'pathParamArray'  => "tinytext NULL COMMENT ''",`
- `DataType/DTRouteDBModelDBTableRoute.php`

### [PRE] - 2025-04-20

- first Release

