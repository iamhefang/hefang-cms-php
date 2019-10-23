<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class ThemeController extends BaseController
{
	public function __construct()
	{
		$this->_checkAdmin();
	}

	public function list(): BaseView
	{

	}
}
