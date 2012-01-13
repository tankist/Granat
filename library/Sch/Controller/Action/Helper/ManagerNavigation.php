<?php

class Sch_Controller_Action_Helper_ManagerNavigation extends Sch_Controller_Action_Helper_AbstractNavigation
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
                        'module' => 'users',
                        'label' => 'Пользователи',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'label' => 'Пользователи',
                                'type' => 'mvc',
                                'module' => 'users',
                                'route' => 'manager',
                            ),

                            array(
                                'label' => 'Создать',
                                'type' => 'mvc',
                                'module' => 'users',
                                'controller' => 'manager',
                                'action' => 'add',
                                'route' => 'manager',
                                'visible' => false
                            ),

                            array(
                                'label' => 'Города',
                                'type' => 'mvc',
                                'module' => 'users',
                                'controller' => 'cities',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'users',
                                        'controller' => 'cities',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                            array(
                                'label' => 'Станции метро',
                                'type' => 'mvc',
                                'module' => 'users',
                                'controller' => 'metro',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'users',
                                        'controller' => 'metro',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                            array(
                                'label' => 'Районы',
                                'type' => 'mvc',
                                'module' => 'users',
                                'controller' => 'suburbs',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'users',
                                        'controller' => 'suburbs',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            )

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'module' => 'shop',
                        'label' => 'Магазины',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'type' => 'mvc',
                                'module' => 'shop',
                                'label' => 'Магазины',
                                'route' => 'manager',
                            ),

                            array(
                                'label' => 'Создать',
                                'type' => 'mvc',
                                'module' => 'shop',
                                'action' => 'add',
                                'route' => 'manager',
                                'visible' => false
                            )

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'module' => 'products',
                        'label' => 'Товары',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'type' => 'mvc',
                                'module' => 'products',
                                'controller' => 'brands',
                                'label' => 'Бренды',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'products',
                                        'controller' => 'brands',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                            /*array(
                                'type' => 'mvc',
                                'module' => 'products',
                                'controller' => 'colors',
                                'label' => 'Цвета',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'products',
                                        'controller' => 'colors',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),*/

                            array(
                                'type' => 'mvc',
                                'module' => 'products',
                                'controller' => 'styles',
                                'label' => 'Стили',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'products',
                                        'controller' => 'styles',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'products',
                                'controller' => 'tags',
                                'label' => 'Теги',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'products',
                                        'controller' => 'tags',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'products',
                                'controller' => 'types',
                                'label' => 'Типы',
                                'route' => 'managerController',
                                'pages' => array(

                                    array(
                                        'label' => 'Создать',
                                        'type' => 'mvc',
                                        'module' => 'products',
                                        'controller' => 'types',
                                        'action' => 'add',
                                        'route' => 'managerController',
                                        'visible' => false
                                    )

                                )
                            ),

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'module' => 'blog',
                        'label' => 'Блоги',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'type' => 'mvc',
                                'module' => 'blog',
                                'label' => 'Блоги',
                                'route' => 'blogManager'
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'blog',
                                'action' => 'add',
                                'label' => 'Добавить запись',
                                'route' => 'manager',
                                'visible' => false
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'blog',
                                'label' => 'Показать удаленные посты',
                                'route' => 'managerController',
                                'params' => array('deleted' => 1)
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'blog',
                                'label' => 'Показать скрытые посты',
                                'route' => 'managerController',
                                'params' => array('hidden' => 1)
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'blog',
                                'controller' => 'sections',
                                'label' => 'Разделы',
                                'route' => 'blogManager',
                                'pages' => array(

                                    array(
                                        'type' => 'mvc',
                                        'module' => 'blog',
                                        'controller' => 'sections',
                                        'action' => 'add',
                                        'label' => 'Добавить раздел',
                                        'route' => 'blogManager',
                                        'visible' => false
                                    )

                                )
                            )

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'module' => 'pages',
                        'label' => 'Типовые страницы',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'type' => 'mvc',
                                'module' => 'pages',
                                'label' => 'Типовые страницы',
                                'route' => 'manager'
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'pages',
                                'action' => 'add',
                                'label' => 'Добавить страницу',
                                'route' => 'manager',
                                'visible' => false
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'pages',
                                'action' => 'sections',
                                'label' => 'Разделы',
                                'route' => 'manager',
                                'pages' => array(

                                    array(
                                        'type' => 'mvc',
                                        'module' => 'pages',
                                        'action' => 'add-section',
                                        'label' => 'Добавить раздел',
                                        'route' => 'manager',
                                        'visible' => false
                                    )

                                )
                            )

                        )
                    ),

                    array(
                        'type' => 'mvc',
                        'module' => 'feedback',
                        'label' => 'Обратная связь',
                        'route' => 'manager',
                        'pages' => array(

                            array(
                                'type' => 'mvc',
                                'module' => 'feedback',
                                'label' => 'Обратная связь',
                                'route' => 'manager'
                            ),

                            array(
                                'type' => 'mvc',
                                'module' => 'feedback',
                                'action' => 'subjects',
                                'label' => 'Темы вопросов',
                                'route' => 'manager',
                                'pages' => array(

                                    array(
                                        'type' => 'mvc',
                                        'module' => 'feedback',
                                        'action' => 'subject-add',
                                        'label' => 'Добавить тему',
                                        'route' => 'manager',
                                        'visible' => false
                                    )

                                )
                            )

                        )
                    )
                )
            );
        }
        return $this->_navigation;
    }

    public function direct()
    {
        $navigation = parent::direct();
        Zend_Layout::getMvcInstance()->assign(array('navigation' => $navigation));
        return $navigation;
    }
}
