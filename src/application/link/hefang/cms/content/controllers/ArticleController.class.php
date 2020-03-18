<?php


namespace link\hefang\cms\content\controllers;

use Exception;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\content\models\ArticleModel;
use link\hefang\cms\content\models\ContentTagModel;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\guid\GUKey;
use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use Throwable;

class ArticleController extends BaseCmsController
{
	/**
	 * 添加或更新数据
	 * @method POST
	 * @method PUT
	 * @method PATCH
	 * @param string|null $id
	 * @return BaseView
	 */
	public function set(string $id = null): BaseView
	{
		$login = $this->_checkLogin();
		$method = $this->_method();
		$data = $this->_post();
		if ($method == "POST") {
			$key = new GUKey(ArticleModel::class);
			$model = new ArticleModel();
			try {
				foreach ($data as $prop => $value) {
					$model->setValue2Prop($value, $prop);
				}
				$res = $model->setId($key->next())
					->setAuthorId($login->getId())
					->setPostTime(TimeHelper::formatMillis())
					->insert();
				if ($res && is_array($data["tags"])) {
					$res = $this->saveTags($model, $data["tags"]);
				}
				return $res ? $this->_restApiCreated($res) : $this->_restFailedUnknownReason($res);
			} catch (Throwable $e) {
				return $this->_restApiServerError($e);
			}
		}

		$id = $this->_request("id", $id);
		if (!GUKey::isGuKey($id)) {
			return $this->_restApiBadRequest();
		}
		try {
			$model = ArticleModel::get($id);
			if (!($model instanceof ArticleModel) || !$model->isExist()) {
				return $this->_restApiNotFound();
			}
			if (!$login->isAdmin() && $login->getId() !== $model->getId()) {
				return $this->_restApiForbidden("您无权修改该内容");
			}
			foreach ($data as $prop => $value) {
				$model->setValue2Prop($value, $prop);
			}
			$res = $model->setLastAlterTime(TimeHelper::formatMillis())
				->update([
					"category_id",
					"extra",
					"content",
					"keywords",
					"description",
					"title",
					"path",
					"is_draft"
				]);
			if (is_array($data["tags"])) {
				$res = $this->saveTags($model, $data["tags"]);
			}
			return $res ? $this->_restApiOk($res) : $this->_restFailedUnknownReason($res);
		} catch (Throwable $e) {
			return $this->_restApiServerError($e);
		}
	}

	private function saveTags(ArticleModel $model, array $tags): int
	{
		return ContentTagModel::saveTag($model->getId(), $model->getType() ?: "article", $tags);
	}

	/**
	 * 获取一条数据
	 * @method GET
	 * @param string|null $id 要获取的数据的id
	 * @return BaseView
	 */
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

	/**
	 * 获取内容列表
	 * @method GET
	 * @param string|null $cmd 自定义参数
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		$login = $this->_getLogin();
		$category = $this->_request("category");
		$tag = $this->_request("tag");
		$whereArr = [];

		if ($login instanceof AccountModel) {
			if ($login->isAdmin()) {
				$showDisabled = $this->_request("showDisabled");
				$author = $this->_request("author");
				if (!StringHelper::isNullOrBlank($showDisabled)) {
					$showDisabled = ParseHelper::parseBoolean($showDisabled);
					if (!$showDisabled) {
						$whereArr[] = "enable = TRUE";
					}
				}
				if (!StringHelper::isNullOrBlank($author) && strlen($author) === 40) {
					$whereArr[] = "`author` = '{$author}'";
				}
			} else {
				$whereArr[] = "`author`='{$login->getId()}'";
			}
		} else {
			$whereArr[] = "enable = TRUE AND is_draft = FALSE";
		}

		$categoryTable = Mvc::getTablePrefix() . "category";
		if (!StringHelper::isNullOrBlank($category) && $category !== "all") {
			$whereArr .= "`{$categoryTable}` = '{$category}'";
		} else if ($category == "null") {
			$whereArr .= "`{$categoryTable}` IS NULL";
		}

		if (!StringHelper::isNullOrBlank($tag)) {
			$tagTable = Mvc::getTablePrefix() . "tags";
			$whereArr[] = "`id` IN (SELECT `content_id` FROM `{$tagTable}` WHERE `tag` = '{$tag}' AND `type`='article')";
		}

		$where = join(" AND ", $whereArr);
		$query = ArticleModel::query2sql($this->_query());

		if ($query) {
			$where = $where ? "($query) AND ($where)" : $query;
		}

		try {
			$data = ArticleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$where,
				ArticleModel::sort2sql($this->_sort())
			);
			return $this->_restApiOk($data);
		} catch (Exception $exception) {
			return $this->_restApiServerError($exception);
		}
	}

	/**
	 * 删除一条数据
	 * @method DELETE
	 * @method POST
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function delete(string $cmd = null): BaseView
	{
		$ids = $this->_post("ids");
		if (!is_array($ids) || !in_array($cmd, ["recycle", "destroy", "restore"])) {
			return $this->_restApiBadRequest();
		}
		if (
			($cmd === "restore" && $this->_method() !== "POST") ||
			($cmd !== "restore" && $this->_method() === "POST")
		) {
			return $this->_restApiMethodNotAllowed();
		}
		try {
			$where = "`id` IN ('" . join("','", $ids) . "')";
			if ($cmd === "destroy") {
				$res = ArticleModel::database()->delete(ArticleModel::table(), $where);
			} else {
				$data = ["enable" => $cmd === "restore"];
				if ($cmd === "restore") {
					$data["is_draft"] = true;
				}
				$res = ArticleModel::database()->update(ArticleModel::table(), $data, $where);
			}
			return $this->_restApiOk($res);
		} catch (Throwable $e) {
			return $this->_restApiServerError($e);
		}
	}
}
