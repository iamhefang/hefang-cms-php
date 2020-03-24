<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\admin\models\SettingCategoryModel;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class SettingCategoryController extends BaseCmsController
{

	/**
	 * 查询数据列表
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		try {
			$pager = SettingCategoryModel::pager(
				$this->_pageIndex(),
				100,
				null,
				SettingCategoryModel::sort2sql($this->_sort("sort"))
			);
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
