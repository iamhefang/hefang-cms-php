<?php


namespace link\hefang\cms\application\content\controllers;


use Exception;
use link\hefang\cms\application\content\models\ContentTagModel;
use link\hefang\cms\application\content\models\FileModel;
use link\hefang\cms\application\user\models\AccountModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\cms\HeFangCMS;
use link\hefang\guid\GUKey;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class FileController extends BaseCmsController
{
	const ERROR_MAP = [
		UPLOAD_ERR_INI_SIZE => "上传文件过大: 超过PHP限制",
		UPLOAD_ERR_FORM_SIZE => "上传文件过大：超过表单限制",
		UPLOAD_ERR_PARTIAL => "文件未上传完整",
		UPLOAD_ERR_NO_FILE => "没有文件被上传",
		UPLOAD_ERR_NO_TMP_DIR => "找不到临时文件夹",
		UPLOAD_ERR_CANT_WRITE => "文件写入失败"
	];

	/**
	 * 文件上传， 一次上传一个文件
	 * @param string|null $id
	 * @return BaseView
	 */
	public function set(string $id = null): BaseView
	{
		$name = $this->_post("name");
		$tags = $this->_post("tags");
		$isPublic = ParseHelper::parseBoolean($this->_post("isPublic"), true);

		if (empty($_FILES)) {
			return $this->_restApiBadRequest("未解析到文件");
		}

		$key = new GUKey(FileModel::class);
		$file = array_values($_FILES)[0];

		$tmpFile = $file["tmp_name"];

		if ($tmpFile["error"] !== UPLOAD_ERR_OK) {
			return $this->_restApiBadRequest(CollectionHelper::getOrDefault(self::ERROR_MAP, $tmpFile["error"], "未知原因"));
		}

		$ext = CollectionHelper::last(explode(".", $tmpFile), "");
		$hash = sha1_file($file["tmp_name"]);
		$uploadFile = PATH_DATA . DS . "files" . DS . $hash . "." . $ext;

		if (!move_uploaded_file($file["tmp_name"], $uploadFile)) {
			return $this->_restApiBadRequest("保存文件出错");
		}

		$model = new FileModel();
		$model->setId($key->next())
			->setName($name)
			->setFileName($file["name"])
			->setType($file["type"])
			->setSize($file["size"])
			->setIsPublic($isPublic)
			->setHash("sha1." . $hash)
			->setEnable(true);
		try {
			$tagTable = ContentTagModel::table();
			if (is_array($tags) && count($tags) > 0) {
				$sqls = [];
				foreach ($tags as $tag) {
					$sqls[] = new Sql(
						"INSERT INTO `{$tagTable}`(`content_id`,`tag`,`type`) values (:id,:tag,'file')",
						[
							"id" => $model->getId(),
							"tag" => $tag
						]
					);
				}
				ContentTagModel::database()->transaction($sqls);
			}
			return $model->insert() ? $this->_restApiOk($model) : $this->_restFailedUnknownReason();
		} catch (Exception $e) {
			return $this->_restApiServerError($e, "保存文件信息时出错");
		}
	}

	/**
	 * 删除数据
	 * @param string|null $id
	 * @return BaseView
	 */
	public function delete(string $id = null): BaseView
	{
		$user = $this->_checkLogin();
		$ids = $this->_request("ids");
		if (!is_array($ids)) {
			return $this->_restApiBadRequest();
		}
		if (!$user->isAdmin() && count($ids) > 1) {
			return $this->_restApiForbidden("您无权批量删除文件");
		}
		$id = "'" . join("','", $ids);
		try {
			if (!$user->isAdmin()) {
				$model = FileModel::get($ids[0]);
			}
			$result = FileModel::database()->update(FileModel::table(), ["enable" => false], "id IN ({$id})");
			return $result ? $this->_restApiOk($result) : $this->_restNotModified();
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}


	/**
	 * 查询数据列表
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		$search = $this->_request(HeFangCMS::queryKey());
		$tag = $this->_request("tag");
		$type = $this->_request("type");
		$user = $this->_getLogin();
		$where = "enable = TRUE";
		if (!($user instanceof AccountModel)) {
			return $this->_restApiUnauthorized();
		}
		if ($user->isAdmin()) {
			$uploaderId = $this->_request("uploaderId");
		} else {
			$uploaderId = $user->getId();
		}

		if (!StringHelper::isNullOrBlank($tag)) {
			$tagTable = ContentTagModel::table();
			$where .= " AND id IN (SELECT content_id FROM `{$tagTable}` WHERE `tag`='{$tag}' AND `type` = 'file')";
		}

		if (!StringHelper::isNullOrBlank($type)) {
			$where .= " AND `type` = '{$type}'";
		}

		if (!StringHelper::isNullOrBlank($uploaderId)) {
			$where .= " AND uploader_id = '{$uploaderId}'";
		}
		try {
			return $this->_restApiOk(FileModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$where,
				FileModel::sort2sql($this->_sort())
			));
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
		$type = strtolower($this->_request("type", "model"));
		try {
			$model = FileModel::get($id);
			if (!($model instanceof FileModel) || !$model->isExist() || !$model->isEnable()) {
				return $this->_restApiNotFound("请求的文件不存在或已被删除");
			}

			$user = $this->_getLogin();
			if (!$model->isIsPublic()) {
				if (!($user instanceof AccountModel)) {
					return $this->_restApiUnauthorized();
				}
				if (!$user->isAdmin() && $user->getId() !== $model->getUploaderId()) {
					return $this->_restApiForbidden("您无权访问该文件");
				}
			}

			if ($type === "model") {
				return $this->_restApiOk($model);
			}
			return $this->_file($model->getSavePath(), $model->getType());
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}
}
