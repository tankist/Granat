<?php

$acl = new Zend_Acl();

// Роли

$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::GUEST));
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::USER), Sch_Acl_Roles::GUEST);
$acl->addRole(new Zend_Acl_Role(Sch_Acl_Roles::ADMIN), Sch_Acl_Roles::USER);

/**
 * Базовые ресурсы
 */
$acl->addResource(new Zend_Acl_Resource('index'));
$acl->addResource(new Zend_Acl_Resource('user'));
$acl->addResource(new Zend_Acl_Resource('auth'), 'index');
$acl->addResource(new Zend_Acl_Resource('error'), 'index');
$acl->addResource(new Zend_Acl_Resource('admin'));

// Ресурсы
// формат ресурса: <имя модуля>.<имя контроллера>
$acl->addResource(new Zend_Acl_Resource('admin.index'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.categories'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.fabrics'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.collections'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.models'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.modelimage'), 'admin');
$acl->addResource(new Zend_Acl_Resource('admin.users'), 'admin');

// Права доступа

$acl->deny(Sch_Acl_Roles::GUEST, null);
$acl->allow(Sch_Acl_Roles::GUEST, 'index');

$acl->deny(null, 'admin');
$acl->allow(Sch_Acl_Roles::ADMIN, 'admin');

// Login/Logout
$acl->allow(Sch_Acl_Roles::GUEST, 'admin.users', 'login');
$acl->allow(Sch_Acl_Roles::GUEST, 'admin.users', 'logout');
