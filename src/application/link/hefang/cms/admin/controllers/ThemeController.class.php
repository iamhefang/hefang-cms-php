<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\common\helpers\CacheHelper;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class ThemeController extends BaseCmsController
{
	public function list(string $cmd = null): BaseView
	{
		$themes = CacheHelper::cacheOrFetch("themes", function () {
			$themeDirs = scandir(PATH_THEMES);
			$themeArray = [];
			foreach ($themeDirs as $themeDir) {
				$file = join([PATH_THEMES, $themeDir, "manifest.json"], DS);
				if ($themeDir === "." || $themeDir === ".." || !is_file($file)) continue;
				$theme = json_decode(file_get_contents($file), true);
				$theme["isCurrent"] = Mvc::getConfig("site|theme") === $theme["id"];
				$themeArray[] = $theme;
			}
			return $themeArray;
		});
		return $this->_restApiOk($themes);
	}
}
