<?php


namespace link\hefang\cms\user\controllers;


use link\hefang\cms\admin\models\FunctionModel;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\models\BaseLoginModel;
use link\hefang\mvc\views\BaseView;

class AccountController extends BaseController
{
	/**
	 * 登录接口
	 * @return BaseView
	 */
	public function login(): BaseView
	{
		$name = $this->_post("name");
		$password = $this->_post("password");
		$captcha = $this->_post("captcha");

		if ($password !== "111111") {
			return $this->_restApiBadRequest("密码错误");
		}

		$model = new AccountModel();
		$model->setId("root")
			->setName($name)
			->setToken()
			->login($this);
		return $this->_restApiOk($model);
	}

	/**
	 * 获取当前登录用户
	 * @return BaseView
	 */
	public function current(): BaseView
	{
		$login = $this->_getLogin();
		return $login instanceof BaseLoginModel ? $this->_restApiOk($login) : $this->_restApiUnauthorized();
	}

	/**
	 * 获取当前登录用户的菜单
	 * @return BaseView
	 */
	public function menus(): BaseView
	{
//		$login = $this->_getLogin();

		return $this->_restApiOk(FunctionModel::all());
	}
}
