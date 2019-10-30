<?php


namespace link\hefang\cms\users\controllers;


use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class AuthController extends BaseController
{
	public function login(): BaseView
	{
		$name = $this->_post("name");
		$password = $this->_post("password");
		$captcha = $this->_post("captcha");

		if ($password !== "111111") {
			return $this->_restApiBadRequest("密码错误");
		}

		$login = [
			"id" => md5($name . $password),
			"name" => $name,
			"avatar" => "delete",
			"token" => RandomHelper::guid()
		];
		Mvc::getCache()->set($login["token"], $login);
		return $this->_restApiOk($login);
	}

	public function current(): BaseView
	{
		$token = $this->_header("HeFang-CMS-Token");
		if (StringHelper::isNullOrBlank($token)) {
			return $this->_restApiUnauthorized();
		}
		$login = Mvc::getCache()->get($token);
		return $login ? $this->_restApiOk($login) : $this->_restApiUnauthorized();
	}
}
