<?php
/**
 * Конфиг для списка прав доступа
 */

/**
 * Создание констант
 * Для быстродействия предпочтительнее использовать константы, нежели строковые значения
 */

define('ACL_ROLE_GUEST',		0x00);
define('ACL_ROLE_USER',			0x01);
define('ACL_ROLE_MEMBER',		0x02);
define('ACL_ROLE_MODERATOR',	0x03);
define('ACL_ROLE_MANAGER',		0x04);
define('ACL_ROLE_ADMIN',		0xff);

define('ACL_RESOURCE_AD',			0x01);
define('ACL_RESOURCE_AD_OWNED',		0x02);
define('ACL_RESOURCE_CAPTCHA',		0x03);
define('ACL_RESOURCE_COMPANY',		0x04);
define('ACL_RESOURCE_SEARCH',		0x05);
define('ACL_RESOURCE_STATISTIC',	0x06);

define('ACL_ACTION_ADD',	0x01);
define('ACL_ACTION_CLEAR',	0x02);
define('ACL_ACTION_DELETE',	0x03);
define('ACL_ACTION_DO',		0x04);
define('ACL_ACTION_EDIT',	0x05);
define('ACL_ACTION_SHOW',	0x06);
define('ACL_ACTION_VIEW',	0x07);

/**
 * Массив существующих ролей
 */
$data['roles'] = array(
	ACL_ROLE_GUEST,
	ACL_ROLE_USER,
	ACL_ROLE_MEMBER,
	ACL_ROLE_MODERATOR,
	ACL_ROLE_MANAGER,
	ACL_ROLE_ADMIN,
);

/**
 * Роль с абсолютными правами доступа - root, admin
 */
$data['complete_access_role'] = ACL_ROLE_ADMIN;

/**
 * Иерархия ролей, кто от кого наследует права доступа
 * Допустимо множественное наследование
 * !!! Избегайте наличия циклов в иерархии
 */
$data['hierarchy'] = array(
	ACL_ROLE_USER => array(ACL_ROLE_GUEST),
	ACL_ROLE_MEMBER => array(ACL_ROLE_USER),
	ACL_ROLE_MODERATOR => array(ACL_ROLE_USER),
	ACL_ROLE_MANAGER => array(ACL_ROLE_MODERATOR, ACL_ROLE_MEMBER),
);

/**
 * Ресурсы с перечислением возможных производимых над ними действий
 */
$data['resources'] = array(
	ACL_RESOURCE_AD => array(
		ACL_ACTION_ADD,
		ACL_ACTION_VIEW,
		ACL_ACTION_EDIT,
		ACL_ACTION_DELETE,
	),
	ACL_RESOURCE_AD_OWNED => array(
		ACL_ACTION_EDIT,
		ACL_ACTION_DELETE,
	),
	ACL_RESOURCE_CAPTCHA => array(
		ACL_ACTION_SHOW,
		ACL_ACTION_CLEAR,
	),
	ACL_RESOURCE_COMPANY => array(
		ACL_ACTION_ADD,
		ACL_ACTION_EDIT,
		ACL_ACTION_DELETE,
	),
	ACL_RESOURCE_SEARCH => array(
		ACL_ACTION_DO,
		ACL_ACTION_CLEAR,
	),
	ACL_RESOURCE_STATISTIC => array(
		ACL_ACTION_DO,
	),
);

/**
 * Список разрешения доступа
 * array(
 * 		кто => array(
 * 			над чем => array(какие действия может делать, ...),
 * 		),
 * )
 */
$data['allow'] = array(
	ACL_ROLE_GUEST => array(
		ACL_RESOURCE_AD => array(ACL_ACTION_VIEW),
		ACL_RESOURCE_CAPTCHA => array(ACL_ACTION_SHOW),
		ACL_RESOURCE_SEARCH => array(ACL_ACTION_DO),
		ACL_RESOURCE_STATISTIC => array(ACL_ACTION_DO),
	),
	ACL_ROLE_USER => array(
		ACL_RESOURCE_AD => array(ACL_ACTION_ADD),
		ACL_RESOURCE_AD_OWNED => array(ACL_ACTION_EDIT, ACL_ACTION_DELETE),
	),
	ACL_ROLE_MEMBER => array(
		ACL_RESOURCE_COMPANY => array(ACL_ACTION_EDIT),
	),
	ACL_ROLE_MODERATOR => array(
		ACL_RESOURCE_AD => array(ACL_ACTION_EDIT, ACL_ACTION_DELETE),
		ACL_RESOURCE_CAPTCHA => array(ACL_ACTION_CLEAR),
		ACL_RESOURCE_SEARCH => array(ACL_ACTION_CLEAR),
	),
	ACL_ROLE_MANAGER => array(
		ACL_RESOURCE_COMPANY => array(ACL_ACTION_ADD, ACL_ACTION_DELETE),
	),
);

/**
 * Список запрещения доступа
 * array(
 * 		кто => array(
 * 			над чем => array(какие действия НЕ может делать, ...),
 * 		),
 * )
 */
$data['deny'] = array(
	ACL_ROLE_MANAGER => array(
		ACL_RESOURCE_AD => array(ACL_ACTION_EDIT, ACL_ACTION_DELETE),
	),
);

return $data;