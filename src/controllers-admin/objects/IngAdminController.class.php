<?php
	/**
	 * Отвечает за отображение и редактирование настроек пользователей/админов
	 */
	class IngAdminController extends SimpleObjectFlowController {

		/**
		 * Возвращает массив дополнительных данных для кастомного отображения свойств объекта
		 * @param IdentifiableObject $infoObject
		 * @return array
		 */
		protected function getCustomInfoFieldsData(IdentifiableObject $infoObject)
		{
			return array(
				'passwordHash' => $this->getEmptyFieldData(),
				'loginKey' => $this->getEmptyFieldData(),
			);
		}

		/**
		 * Возвращает дополнительные данные для отображения кастномного редактирования полей
		 * @param Form $form
		 * @param IdentifiableObject $subject
		 * @return array
		 */
		protected function getCustomEditFieldsData(Form $form, IdentifiableObject $subject)
		{
			return array(
				'passwordHash' => $this->getEmptyFieldData(),
				'loginKey' => $this->getEmptyFieldData(),
			);
		}

		/**
		 * Возвращает название комманды, реализующей редактирование объекта
		 * @return string
		 */
		protected function getCommandName()
		{
			return 'TakeEditToolkitCommandIngAdmin';
		}

		/**
		 * Создает и возвращает комманду для редактирования объекта
		 * @return EditorCommand
		 */
		protected function getCommand()
		{
			return parent::getCommand()->setEditableFieldList(array('id', 'name', 'email', 'passwordNew', 'passwordHash'));
		}
	}
?>