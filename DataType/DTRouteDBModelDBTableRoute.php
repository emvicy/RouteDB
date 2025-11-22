<?php

/**
 * @name $RouteDBDataType
 */
namespace RouteDB\DataType;

use MVC\DataType\DTValue;
use MVC\MVCTrait\TraitDataType;

class DTRouteDBModelDBTableRoute extends \MVC\DB\DataType\DB\TableDataType
{
	use TraitDataType;

	public const DTHASH = '218265ca92c33355c59ffbac28eef5eb';

	/**
	 * @required true
	 * @var string
	 */
	protected $uuid;

	/**
	 * @required true
	 * @var string
	 */
	protected $path;

	/**
	 * @required true
	 * @var int
	 */
	protected $iSlashes;

	/**
	 * @required true
	 * @var string
	 */
	protected $requestMethod;

	/**
	 * @required true
	 * @var string
	 */
	protected $methodsAssigned;

	/**
	 * @required true
	 * @var string
	 */
	protected $query;

	/**
	 * @required true
	 * @var string
	 */
	protected $module;

	/**
	 * @required true
	 * @var string
	 */
	protected $class;

	/**
	 * @required true
	 * @var string
	 */
	protected $method;

	/**
	 * @required true
	 * @var string
	 */
	protected $classFile;

	/**
	 * @required true
	 * @var string|null
	 */
	protected $additional;

	/**
	 * @required true
	 * @var string|null
	 */
	protected $tag;

	/**
	 * @required true
	 * @var string
	 */
	protected $description;

	/**
	 * DTRouteDBModelDBTableRoute constructor.
	 * @param DTValue $oDTValue
	 * @throws \ReflectionException 
	 */
	protected function __construct(DTValue $oDTValue)
	{
		\MVC\Event::run('DTRouteDBModelDBTableRoute.__construct.before', $oDTValue);
		$aData = $oDTValue->get_mValue();
		$this->uuid = '';
		$this->path = '';
		$this->iSlashes = 0;
		$this->requestMethod = '';
		$this->methodsAssigned = '';
		$this->query = '';
		$this->module = '';
		$this->class = '';
		$this->method = '';
		$this->classFile = '';
		$this->additional = null;
		$this->tag = null;
		$this->description = '';

		parent::__construct($oDTValue);
		$this->setProperties($oDTValue);

		$oDTValue = DTValue::create()->set_mValue($aData); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.__construct.after', $oDTValue);
	}

    /**
     * @param array|null $aData
     * @return DTRouteDBModelDBTableRoute
     * @throws \ReflectionException
     */
    public static function create(?array $aData = array())
    {            
        (null === $aData) ? $aData = array() : false;
        $oDTValue = DTValue::create()->set_mValue($aData);
		\MVC\Event::run('DTRouteDBModelDBTableRoute.create.before', $oDTValue);
		$oObject = new self($oDTValue);
        $oDTValue = DTValue::create()->set_mValue($oObject); \MVC\Event::run('DTRouteDBModelDBTableRoute.create.after', $oDTValue);

        return $oDTValue->get_mValue();
    }

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_uuid(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_uuid.before', $oDTValue);
		$this->uuid = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_path(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_path.before', $oDTValue);
		$this->path = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param int $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_iSlashes(int $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_iSlashes.before', $oDTValue);
		$this->iSlashes = (int) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_requestMethod(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_requestMethod.before', $oDTValue);
		$this->requestMethod = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_methodsAssigned(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_methodsAssigned.before', $oDTValue);
		$this->methodsAssigned = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_query(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_query.before', $oDTValue);
		$this->query = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_module(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_module.before', $oDTValue);
		$this->module = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_class(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_class.before', $oDTValue);
		$this->class = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_method(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_method.before', $oDTValue);
		$this->method = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_classFile(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_classFile.before', $oDTValue);
		$this->classFile = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string|null $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_additional(?string $mValue = null)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_additional.before', $oDTValue);
		$this->additional = $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string|null $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_tag(?string $mValue = null)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_tag.before', $oDTValue);
		$this->tag = $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @param string $mValue 
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function set_description(string $mValue)
	{
		$oDTValue = DTValue::create()->set_mValue($mValue); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.set_description.before', $oDTValue);
		$this->description = (string) $oDTValue->get_mValue();

		return $this;
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_uuid() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->uuid); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_uuid.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_path() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->path); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_path.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return int
	 * @throws \ReflectionException
	 */
	public function get_iSlashes() : int
	{
		$oDTValue = DTValue::create()->set_mValue($this->iSlashes); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_iSlashes.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_requestMethod() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->requestMethod); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_requestMethod.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_methodsAssigned() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->methodsAssigned); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_methodsAssigned.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_query() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->query); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_query.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_module() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->module); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_module.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_class() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->class); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_class.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_method() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->method); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_method.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_classFile() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->classFile); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_classFile.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string|null
	 * @throws \ReflectionException
	 */
	public function get_additional() : ?string
	{
		$oDTValue = DTValue::create()->set_mValue($this->additional); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_additional.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string|null
	 * @throws \ReflectionException
	 */
	public function get_tag() : ?string
	{
		$oDTValue = DTValue::create()->set_mValue($this->tag); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_tag.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function get_description() : string
	{
		$oDTValue = DTValue::create()->set_mValue($this->description); 
		\MVC\Event::run('DTRouteDBModelDBTableRoute.get_description.before', $oDTValue);

		return $oDTValue->get_mValue();
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_uuid()
	{
        return 'uuid';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_path()
	{
        return 'path';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_iSlashes()
	{
        return 'iSlashes';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_requestMethod()
	{
        return 'requestMethod';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_methodsAssigned()
	{
        return 'methodsAssigned';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_query()
	{
        return 'query';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_module()
	{
        return 'module';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_class()
	{
        return 'class';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_method()
	{
        return 'method';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_classFile()
	{
        return 'classFile';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_additional()
	{
        return 'additional';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_tag()
	{
        return 'tag';
	}

	/**
	 * @return string
	 */
	public static function getPropertyName_description()
	{
        return 'description';
	}

	/**
	 * @return false|string JSON
	 */
	public function __toString()
	{
        return $this->getPropertyJson();
	}

	/**
	 * @return false|string
	 */
	public function getPropertyJson()
	{
        return json_encode(\MVC\Convert::objectToArray($this));
	}

	/**
	 * @return array
	 */
	public function getPropertyArray()
	{
        return get_object_vars($this);
	}

	/**
	 * @return array
	 * @throws \ReflectionException
	 */
	public function getConstantArray()
	{
		$oReflectionClass = new \ReflectionClass($this);
		$aConstant = $oReflectionClass->getConstants();

		return $aConstant;
	}

	/**
	 * @return $this
	 */
	public function flushProperties()
	{
		foreach ($this->getPropertyArray() as $sKey => $mValue)
		{
			$sMethod = 'set_' . $sKey;

			if (method_exists($this, $sMethod)) 
			{
				$this->$sMethod('');
			}
		}

		return $this;
	}

}
