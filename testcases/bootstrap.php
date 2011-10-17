<?php
require dirname(dirname(__FILE__)).'/conf/constants.auto.inc.php';
require PATH_BASE.'conf/config.inc.php';

ini_set(
	'include_path',
	get_include_path()

	.PATH_CORE . 'tests' . PATH_SEPARATOR
	.PATH_BASE . 'testcases/utils' . PATH_SEPARATOR
);


define('PATH_WEB_URL', PATH_WEB.'?');
define('PATH_WEB_CSS', PATH_WEB.'css/');
define('PATH_WEB_IMG', PATH_WEB.'img/');
define('PATH_WEB_SCRIPTS', PATH_WEB.'scripts/');

?>
