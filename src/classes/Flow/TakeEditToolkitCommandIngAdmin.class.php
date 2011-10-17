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

	/**
	 * Комманда для редактирования объектов через toolkit со списком разрешенных для редактирования полей
	 */
	class TakeEditToolkitCommandIngAdmin extends TakeEditToolkitCommandByFields {

		/**
		 * Базовая настройка формы - копирует в прототип нередактируемые свойства
		 * @return TakeEditTemplateCommand
		 */
		protected function prepairForm(Prototyped $subject, Form $form, HttpRequest $request)
		{
			parent::prepairForm($subject, $form, $request);
			
			$form->
				add(Primitive::string('passwordNew')->setMin(6)->setMax(20)->required())->
				addRule('passwordConvert', CallbackLogicalObject::create(function(Form $form) {
					if ($newPassword = $form->getValue('passwordNew')) {
						$form->importValue(
							'password',
							IngAdmin::create()->
								storePassword($newPassword)->
								getPassword()
						);
						return true;
					}
					return false;
				}));

			return $this;
		}
	}