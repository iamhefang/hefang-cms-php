<?php


namespace link\hefang\cms\application\admin\controllers;


use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\helpers\FileHelper;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class ThemeController extends BaseCmsController
{
	public function list(string $cmd = null): BaseView
	{
		$login = $this->_checkLockedScreen();
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden();
		}
		$themes = CacheHelper::cacheOrFetch(CacheHelper::KEY_ALL_THEMES, function () {
			$themeDirs = scandir(PATH_THEMES);
			$themeArray = [];
			foreach ($themeDirs as $themeDir) {
				$file = join([PATH_THEMES, $themeDir, "manifest.json"], DS);
				if ($themeDir === "." || $themeDir === ".." || !is_file($file)) continue;
				$theme = json_decode(file_get_contents($file), true);
				$theme["isCurrent"] = Mvc::getConfig("site|theme") === $theme["id"];
				unset($theme["\$schema"]);
				$themeArray[] = $theme;
			}
			return $themeArray;
		});
		return $this->_restApiOk($themes);
	}

	public function delete(string $cmd = null): BaseView
	{
		$login = $this->_checkLockedScreen();
		if (!$login->isSuperAdmin()) return $this->_restApiForbidden();

		if (Mvc::getConfig("site|theme") === $cmd) {
			return $this->_restApiBadRequest("该主题是当前主题，无法删除");
		}

		$themeDir = PATH_THEMES . DS . $cmd;
		if (!file_exists($themeDir)) {
			return $this->_restApiNotFound();
		}
		$res = FileHelper::delete($themeDir);
		Mvc::getCache()->remove(CacheHelper::KEY_ALL_THEMES);
		return $this->_restApiOk($res);
	}
}
