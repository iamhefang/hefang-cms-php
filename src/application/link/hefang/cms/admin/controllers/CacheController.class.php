<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class CacheController extends BaseCmsController
{
	public function clean(): BaseView
	{
		$login = $this->_getLogin();
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden("只有超级管理员能清空缓存");
		}
		Mvc::getCache()->clean();
		return $this->_restApiOk("ok");
	}
}
