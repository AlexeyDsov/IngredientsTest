<?php
require dirname(dirname(__FILE__)).'/conf/config.auto.inc.php';
ini_set(
	'include_path',
	get_include_path()
	.join(
		PATH_SEPARATOR,
		array(
			PATH_CONTROLLERS_ADMIN,
			PATH_CONTROLLERS_ADMIN . 'service/',
			PATH_CONTROLLERS_ADMIN . 'objects/',
			PATH_CONTROLLERS_ADMIN . 'objectsList/',
		)
	)
	.PATH_SEPARATOR
);

define('PATH_WEB_URL', PATH_WEB.'admin.php?');
define('PATH_WEB_CSS', PATH_WEB.'css/');
define('PATH_WEB_IMG', PATH_WEB.'img/');
define('PATH_WEB_JS', PATH_WEB.'js/');

try {
	$serviceLocator = ServiceLocator::create();
	$serviceLocator->set('permissionManager', ToolkitPermissionManager::create());
	$authorisator = Authorisator::create()->setUserClassName('IngAdmin');
	
	$application = WebApplication::create()->
		setPathWeb(PATH_WEB)->
		setPathController(PATH_CONTROLLERS_ADMIN)->
		setPathTemplate(PATH_TEMPLATES_ADMIN)->
		setServiceLocator($serviceLocator)->
		add(WebAppBufferHandler::create())->
		add(
			WebAppSessionHandler::create()->
				setCookieDomain(COOKIE_HOST_NAME)->
				setSessionName('some_session')
		)->
		add(WebAppAjaxHandler::create())->
		add(
			WebAppAuthorisatorHandler::create()->
				addAuthorisator('admin', $authorisator)
		)->
		add(WebAppLinkerInjector::create()->setLogClassName('IngLog'))->
		add(
			WebAppControllerMultiResolverHandler::create()->
				addSubPath('service/')->
				addSubPath('objects/')->
				addSubPath('objectsList/')
		)->
		add(WebAppControllerHandlerAdmin::create())->
		add(WebAppViewHandlerProject::create());
	$application->run();

} catch (Exception $e) {
	print "<pre>";
	var_dump(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
	exit;
}

?>
