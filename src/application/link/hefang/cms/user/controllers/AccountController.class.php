<?php


namespace link\hefang\cms\user\controllers;


use link\hefang\cms\admin\models\MenuModel;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\helpers\HashHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class AccountController extends BaseCmsController
{

	public function initRoot(): BaseView
	{
		$root = new AccountModel();
		$root->setId("root")
			->setName("Administrator")
			->setRegisterTime(TimeHelper::formatMillis())
			->setRegisterType("System Init")
			->setPassword(HashHelper::passwordHash(sha1("111111") . md5("111111"), "(*lfwekjfO*gklswjefwFDSefwgwlekjf"))
			->setRoleId("admin");
		return $this->_text($root->insert());
	}

	/**
	 * 登录接口
	 * @return BaseView
	 */
	public function login(): BaseView
	{
		$name = $this->_post("name");
		$password = $this->_post("password");
		$captcha = $this->_post("captcha");
		if (StringHelper::isNullOrBlank($name)) {
			return $this->_restApiBadRequest("用户名不能为空");
		}
		if (strlen($password) !== 72) {
			return $this->_restApiBadRequest("参数异常");
		}
		try {
			$user = AccountModel::find("name='{$name}'");
			if (!($user instanceof AccountModel) || !$user->isExist()) {
				return $this->_restApiNotFound("用户不存在");
			}
			if (!HashHelper::passwordVerify($password, $user->getPassword(), Mvc::getPasswordSalt())) {
				return $this->_restApiBadRequest("用户名和密码不匹配");
			}
			if (!$user->isSuperAdmin() && $user->isLocked()) {
				return $this->_restApiForbidden("该用户已于{$user->getLockedTime()}锁定， 无法登录");
			}
			$user->login($this);
			return $this->_restApiOk($user);
		} catch (ModelException $e) {
			return $this->_restApiServerError($e, "解析用户信息时出现异常");
		} catch (SqlException $e) {
			return $this->_restApiServerError($e, "读取用户信息时出现异常");
		}
	}

	public function logout(): BaseView
	{
		$login = $this->_checkLogin();
		if ($login instanceof AccountModel) {
			$login->logout($this);
		}
		return $this->_restApiOk();
	}

	/**
	 * 获取当前登录用户
	 * @return BaseView
	 */
	public function current(): BaseView
	{
		return $this->_restApiOk($this->_checkLogin());
	}

	/**
	 * 获取当前登录用户的菜单
	 * @return BaseView
	 */
	public function menus(): BaseView
	{
		$this->_checkLogin();
		return $this->_restApiOk(MenuModel::all(true));
	}
}
