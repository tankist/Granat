<?php
class Skaya_Controller_Router_Route_Chain extends Zend_Controller_Router_Route_Chain {

	/**
	 * Instantiates route based on passed Zend_Config structure
	 * @param Zend_Config $config Configuration object
	 * @return Skaya_Controller_Router_Route_Chain
	 */
	public static function getInstance(Zend_Config $config) {
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
		return new self($config->route, $defs);
	}

	public function match($request, $partial = null) {
		$temporaryRequest = clone $request;
		$match = $this->_parentMatch($temporaryRequest, $partial);
		if (!$match) {
			return false;
		}
		$request->setPathInfo($temporaryRequest->getPathInfo());
		return $match;
	}

	protected function _parentMatch($request, $partial = null) {
		$path = trim($request->getPathInfo(), '/');
		$subPath = $path;
		$values = array();
		$matchedPath = $separator = null;

		foreach ($this->_routes as $key => $route) {
			if ($key > 0
			    && $matchedPath !== null
			    && $subPath !== ''
			    && $subPath !== false
			) {
				$separator = substr($subPath, 0, strlen($this->_separators[$key]));

				if ($separator !== $this->_separators[$key]) {
					return false;
				}

				$subPath = substr($subPath, strlen($separator));
			}

			// TODO: Should be an interface method. Hack for 1.0 BC
			if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
				$match = $subPath;
			} else {
				$request->setPathInfo($subPath);
				$match = $request;
			}

			$res = $route->match($match, true);
			if ($res === false) {
				return false;
			}

			$matchedPath = $route->getMatchedPath();

			$this->setMatchedPath(trim(
				                      (string)$this->getMatchedPath() .
				                      (string)$separator .
				                      (string)$matchedPath
			                      ), '/');

			if ($matchedPath !== null) {
				$subPath = substr($subPath, strlen($matchedPath));
				$separator = substr($subPath, 0, strlen($this->_separators[$key]));
			}

			$values = $res + $values;
		}

		$request->setPathInfo($path);

		if ($subPath !== '' && $subPath !== false) {
			return false;
		}

		return $values;
	}

}
