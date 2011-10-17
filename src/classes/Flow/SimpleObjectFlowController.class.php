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
	 * Класс для отображения данных об объекте и редактировании их
	 */
	abstract class SimpleObjectFlowController extends ToolkitBaseController {

		/**
		 * Список методов, реализуемых контроллером
		 * @var array
		 */
		protected $methodMap = array(
			'info' => 'infoProcess',
			'edit' => 'editProcess',
			'take' => 'takeProcess',
		);
		protected $defaultAction = 'info';

		/**
		 * Определяет, какое действие должен выполнить контроллер, вызывает его и возвращает результат
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		public function handleRequest(HttpRequest $request)
		{
			return $this->resolveAction($request);
		}

		/**
		 * Возвращает модель для отображения информации об объекте
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		protected function infoProcess(HttpRequest $request)
		{
			$className = $this->getObjectName();
			if (!$this->serviceLocator->get('linker')->isObjectSupported($className, 'info')) {
				throw new PermissionException('No permission for info '.$className);
			}

			$proto = $this->getObjectProto();

			$form = Form::create();
			$proto->getPropertyByName('id')->fillForm($form);
			$form->get('id')->required();

			$form->import($request->getGet())->importMore($request->getPost());

			if ($form->getErrors()) {
				return $this->getMav('index', 'NotFound');
			}

			$infoObject = $form->getValue('id');
			$this->model->
				set('infoObject', $infoObject)->
				set('customInfoFieldsData', $this->getCustomInfoFieldsData($infoObject))->
				set('orderFunction', $this->getFunctionListOrder())->
				set('buttonUrlList', $this->getButtonUrlList($infoObject));
			return $this->getMav('info');
		}

		/**
		 * Возвращает модель с данными для редактирования объекта (форму)
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		protected function editProcess(HttpRequest $request)
		{
			$className = $this->getObjectName();
			if (!$this->serviceLocator->get('linker')->isObjectSupported($className, 'edit')) {
				throw new PermissionException('No permission for edit '.$className);
			}

			$proto = $this->getObjectProto();

			$form = $proto->makeForm();
			$subject = ClassUtils::callStaticMethod("{$this->getObjectName()}::create");

			$command = $this->getCommand();
			/* @var $command EditorCommand */
			$mav = $command->run($subject, $form, $request);

			return $this->getEditMav($form, $subject, $mav->getModel());
		}

		/**
		 * Валидирует данные для сохранения в объект,
		 * если данные валидны - выполняет операцию сохранения объекта и возвращает редирект на просмотр объекта
		 * если данные не валидны - отмечает не валидные примитивы в форме
		 *  и возвращает форму для продолжения редактирования
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		protected function takeProcess(HttpRequest $request)
		{
			$className = $this->getObjectName();
			if (!$this->serviceLocator->get('linker')->isObjectSupported($className, 'edit')) {
				throw new PermissionException('No permission for edit '.$className);
			}

			$proto = $this->getObjectProto();

			$form = $proto->makeForm();
			$subject = ClassUtils::callStaticMethod("{$this->getObjectName()}::create");

			$command = $this->getCommand();
			/* @var $command EditorCommand */
			if ($this->isTakeInTransaction()) {
				$command = new CarefulDatabaseRunner($command);
			}

			$mav = $command->run($subject, $form, $request);

			if ($mav->getView() != EditorController::COMMAND_SUCCEEDED) {
				if ($command instanceof CarefulCommand) {
					$command->rollback();
				}
				return $this->getEditMav($form, $subject, $mav->getModel());
			}

			if ($command instanceof CarefulCommand) {
				$command->commit();
			}

			if ($this->serviceLocator->get('isAjax')) {
				$isNew = (bool) $request->hasGetVar('id') ? $request->getGetVar('id') : false;
				$this->model->
					set('isNew', $isNew)->
					set('infoObject', $subject)->
					set('infoUrl', $this->getUrlInfo($subject));
				return $this->getMav('edit.success');
			}

			return $this->getMavRedirectByUrl($this->getUrlInfo($subject));
		}

		protected function getEditMav(Form $form, IdentifiableObject $subject, Model $commandModel)
		{
			$infoObject = $form->getValue('id') ?: $subject;
			$this->model->
				set('form', $form)->
				set('infoObjectPrototype', $subject)->
				set('infoObject', $infoObject)->
				set('commandModel', $commandModel)->
				set('customEditFieldsData', $this->getCustomEditFieldsData($form, $subject))->
				set('orderFunction', $this->getFunctionListOrder())->
				set('infoUrl', $this->getUrlInfo($infoObject))->
				set('takeUrl', $this->getUrlTake($infoObject));

			return $this->getMav('edit');
		}

		/**
		 * Возвращает массив дополнительных данных для кастомного отображения свойств объекта
		 * @param IdentifiableObject $infoObject
		 * @return array
		 */
		protected function getCustomInfoFieldsData(IdentifiableObject $infoObject)
		{
			return array();
		}

		/**
		 * Возвращает массив дополнительных данных для кастомного отображения редактируемых полей объекта
		 * @param Form $form
		 * @param IdentifiableObject $subject
		 * @return array
		 */
		protected function getCustomEditFieldsData(Form $form, IdentifiableObject $subject)
		{
			return array();
		}

		/**
		 * Возвращает порядок сортировки провертей объекта при его отображении
		 * Все не перечисленные параметры будут оказываться после перечисленных в порядке по умолчнию
		 * @return array
		 */
		protected function getOrderFieldList()
		{
			return array();
		}

		/**
		 * Возвращает имя класса бизнес объекта с которым работает данный контроллер
		 * По умолчанию для удобства это обрезанное название текущего контроллера (убрана часть controller)
		 * @return string
		 */
		protected function getObjectName()
		{
			$className = get_class($this);
			return substr($className, 0, stripos($className, 'controller'));
		}

		/**
		 * Возвращает прото объекта, с которым происходит работа в текущем контроллере
		 * @return AbstractProtoClass
		 */
		protected function getObjectProto()
		{
			return ClassUtils::callStaticMethod("{$this->getObjectName()}::proto");
		}

		/**
		 * Возвращает название комманды, реализующей редактирование объекта
		 * @return string
		 */
		protected function getCommandName()
		{
			return 'TakeEditToolkitCommand';
		}

		/**
		 * Создает и возвращает комманду для редактирования объекта
		 * @return EditorCommand
		 */
		protected function getCommand()
		{
			$command = $this->serviceLocator->spawn($this->getCommandName());
			
			if ($command instanceof TakeEditToolkitCommand) {
				$serviceLocator = $this->serviceLocator;
				$command->setLogCallback(
					function(array $logData, IdentifiableObject $logObject) use ($serviceLocator) {
						$log = IngLog::createByObject(
							$logData,
							$logObject,
							$serviceLocator->get('admin')->getUser()
						);

						$log->dao()->add($log);
					}
				);
			}
			
			return $command;
		}
		
		protected function getLogClassName()
		{
			return 'IngLog';
		}

		/**
		 * Признак необходимости выполнять комманду в транзакции
		 * @return boolean
		 */
		protected function isTakeInTransaction()
		{
			return false;
		}

		/**
		 * Возвращает дефолтный путь к директории с шаблонами
		 * @return string
		 */
		protected function getViewPath()
		{
			return 'Objects/SimpleObject';
		}

		/**
		 * Возвращает массив ассоциативный названий-действий
		 * - url'ов действий которые можно делать пользователю с объектом
		 * @param type $infoObject
		 */
		protected function getButtonUrlList(IdentifiableObject $infoObject)
		{
			$linker = $this->serviceLocator->get('linker');
			/* @var $linker ToolkitLinkUtils */
			$buttonList = array();
			if ($linker->isObjectSupported($infoObject, 'edit')) {
				$buttonList['Edit'] = array(
					'window' => true,
					'url' => $this->getUrlEdit($infoObject),
				);
			}
			if ($linker->isObjectSupported('IngLog', 'info')) {
				$buttonList['Logs'] = array(
					'window' => false,
					'url' => $linker->getUrlLog($infoObject),
				);
			}

			return $buttonList;
		}

		/**
		 * Возвращает url для просмотра свойств объекта
		 * @param IdentifiableObject $infoObject
		 * @return string
		 */
		protected function getUrlInfo(IdentifiableObject $infoObject)
		{
			return $this->serviceLocator->get('linker')->getUrl($infoObject);
		}

		/**
		 * Возвращает url для формы-редактирования объекта
		 * @param IdentifiableObject $infoObject
		 * @return string
		 */
		protected function getUrlEdit(IdentifiableObject $infoObject)
		{
			return $this->serviceLocator->get('linker')->getUrl($infoObject, array('action' => 'edit'), 'edit');
		}

		/**
		 * Возвращает url для операции сохранения новых свойств из формы объекта
		 * @param IdentifiableObject $infoObject
		 * @return string
		 */
		protected function getUrlTake(IdentifiableObject $infoObject)
		{
			return $this->serviceLocator->get('linker')->getUrl($infoObject, array('action' => 'take'), 'edit');
		}

		final protected function getEmptyFieldData() {
			return array('tpl' => 'Objects/SimpleObject/empty');
		}

		/**
		 * Возвращает анонимную функцию для сортировки ассоциативной массива в необходимом порядке
		 * @return
		 */
		private function getFunctionListOrder()
		{
			$indexList = $this->getOrderFieldList();

			return function(array $dataList) use ($indexList) {
				$resultList = array();
				foreach ($indexList as $indexName) {
					if (array_key_exists($indexName, $dataList)) {
						$resultList[$indexName] = $dataList[$indexName];
						unset($dataList[$indexName]);
					}
				}
				$resultList += $dataList;
				return $resultList;
			};
		}
	}