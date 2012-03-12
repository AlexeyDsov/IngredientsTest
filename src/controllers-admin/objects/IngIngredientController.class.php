<?php
	/**
	 * Отвечает за отображение и редактирование настроек пользователей/админов
	 */
	class IngIngredientController extends SimpleObjectFlowController {
		
		protected function getCustomEditFieldsData(Form $form, IdentifiableObject $subject) {
			return array(
				'product' => array('productList' => IngProduct::dao()->getListOrdered()),
				'receipt' => array('receiptList' => IngReceipt::dao()->getListOrdered()),
			);
		}
	}
?>