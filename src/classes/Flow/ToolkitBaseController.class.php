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

	abstract class ToolkitBaseController extends BaseController
	{
		protected function setupMeta()
		{
			parent::setupMeta();
			$this->meta->setTitle('Toolkit');
			return $this;
		}

		protected function getMavRedirectByUrl($url)
		{
			if ($this->serviceLocator->get('isPjax')) {
				return ModelAndView::create()->
					setView('helpers/pjax.redirect')->
					setModel(Model::create()->set('url', $url));
			}
			return parent::getMavRedirectByUrl($url);
		}
	}
?>