<?php


namespace link\hefang\cms\application\user\controllers;


use link\hefang\cms\application\user\models\RoleModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class RoleController extends BaseCmsController
{

	/**
	 * 查询数据列表
	 * @needLogin
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		try {
			return $this->_restApiOk(RoleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize()
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e, "读取数据时出现异常");
		}
	}
}
