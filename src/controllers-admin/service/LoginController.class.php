<?php
/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	class LoginController extends ToolkitBaseController {

		protected $methodMap = array(
			'getAuthData' => 'getAuthDataProcess',
			'unlogin' => 'unloginProcess',
		);
		protected $defaultAction = 'getAuthData';
		protected $actionName = 'action';

		/**
		 * @return ModelAndView
		**/
		public function handleRequest(HttpRequest $request)
		{
			return $this->resolveAction($request);
		}

		protected function getAuthDataProcess(HttpRequest $request) {
			if (!$this->getLoginHelper()->authRequest()) {
				HeaderUtils::sendHttpStatus(new HttpStatus(HttpStatus::CODE_401));
				return $this->getMav('required.login');
			} else {
				return $this->getMavRedirectByUrl(PATH_WEB_URL);
			}
		}

		protected function unloginProcess(HttpRequest $request) {
			$this->getLoginHelper()->unlogin();
			$adminAuth = $this->serviceLocator->get('admin');
			header('WWW-Authenticate: Basic realm="Logout"');
			HeaderUtils::sendHttpStatus(new HttpStatus(HttpStatus::CODE_401));
			
			return $this->getMav('required.login');
		}
		
		/**
		 * @return LoginHelperDigest
		 */
		private function getLoginHelper() {
			$loginHelper = $this->serviceLocator->spawn('LoginHelperDigest');
			/* @var $loginHelper LoginHelperDigest */
			$loginHelper->setAuthorisator($this->serviceLocator->get('admin'));
			$loginHelper->setSession($this->serviceLocator->get('session'));
			return $loginHelper;
		}
	}
?>