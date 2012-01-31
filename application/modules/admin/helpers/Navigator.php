<?php

/**
 * @class Admin_Helper_Navigator
 */
class Admin_Helper_Navigator extends Sch_Controller_Action_Helper_AbstractNavigation
{

    /**
     * @return Zend_Navigation_Container
     */
    public function getNavigation()
    {
        if (!$this->_navigation) {
            $this->_navigation = new Zend_Navigation(array(

                    array(
                        'type' => 'mvc',
                        'controller' => 'collections',
                        'label' => 'Collections',
                        'route' => 'admin-default-pages',
                        'pages' => array(

                            array(
                                'label' => 'Add collection',
                                'type' => 'mvc',
                                'controller' => 'collections',
                                'action' => 'add',
                                'route' => 'admin-default',
                                'visible' => false
                            ),

                            array(
                                'label' => 'Edit collection',
                                'type' => 'mvc',
                                'controller' => 'collections',
                                'action' => 'edit',
                                'route' => 'admin-editDelete',
                                'visible' => false
                            ),

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'controller' => 'categories',
                        'label' => 'Categories',
                        'route' => 'admin-default-pages',
                        'pages' => array(

                            array(
                                'label' => 'Add category',
                                'type' => 'mvc',
                                'controller' => 'categories',
                                'action' => 'add',
                                'route' => 'admin-default',
                                'visible' => false
                            ),

                            array(
                                'label' => 'Edit category',
                                'type' => 'mvc',
                                'controller' => 'categories',
                                'action' => 'edit',
                                'route' => 'admin-editDelete',
                                'visible' => false
                            ),

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'controller' => 'models',
                        'label' => 'Models',
                        'route' => 'admin-default-pages',
                        'pages' => array(

                            array(
                                'label' => 'Add model',
                                'type' => 'mvc',
                                'controller' => 'models',
                                'action' => 'add',
                                'route' => 'admin-default',
                                'visible' => false
                            ),

                            array(
                                'label' => 'Edit model',
                                'type' => 'mvc',
                                'controller' => 'models',
                                'action' => 'edit',
                                'route' => 'admin-editDelete',
                                'visible' => false
                            ),

                        )
                    ),
                )
            );
        }
        return $this->_navigation;
    }

    /**
     * @return Zend_Navigation_Container
     */
    public function direct()
    {
        $navigation = parent::direct();
        Zend_Layout::getMvcInstance()->assign(array('navigation' => $navigation));
        return $navigation;
    }

}
