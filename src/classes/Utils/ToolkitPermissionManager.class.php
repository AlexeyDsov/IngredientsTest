<?php
	class ToolkitPermissionManager extends PermissionManager {

		/**
		 * @return ToolkitPermissionManager
		 */
		public static function create()
		{
			return new self;
		}
		/**
		 * Возвращает признак
		 * @param IPermissionUser $user
		 * @param string $action
		 * @return bool
		 */
		public function hasPermission(IPermissionUser $user, $action)
		{
			if ($user->getId() == 1) {
				//temporary @hack - временный хак, что бы не отвлекаться на заполнения таблицы с правами
				return true;
			}
			return parent::hasPermission($user, $action);
		}
	}
?>