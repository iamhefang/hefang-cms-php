<?php


namespace link\hefang\cms\content\controllers;


use Exception;
use link\hefang\cms\content\models\CategoryModel;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class CategoryController extends BaseController
{
	public function insert(): BaseView
	{
		$user = $this->_getLogin();
		if (!($user instanceof AccountModel)) return $this->_restApiUnauthorized();
		$id = $this->_post("id");
		$name = $this->_post("name");
		$keywords = $this->_post("keywords");
		$description = $this->_post("description");

		if (StringHelper::isNullOrBlank($id)) {
			return $this->_restApiBadRequest("分类标识不能为空");
		}

		if (StringHelper::isNullOrBlank($name)) {
			return $this->_restApiBadRequest("分类名不能为空");
		}


		$model = new CategoryModel();
		$model->setId($id)
			->setName($name)
			->setKeywords($keywords)
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

	public function list(): BaseView
	{
		try {
			return $this->_restApiOk(CategoryModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				null,
				"enable = TRUE"
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}
}
