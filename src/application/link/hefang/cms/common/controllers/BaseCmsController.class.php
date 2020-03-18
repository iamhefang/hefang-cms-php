<?php


namespace link\hefang\cms\common\controllers;


use link\hefang\cms\HeFangCMS;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\interfaces\SGLD;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

abstract class BaseCmsController extends BaseController implements SGLD
{
	/**
	 * 获取查询条件
	 * @return string
	 */
	public function _query(): string
	{
		return $this->_request(HeFangCMS::queryKey(), "");
	}

	/**
	 * @return string
	 */
	public function _sort(): string
	{
		return $this->_request(HeFangCMS::sortKey(), "");
	}

	public function _pageIndex(int $pageIndex = 1): int
	{
		return $this->_request("pageIndex", $pageIndex);
	}

	public function _pageSize(int $pageSize = null): int
	{
		return $this->_request("pageSize", $pageSize ?: Mvc::getProperty("default.page.size", 10));
	}

	/**
	 * 获取用户登录信息, 若没有用户登录直接响应登录信息到客户端
	 * @param string $message
	 * @return AccountModel
	 */
	public function _checkLogin(string $message = "您未登录或已掉线, 请先登录"): AccountModel
	{
		$login = $this->_getLogin();
		if (!($login instanceof AccountModel)) {
			$this->_restApiUnauthorized($message)->compile()->render();
		}

		return $login;
	}

	/**
	 * 获取当前登录用户, 不会自动返回需要登录信息, 有用户登录返回账户模型, 没有登录返回null
	 * @return AccountModel|null
	 */
	public function _getLogin()
	{
		$authType = strtoupper(Mvc::getProperty("project.auth.type", "SESSION"));
		if ($authType === "SESSION") {
			return $this->_session(AccountModel::ACCOUNT_SESSION_KEY);
		}

		if ($authType === "TOKEN") {
			$token = $this->_header("Authorization");
			if (!StringHelper::isNullOrBlank($token)) {
				return Mvc::getCache()->get($token);
			}
		}
		return null;
	}

	/**
	 * 添加或更新数据
	 * post: 添加数据
	 * put: 全量更新
	 * patch: 局部更新
	 * @method POST
	 * @method PUT
	 * @method PATCH
	 * @param string|null $id
	 * @return BaseView
	 */
	public function set(string $id = null): BaseView
	{
		return $this->_restApiNotImplemented();
	}

	/**
	 * 获取一条数据
	 * @method GET
	 * @param string|null $id 要获取的数据的id
	 * @return BaseView
	 */
	public function get(string $id = null): BaseView
	{
		return $this->_restApiNotImplemented();
	}

	/**
	 * 获取内容列表
	 * @method GET
	 * @param string|null $cmd 自定义参数
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		return $this->_restApiNotImplemented();
	}

	/**
	 * 删除一条数据
	 * @method DELETE
	 * @param string|null $id
	 * @return BaseView
	 */
	public function delete(string $id = null): BaseView
	{
		return $this->_restApiNotImplemented();
	}
}
