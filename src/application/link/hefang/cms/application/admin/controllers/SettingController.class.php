<?php


namespace link\hefang\cms\application\admin\controllers;


use Exception;
use link\hefang\cms\application\admin\models\SettingModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;
use Throwable;

class SettingController extends BaseCmsController
{
	private $login;

	public function __construct()
	{
		$this->login = $this->_checkLogin();
	}

	/**
	 * 查询数据列表
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		$category = $this->_request("category", $cmd);
		$where = "enable = TRUE AND show_in_center = TRUE";
		if (!StringHelper::isNullOrBlank($category)) {
			$where .= " AND category = '{$category}'";
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
		$kv = explode("|", $id);
		$category = $kv[0];
		$key = $kv[1];
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

	/**
	 * 修改或添加配置
	 * @param string|null $id
	 * @return BaseView
	 */
	public function set(string $id = null): BaseView
	{
		$method = $this->_method();
		$data = $this->_post();
		try {
			if ($method === "POST") {
				$model = new SettingModel();
				foreach ($data as $key => $value) {
					$model->setValue2Prop($value, $key);
				}
				$model->setCategory("custom");
				$res = $model->insert() ? 1 : 0;
			} else if ($method === "PUT") {
				$res = SettingModel::saveSettings($data);
			} else {
				return $this->_restApiMethodNotAllowed();
			}
			return $res > 0 ? $this->_restApiOk() : $this->_restNotModified($res);
		} catch (Throwable $e) {
			return $this->_restApiServerError($e);
		}
	}
}
