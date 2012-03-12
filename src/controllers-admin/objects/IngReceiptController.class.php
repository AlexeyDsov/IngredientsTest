<?php
	/**
	 * Отвечает за отображение и редактирование настроек пользователей/админов
	 */
	class IngReceiptController extends SimpleObjectFlowController {

		/**
		 * Возвращает массив дополнительных данных для кастомного отображения свойств объекта
		 * @param IdentifiableObject $infoObject
		 * @return array
		 */
		protected function getCustomInfoFieldsData(IdentifiableObject $infoObject)
		{
			return array(
				'ingredients' => array(),
			);
		}
		
		protected function getCustomEditFieldsData(Form $form, IdentifiableObject $subject) {
			return array(
				'product' => array('productList' => IngProduct::dao()->getListOrdered()),
				'ingredients' => $this->getEmptyFieldData(),
			);
		}
	}
?>