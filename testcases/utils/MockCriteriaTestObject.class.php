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

	class MockCriteriaTestObject
	{
		public function sum($a, $b)
		{
			return $a + $b;
		}

		public function sub($a, $b)
		{
			return $a - $b;
		}

		public function div($a, $b)
		{
			if ($b == 0) {
				throw new Exception('Can\'t dive by zero');
			}
			return $a / $b;
		}
	}
?>