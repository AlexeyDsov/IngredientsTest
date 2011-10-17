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
	 * Реализует отображение списков объектов.
	 * В наследнике класса необходимо указать proto объекта и propertyList - настройки для получения списка
	 */
	abstract class SimpleListController extends ToolkitBaseController {

		protected $methodMap = array(
			'show' => 'showProcess',
			'list' => 'listProcess',
		);
		protected $defaultAction = 'show';

		/**
		 * @return ModelAndView
		**/
		public function handleRequest(HttpRequest $request)
		{
			$className = $this->getObjectName();
			if (!$this->serviceLocator->get('linker')->isObjectSupported($className, 'info')) {
				throw new PermissionException('No permission for info '.$className);
			}

			return $this->resolveAction($request);
		}

		/**
		 * Возвращает настройки получения списка
		 * @return array
		 */
		abstract protected function getPropertyList();

		/**
		 * Возвращает MaV для отображения условий поиска
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		protected function showProcess(HttpRequest $request)
		{
			$propertyList = $this->getPropertyList();
			$proto = $this->getProto();

			$form = ListMakerFormBuilder::create($proto, $propertyList)->buildForm();
			$this->model->
				set('form', $form)->
				set('propertyList', $propertyList)->
				set('listHeaderModel', $this->makeListHeaderModel($form, $propertyList));

			return $this->getMav('list');
		}

		/**
		 * Возвращает MaV с результатами поиска
		 * @param $request HttpRequest
		 * @return ModelAndView
		**/
		protected function listProcess(HttpRequest $request)
		{
			$this->fillModel($request);
			return $this->getMav('list');
		}

		/**
		 * Заполняет модель результатом поиска
		 *
		 * @param HttpRequest $request
		 * @return Model
		 */
		protected function fillModel(HttpRequest $request)
		{
			$propertyList = $this->getPropertyList();
			$proto = $this->getProto();

			$form = ListMakerFormBuilder::create($proto, $propertyList)->buildForm();
			$form->import($request->getGet());

			$this->model->
				set('form', $form)->
				set('propertyList', $propertyList)->
				set('listHeaderModel', $this->makeListHeaderModel($form, $propertyList));

			if ($form->getErrors()) {
				return $this->model;
			}

			$constructor = ListMakerConstructor::create($proto, $propertyList);
			$queryResult = $constructor->getResult($form);

			$this->model->
				set('limitName', $constructor->getLimitName())->
				set('offsetName', $constructor->getOffsetName())->
				set('queryResult', $queryResult)->
				set('pagerModel', $this->makePagerModel($queryResult, $form))->
				set('columnModel', $this->makeColumnModel($form, $propertyList));

			return $this->model;
		}

		/**
		 * Возвращает подмодель с данными для фильтров поиска
		 * @param Form $form
		 * @param array $propertyList
		 * @return Model
		 */
		protected function makeListHeaderModel(Form $form, array $propertyList)
		{
			return Model::create()->
				set('form', $form)->
				set('propertyList', $propertyList)->
				set('urlParams', $this->getUrlParams());
		}

		/**
		 * Возвращает подмодель с данными для пейджера
		 * @param QueryResult $queryResult
		 * @param Form $form
		 * @return Model
		 */
		protected function makePagerModel(QueryResult $queryResult, Form $form)
		{
			return Model::create()->
				set('totalCount', $queryResult->getCount())->
				set('offset', $form->getSafeValue('offset'))->
				set('limit', $form->getSafeValue('limit'))->
				set('baseUrl', PATH_WEB_URL)->
				set('urlParams', $this->getUrlParams() + $form->export());
		}

		/**
		 * Возвращает подмодель с данными для рендеринга колонок сортировки
		 * @param Form $form
		 * @param array $propertyList
		 * @return Model
		 */
		protected function makeColumnModel(Form $form, array $propertyList)
		{
			$columnParams = $form->export();
			foreach (array_keys($columnParams) as $propertyName) {
				unset($columnParams[$propertyName]['order']);
				unset($columnParams[$propertyName]['sort']);
			}

			return Model::create()->
				set('propertyList', $propertyList)->
				set('baseUrl', PATH_WEB_URL)->
				set('urlParams', $this->getUrlParams() + $columnParams)->
				set('formData', $form->export());
		}

		/**
		 * Возвращает базовые параметры url'а для отображения текущего контроллера
		 * @return array
		 */
		protected function getUrlParams()
		{
			Assert::isTrue((bool)preg_match('~^(.*)Controller$~', get_class($this), $matches));
			return array(
				'area' => $matches[1],
				'action' => 'list',
			);
		}

		/**
		 * Возвращает название класса со списком элементов которого будет работать контроллер
		 * @return string
		 */
		protected function getObjectName()
		{
			$className = get_class($this);
			return substr($className, 0, stripos($className, 'listcontroller'));
		}

		/**
		 * Возвращает Proto объекта по которому создается список
		 * @return AbstractProtoClass
		 */
		protected function getProto()
		{
			return ClassUtils::callStaticMethod("{$this->getObjectName()}::proto");
		}

		/**
		 * Переопределенный метод возвращает путь до базовой директории шаблона
		 * @return string
		 */
		protected function getViewPath()
		{
			return 'Objects/'.$this->getObjectName();
		}
	}