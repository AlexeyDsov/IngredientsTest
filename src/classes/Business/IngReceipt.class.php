<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.0.10.99 at 2011-10-15 14:43:52                     *
 *   This file will never be generated again - feel free to edit.            *
 *****************************************************************************/

	class IngReceipt extends AutoIngReceipt implements Prototyped, DAOConnected
	{
		/**
		 * @return IngReceipt
		**/
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return IngReceiptDAO
		**/
		public static function dao()
		{
			return Singleton::getInstance('IngReceiptDAO');
		}
		
		/**
		 * @return ProtoIngReceipt
		**/
		public static function proto()
		{
			return Singleton::getInstance('ProtoIngReceipt');
		}
		
		// your brilliant stuff goes here
	}
?>