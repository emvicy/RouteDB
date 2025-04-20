<?php

namespace RouteDB\Model;

use RouteDB\DataType\DTRouteDBModelDBTableRoute;
use Emvicy\Emvicy;
use RouteDB\Model\DB\Collection\DB;
use MVC\_ConcreteRoute;
use MVC\Config;
use MVC\DataType\DTRoute;
use MVC\Event;
use MVC\Registry;
use MVC\Request;

use MVC\Strings;
use function Opis\Closure\{serialize, unserialize};

/**
 * Route
 */
class Route extends _ConcreteRoute
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public static function init() : void
    {
        // auto create and import from Route if no data in db table exists
        if (true === self::autoImportIntoDatabase())
        {
            foreach (array_unique(Config::get_MVC_ROUTING_DIR()) as $sRoutingDir)
            {
                if (true === file_exists($sRoutingDir))
                {
                    //  require recursively all php files in module's routing dir
                    /** @var \SplFileInfo $oSplFileInfo */
                    foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sRoutingDir)) as $oSplFileInfo)
                    {
                        if ('php' === strtolower($oSplFileInfo->getExtension()))
                        {
                            require_once $oSplFileInfo->getPathname();
                        }
                    }
                }
            }
        }
    }

    /**
     * @param bool $bCacheAtRuntime
     * @return DTRoute
     * @throws \ReflectionException
     */
    public static function getCurrent(bool $bCacheAtRuntime = true) : DTRoute
    {
        // Request
        $sRequestMethod = Request::in()->get_requestMethod();
        $sPath = Request::in()->get_path();
        $sRegistryKey = __METHOD__ . '.' . $sRequestMethod . '.' . $sPath;

        // only once at runtime
        if (true === $bCacheAtRuntime && true === Registry::isRegistered($sRegistryKey))
        {
            return Registry::get($sRegistryKey);
        }

        // Path 1:1 Match; e.g: /RouteDB/bar/
        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnRequestMethodPath($sRequestMethod, $sPath);
        if (false === empty($oDTRouteDBModelDBTableRoute->get_id()))
        {
            return self::setRoute($sRegistryKey, $sRequestMethod, $sPath, $oDTRouteDBModelDBTableRoute);
        }

        // Path 1:1 + Wildcard (/*) Match; e.g: /RouteDB/bar/*
        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnRequestMethodWildcardPath($sRequestMethod, $sPath . '*');
        if (false === empty($oDTRouteDBModelDBTableRoute->get_id()))
        {
            return self::setRoute($sRegistryKey, $sRequestMethod, $sPath, $oDTRouteDBModelDBTableRoute);
        }

        // Path Placeholder Match (concrete + Wildcard (/*)); e.g: /RouteDB/bar/:id/:name/ + /RouteDB/bar/:id/:name/*
        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnRequestMethodPlaceholderPath($sRequestMethod, $sPath);
        if (false === empty($oDTRouteDBModelDBTableRoute->get_id()))
        {
            return self::setRoute($sRegistryKey, $sRequestMethod, $sPath, $oDTRouteDBModelDBTableRoute);
        }

        return self::handleFallback();
    }

    /**
     * @param string                               $sRegistryKey
     * @param string                               $sRequestMethod
     * @param string                               $sPath
     * @param \RouteDB\DataType\DTRouteDBModelDBTableRoute $oDTRouteDBModelDBTableRoute
     * @return mixed
     * @throws \ReflectionException
     */
    private static function setRoute(string $sRegistryKey, string $sRequestMethod, string $sPath, DTRouteDBModelDBTableRoute $oDTRouteDBModelDBTableRoute)
    {
        \MVC\Route::{$sRequestMethod}(
            sPath: $oDTRouteDBModelDBTableRoute->get_path(),
            sClassMethod: $oDTRouteDBModelDBTableRoute->get_query(),
            mOptional: (false === empty($oDTRouteDBModelDBTableRoute->get_additional())) ? unserialize($oDTRouteDBModelDBTableRoute->get_additional()) : null,
            sTag: (false === empty($oDTRouteDBModelDBTableRoute->get_tag())) ? $oDTRouteDBModelDBTableRoute->get_tag() : '',
        );
        Registry::set($sRegistryKey, self::$aMethodRoute[$sRequestMethod][$oDTRouteDBModelDBTableRoute->get_path()]);

        return self::$aMethodRoute[$sRequestMethod][$oDTRouteDBModelDBTableRoute->get_path()];
    }

    /**
     * @param bool $bCacheAtRuntime
     * @return \MVC\DataType\DTRoute
     * @throws \ReflectionException
     */
    public static function handleFallback(bool $bCacheAtRuntime = true): DTRoute
    {
        // only once at runtime
        if (true === $bCacheAtRuntime && true === Registry::isRegistered(__METHOD__))
        {
            return Registry::get(__METHOD__);
        }

        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnQuery(Config::get_MVC_ROUTING_FALLBACK());
        $oDTRoute = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())
            ->set_methodsAssigned(unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
            ->set_additional(unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
        ;
        Registry::set(__METHOD__, $oDTRoute);

        return $oDTRoute;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    protected static function autoImportIntoDatabase()
    {
        // auto create and import from Route if no data in db table exists
        if (false === DB::use()->oRouteDBModelDBTableRoute->exists() || true === empty(DB::use()->oRouteDBModelDBTableRoute->count()))
        {
            Event::bind('mvc.route.init.after', function(){

                // only once at runtime
                if (true == Registry::isRegistered(__METHOD__)){return;}
                Registry::set(__METHOD__, true);
                Emvicy::clearcache();

                /**
                 * @var string       $sPath
                 * @var DTRoute $oDTRoute
                 */
                foreach (\MVC\Route::$aRoute as $sPath => $oDTRoute)
                {
                    $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create($oDTRoute->getPropertyArray())
                        ->set_iSlashes(count(explode('/', $oDTRoute->get_path()))-1)
                        ->set_uuid(Strings::uuid4())
                        ->set_methodsAssigned(serialize($oDTRoute->get_methodsAssigned()))
                        ->set_additional(serialize($oDTRoute->get_additional()))
                    ;
                    DB::use()->oRouteDBModelDBTableRoute->create($oDTRouteDBModelDBTableRoute);
                }
            });

            return true;
        }

        return false;
    }

    /**
     * @param bool $bWildcardsOnly
     * @return array
     * @throws \ReflectionException
     */
    public static function getIndices(bool $bWildcardsOnly = false): array
    {
        return DB::use()->oRouteDBModelDBTableRoute->getIndices($bWildcardsOnly);
    }

    /**
     * @param string $sTag
     * @param bool   $bCacheAtRuntime
     * @return DTRoute
     * @throws \ReflectionException
     */
    public static function getOnTag(string $sTag = '', bool $bCacheAtRuntime = true): DTRoute
    {
        if (true === empty($sTag))
        {
            return DTRoute::create();
        }

        // only once at runtime
        if (true === $bCacheAtRuntime && true === Registry::isRegistered(__FUNCTION__ . '.' . $sTag))
        {
            return Registry::get(__FUNCTION__ . '.' . $sTag);
        }

        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnTag($sTag);

        if (true === empty($oDTRouteDBModelDBTableRoute->get_id()))
        {
            $oDTRoute = DTRoute::create();
        }
        else
        {
            $oDTRoute = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())
                ->set_methodsAssigned(unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
                ->set_additional(unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
            ;
        }

        if (true === $bCacheAtRuntime)
        {
            Registry::set(__FUNCTION__ . '.' . $sTag, $oDTRoute);
        }

        return $oDTRoute;
    }

    /**
     * returns assoc array DTRoute where keys are the tags of its DTRoute
     * @example Route::getTagList()
     *          Route::getTagList()['home']
     *          Route::getTagList()['home']->get_additional()
     * @return array|\MVC\DataType\DTRoute[]
     * @throws \ReflectionException
     */
    public static function getTagList() : array
    {
        return DB::use()->oRouteDBModelDBTableRoute->getTagList();
    }

//    public static function any(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement any() method.
//    }
//
//    public static function mix(array $aMethod = array(), string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement mix() method.
//    }
//
//    public static function get(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement get() method.
//    }
//
//    public static function post(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement post() method.
//    }
//
//    public static function put(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement put() method.
//    }
//
//    public static function patch(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement patch() method.
//    }
//
//    public static function options(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement options() method.
//    }
//
//    public static function delete(string $sPath = '', string $sClassMethod = '', mixed $mOptional = '', string $sTag = ''): void
//    {
//        // TODO: Implement delete() method.
//    }

//    public static function add(string $sRequestMethod = '*', string $sPath = '', string $sClassMethod = '', mixed $mOptional = null, string $sTag = ''): void
//    {
//        // TODO: Implement add() method.
//    }
//    public static function getRouteIndexArrayOnKey(string $sKey = 'query', string $sValue = ''): array
//    {
//        // TODO: Implement getRouteIndexArrayOnKey() method.
//    }
//
//    public static function getIndexOnWildcard(string $sPath = ''): string
//    {
//        // TODO: Implement getIndexOnWildcard() method.
//    }
//
//    public static function getPathOnPlaceholderIndex(string $sPath = ''): string
//    {
//        // TODO: Implement getPathOnPlaceholderIndex() method.
//    }

//    public static function setPathParam(array $aPathParam = array()): void
//    {
//        // TODO: Implement setPathParam() method.
//    }
}
