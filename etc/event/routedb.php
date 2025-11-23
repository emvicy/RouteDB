<?php

\MVC\Event::processBindConfigStack([

    'routedb.model.route.init.before' => [
        function($oDTValue) {
            \MVC\Log::write($oDTValue, 'debug.log');
        }
    ],
    'routedb.model.route.getdataImportedIntoTableFileAbs' => [
        function($oDTValue) {
            \MVC\Log::write($oDTValue, 'debug.log');
        }
    ],
    'routedb.model.route.init.after' => [
        function($oDTValue) {
            \MVC\Log::write($oDTValue, 'debug.log');
        }
    ],
    'routedb.model.route.setRoute.before' => [
        function($oDTRouteDBModelDBTableRoute) {
            \MVC\Log::write($oDTRouteDBModelDBTableRoute, 'debug.log');
        }
    ],
    'routedb.model.route.setImported.before' => [
        function() {
            \MVC\Log::write('set file `.imported`', 'debug.log');
        }
    ],
    'routedb.model.route.setImported.after' => [
        function($bPut) {
            \MVC\Log::write($bPut, 'debug.log');
        }
    ],
    'routedb.model.route.removeImported.before' => [
        function($oDTValue) {
            \MVC\Log::write($oDTValue, 'debug.log');
        }
    ],
    'routedb.model.route.removeImported.after' => [
        function($bUnlink) {
            \MVC\Log::write($bUnlink, 'debug.log');
        }
    ],
    'routedb.model.route.getCurrent.before' => [
        function($oDTValue) {
//            \MVC\Log::write($oDTValue, 'debug.log');
        }
    ],
    'routedb.model.route.getCurrent.after' => [
        function($oDTRouteDBModelDBTableRoute) {
//            \MVC\Log::write($oDTRouteDBModelDBTableRoute, 'debug.log');
        }
    ],
    'routedb.model.route.handleFallback.after' => [
        function($oDTRoute) {
            \MVC\Log::write($oDTRoute, 'debug.log');
        }
    ],
]);