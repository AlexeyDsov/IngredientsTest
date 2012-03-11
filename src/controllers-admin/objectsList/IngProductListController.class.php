<?php
	/**
	 * Отвечает за работу со списком адмиеов
	 */
	class IngProductListController extends SimpleListController {

		protected function prepairData(HttpRequest $request, ModelAndView $mav)
		{
			$mav = parent::prepairData($request, $mav);
			$mav->getModel()->set('currentMenu', 'Receipts.IngProduct.List');
			return $mav;
		}

		protected function getPropertyList()
		{
			return array(
				'id' => array(
					ListMakerProperties::OPTION_COLUMN => true,
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'name' => array(
					ListMakerProperties::OPTION_COLUMN => true,
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_ILIKE,
					)
				),
				'description' => array(
					ListMakerProperties::OPTION_COLUMN => true,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_ILIKE,
					)
				),
			);
		}
	}
?>