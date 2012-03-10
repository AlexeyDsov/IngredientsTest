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
	class ToolkitMenuConstructorIng extends ToolkitMenuConstructor
	{
		/**
		 * @return ToolkitMenuConstructorIng
		 */
		public static function create() {
			return new self;
		}
		
		protected function getSimpleObjectList() {
			return array(
				'IngAdmin' => array('Admin', 'Admins'),
				'IngLog' => array('Log', 'Logs'),
				'IngIngredient' => array('Ingredient', 'Ingredients'),
				'IngProduct' => array('Product', 'Products'),
				'IngReceipt' => array('Receipt', 'Receipts'),
			);
		}

		protected function getPostProcessMapping()
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
	}
?>