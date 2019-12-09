<?php


namespace link\hefang\cms\content\controllers;

use link\hefang\cms\content\models\ArticleModel;
use link\hefang\cms\HeFangCMS;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\guid\GUKey;
use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class ArticleController extends BaseController
{
	public function set(string $id = null): BaseView
	{
		$id = $this->_request("id", $id);
		$user = $this->_getLogin();
		$method = $this->_method();
		if (!GUKey::isGuKey($id)) {
			return $this->_restApiBadRequest();
		}
		$model = null;
		$key = new GUKey(ArticleModel::class);
		if ($method === "POST") {
			$model = new ArticleModel();
			$model->setId($key->next());
		} else if ($method === "PATCH" || $method === "PUT") {
			try {
				$model = ArticleModel::get($id);
			} catch (ModelException $e) {
				return $this->_restApiServerError($e, "解析原数据时出现异常");
			} catch (SqlException $e) {
				return $this->_restApiServerError($e, "读取原数据时出现异常");
			}
		} else {
			return $this->_methodNotAllowed();
		}
	}

	public function get(string $id = null): BaseView
	{
		$id = $this->_request("id", $id);
		if (!GUKey::isGuKey($id)) {
			return $this->_restApiBadRequest("参数异常");
		}
		$login = $this->_getLogin();
		try {
			$model = ArticleModel::get($id);
			if (!($model instanceof ArticleModel) || !$model->isEnable()) {
				return $this->_restApiNotFound("请求的内容不存在或已被删除");
			}
			if ($login instanceof AccountModel) {
				if (($model->isDraft() && !$login->isAdmin() && $login->getId() !== $model->getAuthorId()) ||
					($model->isPrivate() && !$login->isSuperAdmin() && $login->getId() !== $model->getAuthorId())) {
					return $this->_restApiForbidden("您无权查看该文章");
				}
			} else if ($model->isDraft() || $model->isPrivate()) {
				return $this->_restApiNotFound("请求的内容不存在或未发表");
			}
			return $this->_restApiOk($model);
		} catch (ModelException $e) {
			return $this->_restApiServerError($e, "解析数据时出现异常");
		} catch (SqlException $e) {
			return $this->_restApiServerError($e, "读取数据时出现异常");
		}
	}

	public function list(): BaseView
	{
		$login = $this->_getLogin();
		$search = $this->_request(HeFangCMS::searchKey());
		$category = $this->_request("category");
		$tag = $this->_request("tag");
		$where = [];

		if ($login instanceof AccountModel) {
			if ($login->isAdmin()) {
				$showDisabled = $this->_request("showDisabled");
				$author = $this->_request("author");
				if (!StringHelper::isNullOrBlank($showDisabled)) {
					$showDisabled = ParseHelper::parseBoolean($showDisabled);
					if (!$showDisabled) {
						$where[] = "enable = TRUE";
					}
				}
				if (!StringHelper::isNullOrBlank($author) && strlen($author) === 40) {
					$where[] = "`author` = '{$author}'";
				}
			} else {
				$where[] = "`author`='{$login->getId()}'";
			}
		} else {
			$where[] = "enable = TRUE";
		}

		$categoryTable = Mvc::getTablePrefix() . "category";
		if (!StringHelper::isNullOrBlank($category) && $category !== "all") {
			$where .= "`{$categoryTable}` = '{$category}'";
		} else if ($category == "null") {
			$where .= "`{$categoryTable}` IS NULL";
		}

		if (!StringHelper::isNullOrBlank($tag)) {
			$tagTable = Mvc::getTablePrefix() . "tags";
			$where[] = "`id` IN (SELECT content_id FROM `{$tagTable}` WHERE `tag` = '{$tag}')";
		}

		try {
			$data = ArticleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$search,
				join(" AND ", $where)
			);
			return $this->_restApiOk($data);
		} catch (\Exception $exception) {
			return $this->_restApiServerError($exception);
		}
	}
}
