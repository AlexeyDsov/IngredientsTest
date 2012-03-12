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

	class ObjectNameConverterIng extends ObjectNameConverter
	{
		public function get(IdentifiableObject $object) {
			switch (get_class($object)) {
				case 'IngIngredient':
					/* @var $object IngIngredient */
					return "{$object->getReceipt()->getName()} <- {$object->getProduct()->getName()}";
				default:
					return parent::get($object);
			}
		}
	}
?>