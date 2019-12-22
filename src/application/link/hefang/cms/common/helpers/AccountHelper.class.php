<?php


namespace link\hefang\cms\common\helpers;


use link\hefang\cms\user\models\AccountModel;
use link\hefang\mvc\controllers\BaseController;

class AccountHelper
{
	public static function checkLogin(BaseController $controller, string $message = "需要登录"): AccountModel
	{
		$user = $controller->_getLogin();
		if (!($user instanceof AccountModel)) {
			$controller->_restApiUnauthorized($message)->compile()->render();
		}
		return $user;
	}
}
