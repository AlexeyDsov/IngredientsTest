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
	class WebAppViewHandlerToolkitIng extends WebAppViewHandlerToolkit
	{
		/**
		 * @return WebAppViewHandlerToolkitIng
		 */
		public static function create()
		{
			return new self();
		}
		
		/**
		 * @return string
		 */
		protected function getMenuContructor() {
			return 'ToolkitMenuConstructorIng';
		}

//		need create project-own in future
//		/**
//		 * @return string
//		 */
//		protected function getNameConverterClass() {
//			return 'ObjectNameConverter';
//		}
	}
?>