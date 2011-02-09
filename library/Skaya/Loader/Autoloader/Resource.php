<?php
class Skaya_Loader_Autoloader_Resource extends Zend_Loader_Autoloader_Resource {
	
	const NS_SEPARATOR = '\\';
	
	/**
	 * Constructor
	 *
	 * @param  array|Zend_Config $options Configuration options for resource autoloader
	 * @return void
	 */
	public function __construct($options)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			require_once 'Zend/Loader/Exception.php';
			throw new Zend_Loader_Exception('Options must be passed to resource loader constructor');
		}

		$this->setOptions($options);

		$namespace = $this->getNamespace();
		if ((null === $namespace)
			|| (null === $this->getBasePath())
		) {
			require_once 'Zend/Loader/Exception.php';
			throw new Zend_Loader_Exception('Resource loader requires both a namespace and a base path for initialization');
		}

		if (!empty($namespace)) {
			$namespace .= self::NS_SEPARATOR;
		}
		Zend_Loader_Autoloader::getInstance()->unshiftAutoloader($this, $namespace);
	}
	
	/**
	 * Helper method to calculate the correct class path
	 *
	 * @param string $class
	 * @return False if not matched other wise the correct path
	 */
	public function getClassPath($class)
	{
		if (strpos($class, self::NS_SEPARATOR) === false) {
			return false;
		}
		$segments          = explode(self::NS_SEPARATOR, $class);
		$namespaceTopLevel = $this->getNamespace();
		$namespace         = '';

		if (!empty($namespaceTopLevel)) {
			$namespace = array_shift($segments);
			if ($namespace != $namespaceTopLevel) {
				// wrong prefix? we're done
				return false;
			}
		}

		if (count($segments) < 2) {
			// assumes all resources have a component and class name, minimum
			return false;
		}

		$final     = array_pop($segments);
		$component = $namespace;
		$lastMatch = false;
		do {
			$segment    = array_shift($segments);
			$component .= empty($component) ? $segment : self::NS_SEPARATOR . $segment;
			if (isset($this->_components[$component])) {
				$lastMatch = $component;
			}
		} while (count($segments));

		if (!$lastMatch) {
			return false;
		}

		$final = substr($class, strlen($lastMatch) + 1);
		$path = $this->_components[$lastMatch];
		$classPath = $path . '/' . str_replace(self::NS_SEPARATOR, '/', $final) . '.php';

		if (Zend_Loader::isReadable($classPath)) {
			return $classPath;
		}

		return false;
	}
	
	/**
	 * Set namespace that this autoloader handles
	 *
	 * @param  string $namespace
	 * @return Zend_Loader_Autoloader_Resource
	 */
	public function setNamespace($namespace)
	{
		$this->_namespace = rtrim((string) $namespace, self::NS_SEPARATOR);
		return $this;
	}
	
	/**
	 * Add resource type
	 *
	 * @param  string $type identifier for the resource type being loaded
	 * @param  string $path path relative to resource base path containing the resource types
	 * @param  null|string $namespace sub-component namespace to append to base namespace that qualifies this resource type
	 * @return Zend_Loader_Autoloader_Resource
	 */
	public function addResourceType($type, $path, $namespace = null)
	{
		$type = strtolower($type);
		if (!isset($this->_resourceTypes[$type])) {
			if (null === $namespace) {
				require_once 'Zend/Loader/Exception.php';
				throw new Zend_Loader_Exception('Initial definition of a resource type must include a namespace');
			}
			$namespaceTopLevel = $this->getNamespace();
			$namespace = ucfirst(trim($namespace, self::NS_SEPARATOR));
			$this->_resourceTypes[$type] = array(
				'namespace' => empty($namespaceTopLevel) ? $namespace : $namespaceTopLevel . self::NS_SEPARATOR . $namespace,
			);
		}
		if (!is_string($path)) {
			require_once 'Zend/Loader/Exception.php';
			throw new Zend_Loader_Exception('Invalid path specification provided; must be string');
		}
		$this->_resourceTypes[$type]['path'] = $this->getBasePath() . '/' . rtrim($path, '\/');

		$component = $this->_resourceTypes[$type]['namespace'];
		$this->_components[$component] = $this->_resourceTypes[$type]['path'];
		return $this;
	}
	
	/**
	 * Object registry and factory
	 *
	 * Loads the requested resource of type $type (or uses the default resource
	 * type if none provided). If the resource has been loaded previously,
	 * returns the previous instance; otherwise, instantiates it.
	 *
	 * @param  string $resource
	 * @param  string $type
	 * @return object
	 * @throws Zend_Loader_Exception if resource type not specified or invalid
	 */
	public function load($resource, $type = null)
	{
		if (null === $type) {
			$type = $this->getDefaultResourceType();
			if (empty($type)) {
				require_once 'Zend/Loader/Exception.php';
				throw new Zend_Loader_Exception('No resource type specified');
			}
		}
		if (!$this->hasResourceType($type)) {
			require_once 'Zend/Loader/Exception.php';
			throw new Zend_Loader_Exception('Invalid resource type specified');
		}
		$namespace = $this->_resourceTypes[$type]['namespace'];
		$class     = $namespace . self::NS_SEPARATOR . ucfirst($resource);
		if (!isset($this->_resources[$class])) {
			$this->_resources[$class] = new $class;
		}
		return $this->_resources[$class];
	}
	
}
?>
