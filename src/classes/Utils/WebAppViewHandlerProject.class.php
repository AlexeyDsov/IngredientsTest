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

	class WebAppViewHandlerProject extends WebAppViewHandler
	{
		/**
		 * @return WebAppViewHandlerProject
		 */
		public static function create()
		{
			return new self();
		}

		/**
		 * @param InterceptingChain $chain
		 * @param Model $model
		 * @return ViewResolver
		 */
		protected function getViewResolver(InterceptingChain $chain, Model $model)
		{
			$isPjax = $chain->hasVar('isPjax') ? $chain->getVar('isPjax') : false;
			$isAjax = $chain->hasVar('isAjax') ? $chain->getVar('isAjax') : false;
			
			$resolver = MultiPrefixPhpViewResolverParametrized::create()->
				addFirstPrefix($chain->getPathTemplate())->
				setViewClassName('SimplePhpViewParametrizedToolkit')->
				set('isAjax', $isAjax)->
				set('isPjax', $isPjax)->
				set('serviceLocator', $chain->getServiceLocator())->
				set('linker', $chain->getServiceLocator()->get('linker'));
			
			if (!$isAjax && ($menuList = $this->getMenuList($chain))) {
				$resolver->set('menuList', $menuList);
			}
			
			return $resolver;
		}

		protected function getMenuList(InterceptingChain $chain)
		{
			$serviceLocator = $chain->getVar('serviceLocator');
			$user = $serviceLocator->get('admin')->getUser();

			if ($user) {
				return ToolkitMenuConstructor::create()->
					setPermissionManager($serviceLocator->get('permissionManager'))->
					setUser($user)->
					getMenuList();
			}

			return null;
		}
	}
?>