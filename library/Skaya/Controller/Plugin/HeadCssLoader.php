<?php
class Skaya_Controller_Plugin_HeadCssLoader extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();
        if ($request->isXmlHttpRequest()) {
            return;
        }

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $view = $bootstrap->view;
        if (!empty($moduleName) && isset($bootstrap->modules[$moduleName]->view)) {
            $view = $bootstrap->modules[$moduleName]->view;
        }
        if (!$view) {
            return false;
        }

        $options = $bootstrap->getOptions();

        $applicationConfig = (isset($options['plugin']['headcss'])) ? $options['plugin']['headcss'] : array();
        $moduleConfig = (isset($options[$moduleName]['plugin']['headcss'])) ? $options[$moduleName]['plugin']['headcss'] : array();
        $headCssConfig = array_unique(array_merge($applicationConfig, $moduleConfig));

        $helper = $view->getHelper('headLink');
        foreach ($headCssConfig as $key => $value) {
            if (is_string($value)) {
                $helper->appendStylesheet($value);
            }
            if (is_array($value)) {
                $extras = $params = array();
                foreach ($value as $_name => $_v) {
                    switch ($_name) {
                        case "href":
                            $params[0] = (string)$_v;
                            break;
                        case "media":
                            $params[1] = (string)$_v;
                            break;
                        case "condition":
                            $params[2] = (string)$_v;
                            break;
                        case "extras":
                        default:
                            $extras = array_merge($extras, (array)$_v);
                            break;
                    }
                }
                if (!empty($extras)) {
                    $params[3] = (array)$extras;
                }
                $helperMethodName = 'appendStylesheet';
                if (is_string($key)) {
                    array_unshift($params, $key);
                    $helperMethodName = 'offsetSetStylesheet';
                }
                call_user_func_array(array($helper, $helperMethodName), $params);
            }

        }
    }
}

?>
