<?php


namespace link\hefang\cms\admin\controllers;


use Exception;
use link\hefang\cms\admin\models\MenuModel;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\user\models\AccountModel;
use link\hefang\cms\user\models\RoleModel;
use link\hefang\guid\GUKey;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class MenuController extends BaseCmsController
{
	protected function modelClass(): string
	{
		return MenuModel::class;
	}

	public function list(string $cmd = null): BaseView
	{
		try {
			$pager = MenuModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				"enable = TRUE",
				MenuModel::sort2sql($this->_sort())
			);
			return $this->_restApiOk($pager);
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}

	public function set(string $id = null): BaseView
	{
		$login = $this->_getLogin();
		if (!($login instanceof AccountModel)) {
			return $this->_restApiUnauthorized();
		}
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden("该功能只有管理员才能使用");
		}
		$id = $this->_post("id");
		$parentId = $this->_post("parentId");
		$name = $this->_post("name");
		$path = $this->_post("path");
		$icon = $this->_post("icon");
		$sort = $this->_post("sort");
		try {
			$model = new MenuModel();
			$model->setId((new GUKey("menu"))->next());
			if (GUKey::isGuKey($id)) {
				$model = MenuModel::get($id);
				if (!($model instanceof MenuModel) || !$model->isExist()) {
					return $this->_restApiNotFound("要修改的菜单不存在或已被删除");
				}
			}
			$model->setName($name)
				->setParentId($parentId)
				->setPath($path)
				->setIcon($icon)
				->setSort($sort);
			$res = GUKey::isGuKey($id) ? $model->update(["name", "path", "icon", "sort", "parent_id"]) : $model->insert();
			if ($res) {
				Mvc::getCache()->remove("all-menus");
			}
			return $res ? $this->_restApiOk() : $this->_restNotModified();
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}

	public function all(): BaseView
	{
		$this->_checkLogin();
		try {
			return $this->_restApiOk(MenuModel::all());
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}

	public function init(): BaseView
	{
		$sqls = [];
		$items = json_decode(
			"[{\"path\":\"/\",\"name\":\"工作台\",\"icon\":\"setting\",\"sort\":0},{\"path\":\"/user/\",\"name\":\"用户中心\",\"icon\":\"team\",\"sort\":1,\"children\":[{\"path\":\"/user/users.html\",\"name\":\"用户列表\",\"icon\":\"user\"},{\"path\":\"/user/roles.html\",\"name\":\"角色管理\",\"icon\":\"user\"},{\"path\":\"/user/menus.html\",\"name\":\"菜单管理\",\"icon\":\"user\"},{\"path\":\"/user/profile.html\",\"name\":\"个人中心\",\"icon\":\"user\",\"sort\":0},{\"path\":\"/user/departments.html\",\"name\":\"部门管理\",\"icon\":\"user\"},{\"path\":\"/user/statistics.html\",\"name\":\"用户统计\",\"icon\":\"user\"}]},{\"path\":\"/content/\",\"name\":\"内容管理\",\"icon\":\"setting\",\"sort\":2,\"children\":[{\"path\":\"/content/article/editor.html\",\"name\":\"新建文章\",\"icon\":\"setting\"},{\"path\":\"/content/article/list.html\",\"name\":\"文章管理\",\"icon\":\"setting\"},{\"path\":\"/content/tags/list.html\",\"name\":\"标签管理\",\"icon\":\"setting\"},{\"path\":\"/content/categories/list.html\",\"name\":\"分类管理\",\"icon\":\"setting\"},{\"path\":\"/content/comments/list.html\",\"name\":\"评论管理\",\"icon\":\"setting\"},{\"path\":\"/content/files/list.html\",\"name\":\"文件管理\",\"icon\":\"setting\"},{\"path\":\"/content/statistics.html\",\"name\":\"数据统计\",\"icon\":\"setting\"}]},{\"path\":\"/setting/\",\"name\":\"系统设置\",\"icon\":\"setting\",\"sort\":1000,\"children\":[{\"path\":\"/setting/modules.html\",\"name\":\"功能管理\",\"icon\":\"setting\"},{\"path\":\"/setting/plugins.html\",\"name\":\"插件管理\",\"icon\":\"setting\"},{\"path\":\"/setting/themes.html\",\"name\":\"主题管理\",\"icon\":\"setting\"},{\"path\":\"/setting/configs.html\",\"name\":\"配置中心\",\"icon\":\"setting\"}]}]",
			true
		);
		Mvc::getLogger()->log(print_r($items, true));
		$key = new GUKey("hefang-cms-function");
		foreach ($items as $item) {
			$id = $key->next();
			$sqls[] = new Sql("insert into `menu`(`id`,`name`,`path`,`icon`,`sort`) values (:id,:name,:path,:icon,:sort)", [
				'id' => $id,
				'name' => $item['name'],
				'icon' => $item['icon'],
				'path' => $item['path'],
				'sort' => CollectionHelper::getOrDefault($item, 'sort', 0)
			]);
			if (is_array(CollectionHelper::getOrDefault($item, 'children'))) {
				foreach ($item['children'] as $item) {
					$sqls[] = new Sql("insert into `menu`(`id`,`name`,`path`,`icon`,`sort`,`parent_id`) values (:id,:name,:path,:icon,:sort,:parent_id)", [
						'id' => $key->next(),
						'parent_id' => $id,
						'name' => $item['name'],
						'icon' => $item['icon'],
						'path' => $item['path'],
						'sort' => CollectionHelper::getOrDefault($item, 'sort', 0)
					]);
				}
			}
		}
		return $this->_text(MenuModel::database()->transaction($sqls));
	}

	public function bind(): BaseView
	{
		$login = $this->_getLogin();
		if (!($login instanceof AccountModel)) {
			return $this->_restApiUnauthorized();
		}
		if (!$login->isAdmin()) {
			return $this->_restApiForbidden("该功能只能管理员才能使用");
		}
		$roleId = $this->_request("roleId");
		$menuIds = $this->_post("menuIds");
		if (StringHelper::isNullOrBlank($roleId) || !is_array($menuIds)) {
			return $this->_restApiBadRequest();
		}
		try {
			$role = RoleModel::get($roleId);
			if (!($role instanceof RoleModel) || !$role->isExist() || !$role->isEnable()) {
				return $this->_restApiNotFound("要绑定菜单的角色不存在或已被禁用");
			}
			return $role->bindMenus($menuIds) ? $this->_restApiOk() : $this->_restNotModified();
		} catch (Exception $e) {
			return $this->_restApiServerError($e);
		}
	}
}
