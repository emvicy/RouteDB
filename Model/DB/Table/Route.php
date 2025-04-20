<?php

namespace RouteDB\Model\DB\Table;

use RouteDB\DataType\DTRouteDBModelDBTableRoute;
use MVC\DataType\DTRoute;
use MVC\DB\Model\Db;
use MVC\DataType\DTDBWhere;
use MVC\DB\Trait\TraitDbInit;
use function Opis\Closure\{serialize, unserialize};

class Route extends Db
{
    use TraitDbInit;

    /**
     * @var array
     */
    protected $aField = array(
        'uuid'            => "varchar(36) NOT NULL DEFAULT uuid() COMMENT 'uuid'",
        'path'            => "varchar(255) NOT NULL COMMENT 'Pfad'",
        'iSlashes'        => "int(3) NOT NULL COMMENT 'Amount of Slashes'",
        'requestMethod'   => "varchar(255) NOT NULL COMMENT ''",
        'methodsAssigned' => "varchar(255) NOT NULL COMMENT 'Request methods'",
        'query'           => "varchar(255) NOT NULL COMMENT 'Class::method'",
        'module'          => "varchar(255) NOT NULL COMMENT ''",
        'class'           => "varchar(255) NOT NULL COMMENT ''",
        'method'          => "varchar(255) NOT NULL COMMENT ''",
        'classFile'       => "varchar(255) NOT NULL COMMENT ''",
        'additional'      => "text NULL COMMENT ''",
        'tag'             => "varchar(255) NULL COMMENT 'Tag'",
        "description"     => "text NOT NULL DEFAULT '' COMMENT 'Description'",
    );

    /**
     * @param array $aDbConfig
     * @throws \ReflectionException
     */
    public function __construct(array $aDbConfig = array())
    {
        parent::__construct(
            $this->aField,
            $aDbConfig
        );
    }

