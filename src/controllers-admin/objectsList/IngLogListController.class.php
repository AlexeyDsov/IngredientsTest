<?php
	/**
	 * Отвечает за работу со списком логов
	 */
	class IngLogListController extends SimpleListController {

		protected function prepairData(HttpRequest $request, ModelAndView $mav)
		{
			$mav = parent::prepairData($request, $mav);
			$mav->getModel()->set('currentMenu', 'AdminMenu.IngLog.List');
			return $mav;
		}

		protected function getPropertyList()
		{
			return array(
				'id' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_GT,
						ListMakerProperties::OPTION_FILTERABLE_GTEQ,
						ListMakerProperties::OPTION_FILTERABLE_LT,
						ListMakerProperties::OPTION_FILTERABLE_LTEQ,
					)
				),
				'objectName' => array(
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'objectId' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'adminId' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_IN,
					)
				),
				'insertDate' => array(
					ListMakerProperties::OPTION_ORDERING => ListMakerProperties::ORDER_ASC,
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_GT,
						ListMakerProperties::OPTION_FILTERABLE_GTEQ,
						ListMakerProperties::OPTION_FILTERABLE_LT,
						ListMakerProperties::OPTION_FILTERABLE_LTEQ,
					)
				),
				'message' => array(
					ListMakerProperties::OPTION_FILTERABLE => array(
						ListMakerProperties::OPTION_FILTERABLE_EQ,
						ListMakerProperties::OPTION_FILTERABLE_ILIKE,
					)
				),
			);
		}
	}
?>