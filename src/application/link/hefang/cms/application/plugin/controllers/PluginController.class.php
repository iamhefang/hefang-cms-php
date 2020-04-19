<?php


namespace link\hefang\cms\application\plugin\controllers;


use link\hefang\cms\application\admin\models\SettingModel;
use link\hefang\cms\application\plugin\models\PluginModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\cms\core\exceptions\PluginException;
use link\hefang\helpers\FileHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\ZipHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;
use Throwable;

class PluginController extends BaseCmsController
{
	public function list(string $cmd = null): BaseView
	{
		$login = $this->_checkLogin();
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden();
		}

		try {
			return $this->_restApiOk(PluginModel::pager(
				$this->_pageIndex(), $this->_pageSize(),
				PluginModel::query2sql($this->_query()),
				PluginModel::sort2sql($this->_sort())
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}

	public function init(string $id): BaseView
	{
		$this->_checkLockedScreen();
		try {
			$model = PluginModel::get($id);
			if (!($model instanceof PluginModel) || !$model->isExist()) {
				return $this->_restApiNotFound("该插件还未安装或已被卸载");
			}
			$result = [
				"settings" => SettingModel::initPluginSettings($model),
				"install" => null,
				"upgrade" => null,
			];
			if ($model->getStatus() === PluginModel::STATUS_WAIT_FOR_INSTALL) {
				$script = $model->getScripts();
				if (isset($script["install"]) && is_file($script["install"])) {
					$result["install"] = @include $script["install"];
				}
			}

			return $this->_restApiBadRequest("该插件现状不能执行初始化操作");
		} catch (Throwable $exception) {
			return $this->_restApiServerError($exception);
		}
	}

	/**
	 * 安装插件
	 * @method POST
	 * @param string $type
	 * @return BaseView
	 */
	public function install(string $type): BaseView
	{
		$type = strtolower($type);
		if (!in_array($type, ["archive", "url"])) {
			return $this->_restApiNotFound("访问的接口不存在");
		}
		$login = $this->_checkLogin();
		if (!is_writeable(HEFANG_CMS_PLUGINS)) {
			return $this->_restApiForbidden("服务器插件目录不可写，无法安装插件");
		}
		if ($type === "archive") {
			$file = $_FILES["file"];
			try {

				if (StringHelper::endsWith($file["name"], true, ".zip")) {
					$model = $this->parseZipFile($file["tmp_name"]);
				} elseif (StringHelper::endsWith($file["name"], true, ".phar")) {
					$model = $this->parsePharFile($file["tmp_name"]);
				} else {
					return $this->_restApiBadRequest("包格式不正确");
				}
				$model->setStatus(PluginModel::STATUS_WAIT_FOR_INSTALL)->insert();
				return $this->_restApiOk($model);
			} catch (PluginException $exception) {
				return $this->_restApiBadRequest($exception->getMessage());
			} catch (Throwable $exception) {
				return $this->_restApiServerError($exception);
			}
		}
		return $this->_restApiNotImplemented();
	}

	/**
	 * @param $tmpFile
	 * @return PluginModel
	 * @throws PluginException
	 */
	private function parseZipFile($tmpFile): PluginModel
	{
		$tmpDir = sys_get_temp_dir() . DS . "hefang-cms-plugin-cache" . DS . RandomHelper::guid();
		$dirLength = strlen(HEFANG_CMS_PLUGINS . DS);
		mkdir($tmpDir, 777, true);
		$res = ZipHelper::unCompress($tmpFile, $tmpDir);
		if (!$res) {
			throw new PluginException("插件包解析失败:" . $res);
		}
		$manifestFile = $tmpDir . DS . "manifest.json";
		if (!is_file($manifestFile)) {
			throw new PluginException("插件包无效");
		}
		$model = PluginModel::fromJson(file_get_contents($manifestFile));
		if (!in_array(HEFANG_CMS, $model->getSupportVersion()) && !in_array("*", $model->getSupportVersion())) {
			throw new PluginException("上传的插件包不支持当前CMS版本");
		}
		rename($tmpDir, HEFANG_CMS_PLUGINS . DS . $model->getId());
		$files = FileHelper::listFiles(HEFANG_CMS_PLUGINS . DS . $model->getId());
		$installedFiles = [];
		foreach ($files as $file) {
			$installedFiles[substr($file, $dirLength)] = md5_file($file);
		}
		$model->setInstallFiles($installedFiles);
		return $model;
	}

	/**
	 * @param $tmpFile
	 * @return PluginModel
	 * @throws PluginException
	 */
	private function parsePharFile($tmpFile): PluginModel
	{
		$dirLength = strlen(HEFANG_CMS_PLUGINS . DS);
		$manifestFile = "phar://" . $tmpFile . "/manifest.json";
		$model = PluginModel::fromJson(file_get_contents($manifestFile));
		if (!in_array(HEFANG_CMS, $model->getSupportVersion()) && !in_array("*", $model->getSupportVersion())) {
			throw new PluginException("上传的插件包不支持当前CMS版本");
		}
		$pluginFile = HEFANG_CMS_PLUGINS . DS . $model->getId() . ".phar";
		move_uploaded_file($tmpFile, $pluginFile);
		$installedFiles[substr($pluginFile, $dirLength)] = md5_file($pluginFile);
		$model->setInstallFiles($installedFiles);
		return $model;
	}
}
