<?php

namespace RouteDB\Model;

use MVC\Convert;
use MVC\DataType\DTDBWhere;
use MVC\DataType\DTDBWhereRelation;
use MVC\Lock;
use MVC\Log;
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
        Log::write(__METHOD__, __CLASS__ . '.log');

        // auto create and import from Route if no data in db table exists
        // checks on existance of `.imported` in RouteDB folder
        if (false === self::isImported())
        {
            \MVC\_ConcreteRoute::init();
            \RouteDB\Model\Route::autoImportIntoDatabase();
        }
    }

    /**
     * @return void
     */
    protected static function getdataImportedIntoTableFileAbs()
    {
        return realpath(__DIR__ . '/../') . '/.imported';
    }

    /**
     * @return bool
     */
    public static function isImported() : bool
    {
        return file_exists(\RouteDB\Model\Route::getdataImportedIntoTableFileAbs());
    }

    /**
     * @return false|int
     */
    protected static function setImported()
    {
        return file_put_contents(
            \RouteDB\Model\Route::getdataImportedIntoTableFileAbs(),
            date('Y-m-d H:i:s')
        );
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
            mOptional: (false === empty($oDTRouteDBModelDBTableRoute->get_additional())) ? Convert::unserialize($oDTRouteDBModelDBTableRoute->get_additional()) : null,
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
        self::init();

        Log::write(__LINE__, __CLASS__ . '.log');

        // not ready yet, call default
        if (false === \RouteDB\Model\Route::isImported())
        {
            Log::write(__LINE__, __CLASS__ . '.log');
            return \MVC\_ConcreteRoute::handleFallback();
        }

        #----------------

        // only once at runtime
        if (true === $bCacheAtRuntime && true === Registry::isRegistered(__METHOD__))
        {
            Log::write(__LINE__, __CLASS__ . '.log');
            return Registry::get(__METHOD__);
        }

        Log::write(Config::get_MVC_ROUTING_FALLBACK(), __CLASS__ . '.log');

        $oDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->getOnQuery(Config::get_MVC_ROUTING_FALLBACK());
        Log::write($oDTRouteDBModelDBTableRoute, __CLASS__ . '.log');

        $oDTRoute = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())
            ->set_methodsAssigned(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
            ->set_additional(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
        ;
        Registry::set(__METHOD__, $oDTRoute);

        Log::write(__LINE__, __CLASS__ . '.log');

        return $oDTRoute;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    protected static function autoImportIntoDatabase()
    {
        Log::write(__LINE__, __CLASS__ . '.log');

        // run after application is done
        Event::bind('mvc.application.destruct.before', function(){

            // auto create and import from Route if no data in db table exists
            if (false === \RouteDB\Model\Route::isImported())
            {
                Log::write(__LINE__, __CLASS__ . '.log');

                // only once at runtime
                if (true == Registry::isRegistered(__METHOD__))
                {
                    return;
                }

                Registry::set(__METHOD__, true);
                Lock::create(__FUNCTION__);

                // make sure there are no implications due to importing data into table
                Event::delete();
                Emvicy::clearcache();

                // empty table if there were already data before
                if (DB::use()->oRouteDBModelDBTableRoute->count() > 0)
                {
                    DB::use()->oRouteDBModelDBTableRoute->delete([
                        DTDBWhere::create()
                            ->set_sKey(DTRouteDBModelDBTableRoute::getPropertyName_id())
                            ->set_sRelation(DTDBWhereRelation::greaterThan)
                            ->set_sValue(0)
                    ]);
                }

                /**
                 * @var string       $sPath
                 * @var DTRoute $oDTRoute
                 */
                foreach (\MVC\Route::$aRoute as $sPath => $oDTRoute)
                {
                    // create
                    $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create($oDTRoute->getPropertyArray())
                        ->set_iSlashes(count(explode('/', $oDTRoute->get_path()))-1)
                        ->set_uuid(Strings::uuid4())
                        ->set_methodsAssigned(Convert::serialize($oDTRoute->get_methodsAssigned()))
                        ->set_additional(Convert::serialize($oDTRoute->get_additional()))
                        ->set_stampCreate(date('Y-m-d H:i:s'))
                        ->set_stampChange(date('Y-m-d H:i:s'))
                    ;
                    DB::use()->oRouteDBModelDBTableRoute->create($oDTRouteDBModelDBTableRoute);
                }

                \RouteDB\Model\Route::setImported();
            }
        });
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
                ->set_methodsAssigned(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
                ->set_additional(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
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

    /**
     * @param bool $bCacheAtRuntime
     * @return array
     * @throws \ReflectionException
     */
    public static function getRouteArray(bool $bCacheAtRuntime = true): array
    {
        if (true === $bCacheAtRuntime)
        {
            if (true === Registry::isRegistered(__METHOD__))
            {
                return Registry::get(__METHOD__);
            }
        }

        Log::write(__METHOD__, __CLASS__ . '.log');

        /** @var DTRouteDBModelDBTableRoute[] $aDTRouteDBModelDBTableRoute */
        $aDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->retrieve();
        $aDTRoute = array();

        foreach ($aDTRouteDBModelDBTableRoute as $oDTRouteDBModelDBTableRoute)
        {
            $aDTRoute[$oDTRouteDBModelDBTableRoute->get_path()] = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())->set_methodsAssigned(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()));
        }

        if (true === $bCacheAtRuntime)
        {
            Registry::set(__METHOD__, $aDTRoute);
        }

        return $aDTRoute;
    }

    /**
     * @param bool $bCacheAtRuntime
     * @return array
     * @throws \ReflectionException
     */
    public static function getMethodArray(bool $bCacheAtRuntime = true): array
    {
        if (true === $bCacheAtRuntime)
        {
            if (true === Registry::isRegistered(__METHOD__))
            {
                return Registry::get(__METHOD__);
            }
        }

        Log::write(__METHOD__, __CLASS__ . '.log');

        /** @var DTRouteDBModelDBTableRoute[] $aDTRouteDBModelDBTableRoute */
        $aDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->retrieve();
        $aDTRoute = array();

        foreach ($aDTRouteDBModelDBTableRoute as $oDTRouteDBModelDBTableRoute)
        {
            $oDTRouteDBModelDBTableRoute->set_requestMethod(strtolower($oDTRouteDBModelDBTableRoute->get_requestMethod()));

            (false === isset($aDTRoute[$oDTRouteDBModelDBTableRoute->get_requestMethod()]))
                ? $aDTRoute[$oDTRouteDBModelDBTableRoute->get_requestMethod()] = array()
                : false
            ;

            if (
                true === isset($aDTRoute[$oDTRouteDBModelDBTableRoute->get_requestMethod()]) &&
                false === in_array($oDTRouteDBModelDBTableRoute->get_path(), $aDTRoute[$oDTRouteDBModelDBTableRoute->get_requestMethod()])
            )
            {
                $aDTRoute[strtolower($oDTRouteDBModelDBTableRoute->get_requestMethod())][] = $oDTRouteDBModelDBTableRoute->get_path();
            }
        }

        if (true === $bCacheAtRuntime)
        {
            Registry::set(__METHOD__, $aDTRoute);
        }

        return $aDTRoute;
    }

    /**
     * @param bool $bCacheAtRuntime
     * @return array
     * @throws \ReflectionException
     */
    public static function getMethodRouteArray(bool $bCacheAtRuntime = true): array
    {
        if (true === $bCacheAtRuntime)
        {
            if (true === Registry::isRegistered(__METHOD__))
            {
                return Registry::get(__METHOD__);
            }
        }

        Log::write(__METHOD__, __CLASS__ . '.log');

        /** @var DTRouteDBModelDBTableRoute[] $aDTRouteDBModelDBTableRoute */
        $aDTRouteDBModelDBTableRoute = DB::use()->oRouteDBModelDBTableRoute->retrieve();
        $aDTRoute = array();

        foreach ($aDTRouteDBModelDBTableRoute as $oDTRouteDBModelDBTableRoute)
        {
            $aDTRoute[$oDTRouteDBModelDBTableRoute->get_requestMethod()][$oDTRouteDBModelDBTableRoute->get_path()] = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())->set_methodsAssigned(Convert::unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()));
        }

        if (true === $bCacheAtRuntime)
        {
            Registry::set(__METHOD__, $aDTRoute);
        }

        return $aDTRoute;
    }

    /*
     * Es folgen Methoden, welche noch nicht umgesetzt sind.
     * Es werden dann die Methoden verwendet von: \MVC\_ConcreteRoute
     */

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
//
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
//
//    public static function setPathParam(array $aPathParam = array()): void
//    {
//        // TODO: Implement setPathParam() method.
//    }
}
