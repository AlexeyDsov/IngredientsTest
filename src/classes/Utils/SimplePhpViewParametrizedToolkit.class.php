<?php
	class SimplePhpViewParametrizedToolkit extends SimplePhpViewParametrized {

		protected function isTimePrimitive(Form $form, $propertyName, $filterName)
		{
			$timePrimitiveList = array('PrimitiveTimestamp');

			return in_array(get_class($form->getValue($propertyName)->get($filterName)), $timePrimitiveList);
		}

		protected function isDatePrimitive(Form $form, $propertyName, $filterName)
		{
			$datePrimitiveList = array('PrimitiveDate');

			return in_array(get_class($form->getValue($propertyName)->get($filterName)), $datePrimitiveList);
		}

		protected function getFilteredValue(Form $form, $propertyName, $filterName, $propertyData)
		{
			if (!isset($propertyData[$filterName])) {
				return '';
			}

			if ($this->isDatePrimitive($form, $propertyName, $filterName)) {
				$value = $propertyData[$filterName];
				return $value['year'] . '-' . $value['month'] . '-' . $value['day'];
			}

			if ($this->isTimePrimitive($form, $propertyName, $filterName)) {
				return $this->escape($propertyData[$filterName]);
			}

			return $this->escape($propertyData[$filterName]);
		}
	}
?>