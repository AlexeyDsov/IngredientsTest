<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.0.10.99 at 2012-03-11 01:39:54                     *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoProtoIngAdmin extends AbstractProtoClass
	{
		protected function makePropertyList()
		{
			return array(
				'id' => LightMetaProperty::fill(new LightMetaProperty(), 'id', null, 'integerIdentifier', 'IngAdmin', 8, true, true, false, null, null),
				'name' => LightMetaProperty::fill(new LightMetaProperty(), 'name', null, 'string', null, 64, true, true, false, null, null),
				'email' => LightMetaProperty::fill(new LightMetaProperty(), 'email', null, 'string', null, 128, true, true, false, null, null),
				'passwordHash' => LightMetaProperty::fill(new LightMetaProperty(), 'passwordHash', 'password_hash', 'string', null, 32, true, true, false, null, null),
				'loginKey' => LightMetaProperty::fill(new LightMetaProperty(), 'loginKey', 'login_key', 'string', null, 32, false, true, false, null, null)
			);
		}
	}
?>