<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class CacheController extends BaseController
{
	public function clean(): BaseView
	{
		$this->_checkSuperAdmin();
		return $this->_apiSuccess();
	}
}
