<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.0.10.99 at 2011-10-15 14:43:52                     *
 *   This file will never be generated again - feel free to edit.            *
 *****************************************************************************/

	class IngProductDAO extends AutoIngProductDAO
	{
		public function getListOrdered() {
			return Criteria::create($this)
				->addOrder('name')
				->getList();
		}
	}
?>