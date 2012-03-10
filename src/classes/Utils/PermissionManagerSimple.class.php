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
	 * Упрощенный класс проверки прав. Считаем что еслип пользователь авторизован - у него есть права на все
	 */
	class PermissionManagerSimple extends PermissionManager
	{
		/**
		 * @return PermissionManagerSimple
		 */
		public static function create() {
			return new self;
		}
		
		public function hasPermission(IPermissionUser $user, $method, $object) {
			return true;
		}
		
		public function hasPermissionToClass(IPermissionUser $user, $method, $object) {
			return true;
		}
	}
?>