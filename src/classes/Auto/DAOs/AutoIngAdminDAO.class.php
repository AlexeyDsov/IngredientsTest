<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.0.10.99 at 2011-10-15 14:43:52                     *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoIngAdminDAO extends StorableDAO
	{
		public function getTable()
		{
			return 'ing_admin';
		}
		
		public function getObjectName()
		{
			return 'IngAdmin';
		}
		
		public function getSequence()
		{
			return 'ing_admin_id';
		}
		
		public function getLinkName()
		{
			return 'ing';
		}
	}
?>