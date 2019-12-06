<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\common\helpers\CacheHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

class ThemeController extends BaseController
{
	public function list(): BaseView
	{
		$themes = CacheHelper::cacheOrFetch("themes", function () {
			$themeDirs = scandir(PATH_THEMES);
			$themeArray = [];
			foreach ($themeDirs as $themeDir) {
				$file = join([PATH_THEMES, $themeDir, "manifest.json"], DS);
				if ($themeDir === "." || $themeDir === ".." || !is_file($file)) continue;
				$themeArray[] = json_decode(file_get_contents($file));
			}
			return $themeArray;
		});
		return $this->_restApiOk($themes);
	}
}
