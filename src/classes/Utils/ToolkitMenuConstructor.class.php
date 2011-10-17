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
	 * Класс для создания верхнего меню в админке. Временно решение, в будуйщем может быть изменено
	 */
	class ToolkitMenuConstructor
	{
		/**
		 * @var PermissionManager
		 */
		protected $permissionManager = null;
		/**
		 * @var IPermissionUser
		 */
		protected $user = null;

		/**
		 * @return ToolkitMenuConstructor
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @param PermissionManager $permissionManager
		 * @return ToolkitMenuConstructor
		 */
		public function setPermissionManager(PermissionManager $permissionManager)
		{
			$this->permissionManager = $permissionManager;
			return $this;
		}

		/**
		 * @param IPermissionUser $user
		 * @return ToolkitMenuConstructor
		 */
		public function setUser(IPermissionUser $user)
		{
			$this->user = $user;
			return $this;
		}

		/**
		 * Создаем массив stdClass'ов, реализующих меню
		 * @return array
		 */
		public function getMenuList()
		{
			$menuParams = array(
				'IngAdmin' => array('Admin', 'Admins'),
				'IngLog' => array('Log', 'Logs'),
				'IngIngredient' => array('Ingredient', 'Ingredients'),
				'IngProduct' => array('Product', 'Products'),
				'IngReceipt' => array('Receipt', 'Receipts'),
			);
			$menuList = array();
			foreach ($menuParams as $objectName => $objectSetup) {
				if ($menuPart = $this->makeSimpleObjectMenu($objectName, $objectSetup)) {
					$menuList[$objectName] = $menuPart;
				}
			}

			if ($menu = $this->makeCustomMenu()) {
				$menuList[$menu->name] = $menu;
			}

			return $this->postProcessMenu($menuList);
		}

		/**
		 * Генерим простейшее меню, в
		 * @param string $name Латинское название объекта (префикс в названии контроллера)
		 * @param string $text Русское название объекта
		 * @return stdClass
		 */
		private function  makeSimpleObjectMenu($name, $text)
		{
			if (!$this->permissionManager || !$this->user) {
				return null;
			}

			if (!$this->permissionManager->hasPermission($this->user, "{$name}.info")) {
				return null;
			}

			$class = new stdClass();
			$class->name = $name;
			$class->title = $text[0];
			$class->url = PATH_WEB_URL . "area={$name}List";

			$listSubclass = new stdClass();
			$listSubclass->name = "List";
			$listSubclass->title = "List of {$text[1]}";
			$listSubclass->url = PATH_WEB_URL . "area={$name}List";

			$class->submenu = array(
				$listSubclass,
			);
			return $class;
		}

		private function postProcessMenu($menuList)
		{
			foreach ($this->getPostProcessMapping() as $menuName => $options) {

				$class = new stdClass();
				$class->name = $menuName;
				$class->title = $options['title'];

				$subMenuList = array();
				foreach ($options['submenu'] as $subMenuName) {
					if (isset($menuList[$subMenuName])) {
						$subMenu = $menuList[$subMenuName];
						if (empty($subMenuList)) {
							$class->url = $subMenu->url;
						}
						$subMenuList[$subMenuName] = $subMenu;
						unset($menuList[$subMenuName]);
					}
				}

				if (!empty($subMenuList)) {
					$class->submenu = $subMenuList;
					$menuList[$menuName] = $class;
				}
			}

			return $menuList;
		}

		private function getPostProcessMapping()
		{
			return array(
				'Receipts' => array(
					'title' => 'Ingredient',
					'submenu' => array(
						'IngIngredient',
						'IngReceipt',
						'IngProduct',
					),
				),
				'AdminMenu' => array(
					'title' => 'Admin menu',
					'submenu' => array(
						'IngAdmin',
						'IngLog',
					),
				),
			);
		}

		private function makeCustomMenu()
		{
			if (!$this->checkCustomPermission('customMenu.action')) {
				return null;
			}

			$class = new stdClass();
			$class->name = 'CustomMenu';
			$class->title = 'Custom menu';
			$class->url = PATH_WEB_URL . "area=CustomMenu";

			return $class;
		}

		private function checkCustomPermission($permission)
		{
			if (!$this->permissionManager || !$this->user) {
				return false;
			}

			if (!$this->permissionManager->hasPermission($this->user, $permission)) {
				return false;
			}

			return true;
		}
	}
?>