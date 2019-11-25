<?php


namespace link\hefang\cms\admin\controllers;


use link\hefang\cms\admin\models\FunctionModel;
use link\hefang\guid\GUKey;
use link\hefang\helpers\CollectionHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class FunctionController extends BaseController
{
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
			$sqls[] = new Sql("insert into `function`(`id`,`name`,`path`,`icon`,`sort`) values (:id,:name,:path,:icon,:sort)", [
				'id' => $id,
				'name' => $item['name'],
				'icon' => $item['icon'],
				'path' => $item['path'],
				'sort' => CollectionHelper::getOrDefault($item, 'sort', 0)
			]);
			if (is_array(CollectionHelper::getOrDefault($item, 'children'))) {
				foreach ($item['children'] as $item) {
					$sqls[] = new Sql("insert into `function`(`id`,`name`,`path`,`icon`,`sort`,`parent_id`) values (:id,:name,:path,:icon,:sort,:parent_id)", [
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
		return $this->_text(FunctionModel::database()->transaction($sqls));
	}

	public function list(): BaseView
	{

	}
}
