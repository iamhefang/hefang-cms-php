<?php


namespace link\hefang\cms\application\image\controllers;


use link\hefang\cms\application\image\helpers\ImageHelper;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class CaptchaController extends BaseCmsController
{
	public static function verify(string $answer): bool
	{

	}

	public function english(): BaseView
	{
		return $this->_makeImage(RandomHelper::letter(5));
	}

	private function _makeImage(string $captcha): BaseView
	{
		$format = $this->getRouter()->getFormat();
		if ($format !== "png") return $this->_restApiNotFound();
		if (!extension_loaded("gd")) {
			return $this->_restApiNotImplemented("服务器环境依赖不足");
		}
		$id = RandomHelper::guid();
		setcookie("captchaId", $id, time() + 300);
		Mvc::getCache()->set($id, $captcha, TimeHelper::currentTimeMillis() + 300000);
		$img = ImageHelper::captcha($captcha);
		return $this->_image($img);
	}

	public function chinese(): BaseView
	{
		return $this->_makeImage(RandomHelper::string(4, "我人有的和主产不为这工要在地一上是中国经以发了民同"));
	}
}
