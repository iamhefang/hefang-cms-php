<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\admin\models\SettingCategoryModel;
use link\hefang\cms\HeFangCMS;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\interfaces\IDULG;
use link\hefang\mvc\views\BaseView;

class SettingCategoryController extends BaseController implements IDULG
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
	 * @return BaseView
	 */
	public function list(): BaseView
	{
		$search = $this->_request(HeFangCMS::searchKey());
		try {
			$pager = SettingCategoryModel::pager($this->_pageIndex(), $this->_pageSize(), $search);
			return $this->_restApiOk($pager);
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
		$id = $this->_request("id", $id);
		try {
			$model = SettingCategoryModel::get($id);
			return $this->_restApiOk($model);
		} catch (ModelException $e) {
			return $this->_restApiServerError($e, "解析数据时出现异常");
		} catch (SqlException $e) {
			return $this->_restApiServerError($e, "读取数据时出现异常");
		}
	}
}
