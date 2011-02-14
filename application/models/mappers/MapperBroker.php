<?php
/**
 * @package    Model
 * @subpackage Model_Mapper
 */
class Model_Mapper_MapperBroker
{
    /**
     * @var Zend_Loader_PluginLoader_Interface
     */
    protected static $_pluginLoader;

    /**
     * $_Mappers - Mapper array
     *
     * @var Zend_Controller_Action_MapperBroker_PriorityStack
     */
    protected static $_stack = null;
    
    /**
     * Default mapper provider
     * @var string
     */
    protected static $_defaultProvider;
    
    /**
     * Current mapper provider
     * @var string
     */
    protected $_provider;
    
    /**
	* Set/Get current mapper provider
    * @return string|Model_Mapper_MapperBroker
	*/
	public function provider($provider = null) {
        if (!empty ($provider)) {
            $this->_provider = $provider;
            return $this;
        }
		if (empty($this->_provider)) {
			$this->_provider = self::getDefaultProvider();
		}
		return $this->_provider;
	}
	
    /**
     * Sets default mapper provider
     * @param string $provider 
     */
	public static function setDefaultProvider($provider) {
		self::$_defaultProvider = $provider;
	}
	
    /**
     * Returns default mapper provider
     * @return string
     */
	public static function getDefaultProvider() {
		return self::$_defaultProvider;
	}

    /**
     * Set PluginLoader for use with broker
     *
     * @param  Zend_Loader_PluginLoader_Interface $loader
     * @return void
     */
    public static function setPluginLoader($loader)
    {
        if ((null !== $loader) && (!$loader instanceof Zend_Loader_PluginLoader_Interface)) {
            throw new Model_Exception('Invalid plugin loader provided to MapperBroker');
        }
        self::$_pluginLoader = $loader;
    }

    /**
     * Retrieve PluginLoader
     *
     * @return Zend_Loader_PluginLoader
     */
    public static function getPluginLoader()
    {
        if (null === self::$_pluginLoader) {
            self::$_pluginLoader = new Zend_Loader_PluginLoader(array(
                'Model_Mapper' => APPLICATION_PATH . '/models/mappers',
            ));
        }
        return self::$_pluginLoader;
    }

    /**
     * addPrefix() - Add repository of Mappers by prefix
     *
     * @param string $prefix
     */
    static public function addPrefix($prefix)
    {
        $prefix = rtrim($prefix, '_');
        $path   = str_replace('_', DIRECTORY_SEPARATOR, $prefix);
        self::getPluginLoader()->addPrefixPath($prefix, $path);
    }

    /**
     * addPath() - Add path to repositories where Action_Mappers could be found.
     *
     * @param string $path
     * @param string $prefix Optional; defaults to 'Zend_Controller_Action_Mapper'
     * @return void
     */
    static public function addPath($path, $prefix = 'Model_Mapper')
    {
        self::getPluginLoader()->addPrefixPath($prefix, $path);
    }

    /**
     * addMapper() - Add Mapper objects
     *
     * @param Model_Mapper_Interface $mapper
     * @return void
     */
    static public function addMapper(Model_Mapper_Interface $mapper)
    {
        self::getStack()->push($mapper);
        return;
    }

    /**
     * resetMappers()
     *
     * @return void
     */
    static public function resetMappers()
    {
        self::$_stack = null;
        return;
    }

    /**
     * Retrieve or initialize a Mapper statically
     *
     * Retrieves a Mapper object statically, loading on-demand if the Mapper
     * does not already exist in the stack. Always returns a Mapper, unless
     * the Mapper class cannot be found.
     *
     * @param  string $name
     * @return Model_Mapper_Interface
     */
    public static function getStaticMapper($name)
    {
        $name  = self::_normalizeMapperName($name);
        $stack = self::getStack();

        if (!isset($stack->{$name})) {
            self::_loadMapper($name);
        }

        return $stack->{$name};
    }

