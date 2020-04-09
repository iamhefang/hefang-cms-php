<?php


namespace link\hefang\cms\application\content\controllers;


use Exception;
use link\hefang\cms\application\content\models\CategoryModel;
use link\hefang\cms\application\user\models\AccountModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class CategoryController extends BaseCmsController
{
	public function set(string $id = null): BaseView
	{
		$user = $this->_getLogin();
		if (!($user instanceof AccountModel)) return $this->_restApiUnauthorized();
		$id = $this->_post("id");
		$name = $this->_post("name");
		$keywords = $this->_post("keywords");
		$description = $this->_post("description");
		$type = $this->_post("type", "article");

		if (StringHelper::isNullOrBlank($id)) {
			return $this->_restApiBadRequest("分类标识不能为空");
		}

		if (StringHelper::isNullOrBlank($name)) {
			return $this->_restApiBadRequest("分类名不能为空");
		}

		if (!in_array($type, ["article", "page"])) {
			return $this->_restApiBadRequest("类型参数异常");
		}

		$model = new CategoryModel();
		$model->setId($id)
			->setName($name)
			->setKeywords($keywords)
			->setType($type)
			->setDescription($description);
		try {
			return $model->insert() ? $this->_restApiCreated() : $this->_restNotModified();
		} catch (SqlException $e) {
			if (StringHelper::contains($e->getMessage(), false, "23000", "1062")) {
				return $this->_restApiBadRequest("分类标识“{$id}”已存在");
			}
			return $this->_restApiServerError($e);
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}

	public function list(string $cmd = null): BaseView
	{
		$type = $this->_request("type");
		$where = "enable = TRUE";
		if (!StringHelper::isNullOrBlank($type)) {
			$where .= " AND `type` = '{$type}'";
		}
		try {
			return $this->_restApiOk(CategoryModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$where
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}
}
