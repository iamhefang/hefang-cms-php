<?php

namespace link\hefang\cms\main\controllers;

use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class HomeController extends BaseController
{
	public function index(): BaseView
	{
		if ($this->_method() !== "POST") {
			return $this->_methodNotAllowed();
		}
		return $this->_text(print_r($_SERVER));
	}
}
