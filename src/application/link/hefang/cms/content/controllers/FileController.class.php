<?php


namespace link\hefang\cms\content\controllers;


use Exception;
use link\hefang\cms\content\models\FileModel;
use link\hefang\guid\GUKey;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\ParseHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\interfaces\IDULG;
use link\hefang\mvc\views\BaseView;

class FileController extends BaseController implements IDULG
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
	 * @return BaseView
	 */
	public function insert(): BaseView
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
			return $model->insert() ? $this->_restApiOk($model) : $this->_restFailedUnknownReason();
		} catch (Exception $e) {
			return $this->_restApiServerError($e, "保存文件信息时出错");
		}
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
		// TODO: Implement list() method.
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
