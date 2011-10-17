<?php
require dirname(dirname(__FILE__)).'/conf/constants.auto.inc.php';
require PATH_BASE.'conf/config.inc.php';
ini_set(
	'include_path',
	get_include_path()
	.PATH_CONTROLLERS.PATH_SEPARATOR
);

define('PATH_WEB_URL', PATH_WEB.'?');
define('PATH_WEB_CSS', PATH_WEB.'css/');
define('PATH_WEB_IMG', PATH_WEB.'img/');
define('PATH_WEB_SCRIPTS', PATH_WEB.'scripts/');

try {
//	CssUtils::me()->
//		setOutputPath(PATH_BASE.'www'.DIRECTORY_SEPARATOR.'merger'.DIRECTORY_SEPARATOR)->
//		setInputPath(PATH_BASE.'www'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR)->
//		setWebPath(str_replace('//www.', '//css.', PATH_WEB.'merger/'))->
//		setWebPathUnprocessed(PATH_WEB_CSS);

//	JsUtils::me()->
//		setOutputPath(PATH_BASE.'www'.DIRECTORY_SEPARATOR.'merger'.DIRECTORY_SEPARATOR)->
//		setInputPath(PATH_BASE.'www'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR)->
//		setWebPath(str_replace('//www.', '//js.', PATH_WEB.'merger/'))->
//		setWebPathUnprocessed(PATH_WEB_SCRIPTS);

	$application = WebApplication::create()->
		setPathWeb(PATH_WEB)->
		setPathController(PATH_CONTROLLERS)->
		setPathTemplate(PATH_TEMPLATES)->
		setServiceLocator(ServiceLocator::create())->
		add(WebAppBufferHandler::create())->
		add(
			WebAppSessionHandler::create()->
				setCookieDomain(COOKIE_HOST_NAME)->
				setSessionName('some_session')
		)->
		add(
			WebAppAuthorisatorHandler::create()->
				addAuthorisator(
					'authorisator',
					Authorisator::create()->setUserClassName('FbUser')
				)
		)->
		add(WebAppControllerResolverHandler::create())->
		add(WebAppControllerHandlerIndex::create())->
		add(WebAppViewHandlerProject::create());
	$application->run();

} catch (Exception $e) {
	var_dump(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
	exit;
}

?>
