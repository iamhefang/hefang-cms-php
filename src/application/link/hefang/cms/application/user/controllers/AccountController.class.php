<?php


namespace link\hefang\cms\application\user\controllers;


use Exception;
use link\hefang\cms\application\admin\models\MenuModel;
use link\hefang\cms\application\user\models\AccountModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\helpers\HashHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class AccountController extends BaseCmsController
{

//	public function initRoot(): BaseView
//	{
//		$root = new AccountModel();
//		$root->setId("root")
//			->setName("Administrator")
//			->setRegisterTime(TimeHelper::formatMillis())
//			->setRegisterType("System Init")
//			->setPassword(HashHelper::passwordHash(sha1("111111") . md5("111111"), "(*lfwekjfO*gklswjefwFDSefwgwlekjf"))
//			->setRoleId("admin");
//		return $this->_text($root->insert());
//	}

	/**
	 * 系统心跳, 用于在锁屏时保持在线和通知消息获取
	 * @return BaseView
	 */
	public function heartbeat(): BaseView
	{
		$login = $this->_checkLogin();

		return $this->_restApiOk([]);
	}

	/**
	 * @param string $lock
	 * lock: 锁屏
	 * unlock: 解锁
	 * @return BaseView
	 */
	public function screen(string $lock): BaseView
	{
		if (!$lock || !in_array(strtolower($lock), ["lock", "unlock"])) {
			return $this->_restApiBadRequest("接口不存在");
		}
		$login = $this->_checkLogin();
		$pwd = $this->_post("password");

		if ($lock == "unlock") {
			if (StringHelper::isNullOrBlank($pwd) || strlen($pwd) !== 72) {
				return $this->unlockFailed($login);
			}
			$isMatchLockPwd = $login->getScreenLockPassword() && HashHelper::passwordVerify($pwd, $login->getScreenLockPassword(), Mvc::getPasswordSalt());
			if ($isMatchLockPwd
				|| HashHelper::passwordVerify($pwd, $login->getPassword(), Mvc::getPasswordSalt())) {
				$login->setUnLockTries(0)->setIsLockedScreen(false)->updateSession($this);
				return $this->_restApiOk($login);
			} else {
				return $this->unlockFailed($login);
			}
		}

		if (!StringHelper::isNullOrEmpty($pwd)) {
			if (strlen($pwd) === 72) {
				$login->setScreenLockPassword(HashHelper::passwordHash($pwd, Mvc::getPasswordSalt()));
			} else {
				return $this->_restApiBadRequest();
			}
		}
		$login
			->setIsLockedScreen(strtolower($lock) === "lock")
			->updateSession($this);

		try {
			$login->update(["screen_lock_password"]);
		} catch (Exception $e) {
		}

		return $this->_restApiOk($login);
	}

	private function unlockFailed(AccountModel $login): BaseView
	{
		$login->setUnLockTries($login->getUnLockTries() + 1);
		if ($login->getUnLockTries() >= AccountModel::MAX_UNLOCK_TRY) {
			$login->logout();
			return $this->_restApiUnauthorized("重试次数过多, 请重新登录");
		}
		$login->updateSession($this);
		return $this->_restApiBadRequest("解锁失败, 您还可以重试" . (AccountModel::MAX_UNLOCK_TRY - $login->getUnLockTries()) . "次");
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
			$login->logout();
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
