<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class CacheController extends BaseController
{
	public function clean(): BaseView
	{
		$login = $this->_getLogin();
		if (!$login->isSuperAdmin()) {
			return $this->_restApiForbidden("只有超级管理员能清空缓存");
		}
		return $this->_restApiOk("ok");
	}
}
