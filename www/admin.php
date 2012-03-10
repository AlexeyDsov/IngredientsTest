<?php

ini_set('date.timezone', 'Europe/Moscow');
set_time_limit(0);

try {
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

	$serviceLocator = ServiceLocator::create();
	$serviceLocator->set('permissionManager', PermissionManagerSimple::create());
	$authorisator = Authorisator::create()->setUserClassName('IngAdmin');
	
	$application = WebApplication::create()->
		setPathWeb(PATH_WEB)->
		setPathController(PATH_CONTROLLERS_ADMIN)->
		setPathTemplate(PATH_TEMPLATES_ADMIN)->
		setServiceLocator($serviceLocator)->
		add(WebAppBufferHandler::create())->
		add(WebAppSessionHandler::create()->setSessionName('jhgjh'))->
		add(
			WebAppAuthorisatorHandlerHttpDigest::create()->
				addAuthorisator('admin', $authorisator)
		)->
		add(
			WebAppLinkerInjector::create()->
				setLogClassName('IngLog')->
				setBaseUrl(PATH_WEB_URL)
		)->
		add(WebAppAjaxHandler::create())->
		add(
			WebAppControllerMultiResolverHandler::create()->
				addSubPath('service/')->
				addSubPath('objects/')->
				addSubPath('objectsList/')
		)->
		add(
			WebAppControllerHandlerToolkit::create()->
				setAuthorisatorName('admin')->
				setBaseUrl(PATH_WEB_URL)
		)->
		add(WebAppViewHandlerToolkitIng::create()->setAuthorisatorName('admin'));
	$application->run();

} catch (Exception $e) {
	if (__LOCAL_DEBUG__) {
		print "<pre>";
		var_dump(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
		exit;
	} else {
		HeaderUtils::sendHttpStatus(new HttpStatus(HttpStatus::CODE_500));
		exit;
	}
}

?>
