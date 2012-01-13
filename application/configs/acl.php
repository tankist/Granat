<?php

$acl = new Zend_Acl();

// Роли

$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::GUEST));
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::USER), Sch_Acl_Roles::GUEST);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::ADMIN), Sch_Acl_Roles::USER);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::DEVELOPER), Sch_Acl_Roles::ADMIN);

/**
 * Базовые ресурсы
 */
$acl->addResource(new Zend_Acl_Resource('index'));
$acl->addResource(new Zend_Acl_Resource('user'));
$acl->addResource(new Zend_Acl_Resource('auth'), 'index');
$acl->addResource(new Zend_Acl_Resource('error'), 'index');
$acl->addResource(new Zend_Acl_Resource('manager'));
$acl->addResource(new Zend_Acl_Resource('developer'));

// Ресурсы
// формат ресурса: <имя модуля>.<имя контроллера>
$acl->addResource(new Zend_Acl_Resource('users.edit'), 'user');
$acl->addResource(new Zend_Acl_Resource('users.avatar'), 'users.edit');
$acl->addResource(new Zend_Acl_Resource('users.messages'), 'user');
$acl->addResource(new Zend_Acl_Resource('users.blog'), 'index');
$acl->addResource(new Zend_Acl_Resource('default.ajax'), 'index');
$acl->addResource(new Zend_Acl_Resource('shop.ajax'), 'index');
$acl->addResource(new Zend_Acl_Resource('shop.opinions'), 'index');
$acl->addResource(new Zend_Acl_Resource('shop.catalog'), 'index');
$acl->addResource(new Zend_Acl_Resource('shop.address'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.brands'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.colors'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.materials'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.seasons'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.styles'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.tags'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.targets'), 'manager');
$acl->addResource(new Zend_Acl_Resource('products.types'), 'manager');
$acl->addResource(new Zend_Acl_Resource('blog.index'), 'user');
$acl->addResource(new Zend_Acl_Resource('blog.sections'), 'manager');
$acl->addResource(new Zend_Acl_Resource('pages.sections'), 'manager');
$acl->addResource(new Zend_Acl_Resource('users.metro'), 'manager');
$acl->addResource(new Zend_Acl_Resource('users.cities'), 'manager');
$acl->addResource(new Zend_Acl_Resource('users.suburbs'), 'manager');
$acl->addResource(new Zend_Acl_Resource('users.styles'), 'manager');

// looks
$acl->addResource(new Zend_Acl_Resource('looks.index'), 'index');

// Права доступа

$acl->deny(Sch_Acl_Roles::GUEST, null);
$acl->allow(Sch_Acl_Roles::GUEST, 'index');
$acl->allow(Sch_Acl_Roles::GUEST, 'blog.index', 'index');
$acl->allow(Sch_Acl_Roles::GUEST, 'blog.index', 'view');

$acl->deny(Sch_Acl_Roles::GUEST, 'shop.opinions', 'add');
$acl->deny(Sch_Acl_Roles::GUEST, 'shop.opinions', 'vote');
$acl->allow(Sch_Acl_Roles::USER, 'shop.opinions', 'add');
$acl->allow(Sch_Acl_Roles::USER, 'shop.opinions', 'vote');

$acl->allow(Sch_Acl_Roles::USER, 'user');

$acl->deny(null, 'manager');
$acl->allow(Sch_Acl_Roles::ADMIN, 'manager');

$acl->allow(Sch_Acl_Roles::DEVELOPER, 'developer');

// looks
$acl->deny(Sch_Acl_Roles::GUEST, 'looks.index', 'like');
$acl->allow(Sch_Acl_Roles::USER, 'looks.index', 'like');

$acl->allow(Sch_Acl_Roles::GUEST, 'shop.address', 'index');
