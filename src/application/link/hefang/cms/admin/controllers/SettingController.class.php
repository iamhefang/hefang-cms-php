<?php


namespace link\hefang\cms\admin\controllers;


use Exception;
use link\hefang\cms\admin\models\SettingModel;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\HeFangCMS;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class SettingController extends BaseCmsController
{


	/**
	 * 查询数据列表
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		$category = $this->_request("category");
		$search = $this->_request(HeFangCMS::queryKey());
		$where = "enable = TRUE AND show_in_center = FALSE";
		if (!StringHelper::isNullOrBlank($category)) {
			$where .= "category = '{$category}'";
		}
		try {
			$pager = SettingModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$where,
				SettingModel::sort2sql($this->_sort())
			);
			return $this->_restApiOk($pager);
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
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
		[$category, $key] = explode("|", $id);
		$category = $this->_request("category", $category);
		$key = $this->_request("key", $key);
		if (StringHelper::isNullOrBlank($category) || StringHelper::isNullOrBlank($key)) {
			return $this->_restApiBadRequest();
		}
		try {
			$model = SettingModel::find("category = '{$category}' AND `key` = '{$key}'");
			if (!($model instanceof SettingModel) || !$model->isExist() || !$model->isEnable()) {
				return $this->_restApiNotFound("该配置不存在或已被删除");
			}
			return $this->_restApiOk($model);
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}
}
