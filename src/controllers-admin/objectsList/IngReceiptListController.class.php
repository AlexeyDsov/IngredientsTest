<?php
	/**
	 * Отвечает за работу со списком адмиеов
	 */
	class IngReceiptListController extends SimpleListController {

		protected function prepairData(HttpRequest $request, ModelAndView $mav)
		{
			$mav = parent::prepairData($request, $mav);
			$mav->getModel()->set('currentMenu', 'Receipts.IngReceipt.List');
			return $mav;
		}

		protected function getPropertyList()
		{
			return array(
				'id' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'name' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_ILIKE,
					)
				),
				'product' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
			);
		}
	}
?>