    /**
     * @param string $sRequestMethod
     * @param string $sPath
     * @return \RouteDB\DataType\DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public function getOnRequestMethodPath(string $sRequestMethod = '', string $sPath = '')
    {
        $sSql = "SELECT *
        FROM `" . $this->sTableName . "` 
        WHERE 1
        AND `path` NOT LIKE '%*'
        AND `path` = '" . $sPath . "'
        AND (
            `methodsAssigned` LIKE '%" . $sRequestMethod . "%'
            OR
            `methodsAssigned` LIKE '%*%'
        );";
        return $this->fetchRow($sSql, bReturnDatatypeObject: true);
    }

    /**
     * @param string $sRequestMethod
     * @param string $sPath
     * @return \RouteDB\DataType\DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public function getOnRequestMethodWildcardPath(string $sRequestMethod = '', string $sPath = '')
    {
        $sSql = "SELECT *
        FROM `" . $this->sTableName . "` 
        WHERE 1
        AND `path` LIKE '%*'
        AND (LENGTH(path)-1) <= LENGTH('" . $sPath . "')
        AND SUBSTRING(path, 1, (LENGTH(path)-1)) = SUBSTRING('" . $sPath . "', 1, (LENGTH(path)-1))
        AND (
            `methodsAssigned` LIKE '%" . $sRequestMethod . "%'
            OR
            `methodsAssigned` LIKE '%*%'
        );";
        return $this->fetchRow($sSql, bReturnDatatypeObject: true);
    }

    /**
     * @param string $sRequestMethod
     * @param string $sPath
     * @return \RouteDB\DataType\DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public function getOnRequestMethodPlaceholderPath(string $sRequestMethod = '', string $sPath = '')
    {
        $iAmountSlashesPath = (count(explode('/', $sPath))-1);

        $sSql = "SELECT *
        FROM `" . $this->sTableName . "` 
        WHERE 1
        AND `iSlashes` <= '" . $iAmountSlashesPath . "'
        AND (
            `methodsAssigned` LIKE '%" . $sRequestMethod . "%'
            OR
            `methodsAssigned` LIKE '%*%'
        )  
        AND 
        (
            `path` Like '%/:%'
            OR 
            `path` LIKE '%/{%'
        );";
        $aDTRouteDBModelDBTableRoute = $this->fetchAll($sSql);

        $aRouteOnly = array_map(
            function($oDTRouteDBModelDBTableRoute){
                return $oDTRouteDBModelDBTableRoute['path'];
            },
            $aDTRouteDBModelDBTableRoute
        );

        $sTargetPath = \RouteDB\Model\Route::getPathOnPlaceholderIndex($sPath, $aRouteOnly);

        $mKey = array_search(
        // what to search for
            $sTargetPath,
            // Array to search in & Key to look after
            array_column($aDTRouteDBModelDBTableRoute, 'path')
        );

        if (false === $mKey)
        {
            $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create();
        }
        else
        {
            $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create($aDTRouteDBModelDBTableRoute[$mKey]);
        }

        return $oDTRouteDBModelDBTableRoute;
    }

    /**
     * @param string $sPath
     * @return \MVC\DataType\DTRoute
     * @throws \ReflectionException
     */
    public function getOnPath(string $sPath = '')
    {
        $oDTRouteDBModelDBTableRoute = current($this->retrieve([
            DTDBWhere::create()->set_sKey(DTRouteDBModelDBTableRoute::getPropertyName_path())->set_sValue($sPath),
        ]));
        (false === $oDTRouteDBModelDBTableRoute) ? $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create() : false;

        $oDTRoute = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())
            ->set_methodsAssigned(unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
            ->set_additional(unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
        ;

        return $oDTRoute;
    }

    /**
     * @param string $sQuery
     * @return \RouteDB\DataType\DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public function getOnQuery(string $sQuery = '')
    {
        $oDTRouteDBModelDBTableRoute = current($this->retrieve([
            DTDBWhere::create()->set_sKey(DTRouteDBModelDBTableRoute::getPropertyName_query())->set_sValue($sQuery),
        ]));
        (false === $oDTRouteDBModelDBTableRoute) ? $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create() : false;

        return $oDTRouteDBModelDBTableRoute;
    }

    /**
     * @param string $sTag
     * @return \RouteDB\DataType\DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public function getOnTag(string $sTag = '')
    {
        $oDTRouteDBModelDBTableRoute = current($this->retrieve([
            DTDBWhere::create()->set_sKey(DTRouteDBModelDBTableRoute::getPropertyName_tag())->set_sValue($sTag),
        ]));
        (false === $oDTRouteDBModelDBTableRoute) ? $oDTRouteDBModelDBTableRoute = DTRouteDBModelDBTableRoute::create() : false;

        return $oDTRouteDBModelDBTableRoute;
    }

    /**
     * @param bool $bWildcardsOnly
     * @return array
     * @throws \ReflectionException
     */
    public function getIndices(bool $bWildcardsOnly = false)
    {
        $sSql = "SELECT `path` FROM `" . $this->sTableName . "`";

        if (true === $bWildcardsOnly)
        {
            $sSql .= "\nWHERE 1\nAND `path` LIKE '%*'";
        }

        $aData = $this->fetchAll($sSql);
        $aResult = [];

        foreach ($aData as $aValue)
        {
            $aResult[] = $aValue['path'];
        }

        return $aResult;
    }

    /**
     * @return DTRoute[]
     * @throws \ReflectionException
     */
    public function getTagList() : array
    {
        $aDTRoute = array();

        /** @var DTRouteDBModelDBTableRoute $oDTRouteDBModelDBTableRoute */
        foreach ($this->retrieve() as $oDTRouteDBModelDBTableRoute)
        {
            $aDTRoute[$oDTRouteDBModelDBTableRoute->get_tag()] = DTRoute::create($oDTRouteDBModelDBTableRoute->getPropertyArray())
                ->set_methodsAssigned(unserialize($oDTRouteDBModelDBTableRoute->get_methodsAssigned()))
                ->set_additional(unserialize($oDTRouteDBModelDBTableRoute->get_additional()))
            ;
        }

        /** DTRoute[] $aDTRoute */
        return $aDTRoute;
    }
}