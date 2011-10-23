<?php
// system settings
error_reporting(E_ALL | E_STRICT);

setlocale(LC_CTYPE, "en_US.UTF8");
setlocale(LC_TIME, "en_US.UTF8");

// Xdebug settings @see phpinfo()
ini_set('display_errors', 1);
ini_set('xdebug.show_local_vars', 'on');
ini_set('xdebug.dump_globals', 'on');
ini_set('xdebug.dump.GET', '*');
ini_set('xdebug.collect_params', 'on');
ini_set('xdebug.var_display_max_depth', 8);
ini_set('xdebug.var_display_max_data', 4096);
date_default_timezone_set('Europe/Moscow');

//including project constants
require_once('constants.inc.php');

//Project constants
define('DS', DIRECTORY_SEPARATOR);
define('PATH_BASE', dirname(dirname(__FILE__)).DS);
define('PATH_SRC', PATH_BASE.'src'.DS);
define('PATH_EXTERNALS', PATH_BASE.'externals'.DS);

//SRC PATCHES
define('PATH_CLASSES', PATH_SRC.'classes'.DS);
define('PATH_CONTROLLERS', PATH_SRC.'controllers'.DS);
define('PATH_CONTROLLERS_ADMIN', PATH_SRC.'controllers-admin'.DS);
define('PATH_TEMPLATES', PATH_SRC.'templates'.DS);
define('PATH_TEMPLATES_ADMIN', PATH_SRC.'templates-admin'.DS);

//including project classes
ini_set(
	'include_path',
	get_include_path()
	. PATH_SEPARATOR
	. join(
		PATH_SEPARATOR,
		array(
			PATH_CLASSES,
			PATH_CLASSES.'Auto'.DS.'Business',
			PATH_CLASSES.'Auto'.DS.'Proto',
			PATH_CLASSES.'Auto'.DS.'DAOs',

			PATH_CLASSES.'Business',
			PATH_CLASSES.'DAOs',
			PATH_CLASSES.'Flow',
			PATH_CLASSES.'Proto',
			PATH_CLASSES.'Utils',
		)
	) . PATH_SEPARATOR
);

//including onPHP:
require PATH_ONPHP.'global.inc.php.tpl';

// everything else
define('DEFAULT_ENCODING', 'UTF-8');
mb_internal_encoding(DEFAULT_ENCODING);
mb_regex_encoding(DEFAULT_ENCODING);

//loading AlexeyDsov core:
require PATH_ALEXEYDSOV_CORE . 'include.inc.php';
require 'config.inc.php';


/********** BEGIN RELEASE_CACHE_CONST *********/

define('RELEASE_VERSION', 'v0v1');

//CssUtils::me()->setReleaseVersion(RELEASE_VERSION);
//JsUtils::me()->setReleaseVersion(RELEASE_VERSION);

    /********** END OF RELEASE_CACHE_CONST ********/