    /**
     * getExistingMapper() - get Mapper by name
     *
     * Static method to retrieve Mapper object. Only retrieves Mappers already
     * initialized with the broker (either via addMapper() or on-demand loading
     * via getMapper()).
     *
     * Throws an exception if the referenced Mapper does not exist in the
     * stack; use {@link hasMapper()} to check if the Mapper is registered
     * prior to retrieving it.
     *
     * @param  string $name
     * @return Model_Mapper_Interface
     * @throws Model_Exception
     */
    public static function getExistingMapper($name)
    {
        $name  = self::_normalizeMapperName($name);
        $stack = self::getStack();

        if (!isset($stack->{$name})) {
            throw new Model_Exception('Mapper "' . $name . '" has not been registered with the Mapper broker');
        }

        return $stack->{$name};
    }

    /**
     * Return all registered Mappers as Mapper => object pairs
     *
     * @return array
     */
    public static function getExistingMappers()
    {
        return self::getStack()->getMappersByName();
    }

    /**
     * Is a particular Mapper loaded in the broker?
     *
     * @param  string $name
     * @return boolean
     */
    public static function hasMapper($name)
    {
        $name = self::_normalizeMapperName($name);
        return isset(self::getStack()->{$name});
    }

    /**
     * Remove a particular Mapper from the broker
     *
     * @param  string $name
     * @return boolean
     */
    public static function removeMapper($name)
    {
        $name = self::_normalizeMapperName($name);
        $stack = self::getStack();
        if (isset($stack->{$name})) {
            unset($stack->{$name});
        }

        return false;
    }

    /**
     * Lazy load the priority stack and return it
     *
     * @return Zend_Controller_Action_MapperBroker_PriorityStack
     */
    public static function getStack()
    {
        if (self::$_stack == null) {
            self::$_stack = new Model_MapperBroker_PriorityStack();
        }

        return self::$_stack;
    }

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        foreach (self::getStack() as $mapper) {
            $mapper->init();
        }
    }

    /**
     * getMapper() - get Mapper by name
     *
     * @param  string $name
     * @return Model_Mapper_Interface
     */
    public function getMapper($name)
    {
        $name  = self::_normalizeMapperName($name);
        $stack = self::getStack();

        if (!isset($stack->{$name})) {
            self::_loadMapper($name);
        }

        $mapper = $stack->{$name};

        return $mapper;
    }

    /**
     * Method overloading
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     * @throws Model_Exception if Mapper does not have a direct() method
     */
    public function __call($method, $args)
    {
        $mapper = $this->getMapper($method);
        if (!method_exists($mapper, 'direct')) {
            throw new Model_Exception('Mapper "' . $method . '" does not support overloading via direct()');
        }
        return call_user_func_array(array($mapper, 'direct'), $args);
    }

    /**
     * Retrieve Mapper by name as object property
     *
     * @param  string $name
     * @return Model_Mapper_Interface
     */
    public function __get($name)
    {
        return $this->getMapper($name);
    }

    /**
     * Normalize Mapper name for lookups
     *
     * @param  string $name
     * @return string
     */
    protected static function _normalizeMapperName($name)
    {
        if (strpos($name, '_') !== false) {
            $name = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        }

        return ucfirst($name);
    }

    /**
     * Load a Mapper
     *
     * @param  string $name
     * @return void
     */
    protected static function _loadMapper($name)
    {
        try {
            $class = self::getPluginLoader()->load($name);
        } catch (Zend_Loader_PluginLoader_Exception $e) {
            throw new Model_Exception('Mapper by name ' . $name . ' not found', 0, $e);
        }

        $mapper = new $class();

        if (!$mapper instanceof Model_Mapper_Interface) {
            throw new Model_Exception('Mapper name ' . $name . ' -> class ' . $class . ' is not of type Model_Mapper_Interface');
        }

        self::getStack()->push($mapper);
    }
}
