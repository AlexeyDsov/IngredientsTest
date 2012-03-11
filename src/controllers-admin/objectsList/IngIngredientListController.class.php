<?php
	/**
	 * Отвечает за работу со списком адмиеов
	 */
	class IngIngredientListController extends SimpleListController {

		protected function prepairData(HttpRequest $request, ModelAndView $mav)
		{
			$mav = parent::prepairData($request, $mav);
			$mav->getModel()->set('currentMenu', 'Receipts.IngIngredient.List');
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
				'product' => array(
					ListMakerProperties::OPTION_COLUMN => true,
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'receipt' => array(
					ListMakerProperties::OPTION_COLUMN => true,
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'weight' => array(
					ListMakerProperties::OPTION_COLUMN => true,
				),
				'count' => array(
					ListMakerProperties::OPTION_COLUMN => true,
				),
				'comment' => array(
					ListMakerProperties::OPTION_COLUMN => true,
				),
			);
		}
	}
?>