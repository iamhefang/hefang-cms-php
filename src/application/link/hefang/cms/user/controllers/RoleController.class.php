<?php


namespace link\hefang\cms\user\controllers;


use link\hefang\cms\user\models\RoleModel;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\interfaces\IDULG;
use link\hefang\mvc\views\BaseView;

class RoleController extends BaseController implements IDULG
{

	/**
	 * 添加数据
	 * @return BaseView
	 */
	public function insert(): BaseView
	{
		// TODO: Implement insert() method.
	}

	/**
	 * 删除数据
	 * @return BaseView
	 */
	public function delete(): BaseView
	{
		// TODO: Implement delete() method.
	}

	/**
	 * 更新数据
	 * @return BaseView
	 */
	public function update(): BaseView
	{
		// TODO: Implement update() method.
	}

	/**
	 * 查询数据列表
	 * @needLogin
	 * @return BaseView
	 */
	public function list(): BaseView
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

	/**
	 * 获取一条数据详情
	 * @param string|null $id 要获取详情的数据的主键
	 * @return BaseView
	 */
	public function get(string $id = null): BaseView
	{
		// TODO: Implement get() method.
	}
}
