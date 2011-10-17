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
			'authorisation' => 'authorisationProcess',
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

		protected function getAuthDataProcess(HttpRequest $request)
		{
			$this->model->set('form', $this->getAuthorisationForm());

			return $this->getMav();
		}

		protected function authorisationProcess(HttpRequest $request)
		{
			$form = $this->getAuthorisationForm()->import($request->getPost());
			$this->model->set('form', $form);

			if ($form->getErrors()) {
				$form->get('password')->clean();
				return $this->getMav();
			}

			$user = IngAdmin::dao()->getByEmailAndPassword($form->getValue('email'), $form->getValue('password'));
			if (!$user) {
				$form->get('password')->clean();
				$form->
					addCustomLabel('email', 3, 'Пользователя с таким email\'ом и паролем не существует')->
					markCustom('email', 3);
				return $this->getMav();
			}

			$authorisator = $this->serviceLocator->get('admin');
			$authorisator->setUser($user);

			return $this->getMavRedirectByUrl(PATH_WEB_URL);
		}

		protected function unloginProcess(HttpRequest $request)
		{
			$authorisator = $this->serviceLocator->get('admin');
			$authorisator->dropUser();

			return $this->getMavRedirectByUrl(PATH_WEB_URL);
		}

		protected function getAuthorisationForm()
		{
			return Form::create()->
				add(
					Primitive::string('email')->
						setMax(128)->
						setAllowedPattern(PrimitiveString::MAIL_PATTERN)->
						required()
				)->
				addWrongLabel('email', 'неверный email')->
				addMissingLabel('email', 'поле не заполнено')->
				add(
					Primitive::string('password')->
						setMin(6)->
						setMax(10)->
						setAllowedPattern('~[\w]+~iu')->
						required()
				)->
				addWrongLabel('password', 'неверный пароль (латинские буквы, арабские цифры от 6 до 10)')->
				addMissingLabel('password', 'поле не заполнено');
		}
	}
?>