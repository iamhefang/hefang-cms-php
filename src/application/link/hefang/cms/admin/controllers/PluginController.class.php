<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\core\plugin\PluginManager;
use link\hefang\mvc\views\BaseView;

class PluginController extends BaseCmsController
{
	public function list(string $cmd = null): BaseView
	{
		$login = $this->_checkLogin();
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden();
		}

		return $this->_restApiOk(PluginManager::listPlugins());
	}

	/**
	 * 安装插件
	 * @method POST
	 * @return BaseView
	 */
	public function install(): BaseView
	{
		$login = $this->_checkLogin();

	}
}
