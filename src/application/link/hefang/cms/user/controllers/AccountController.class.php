<?php


namespace link\hefang\cms\user\controllers;


use link\hefang\cms\user\models\AccountModel;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\models\BaseLoginModel;
use link\hefang\mvc\views\BaseView;

class AccountController extends BaseController
{
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

	public function current(): BaseView
	{
		$login = $this->_getLogin();
		return $login instanceof BaseLoginModel ? $this->_restApiOk($login) : $this->_restApiUnauthorized();
	}

	public function menus(): BaseView
	{
		$login = $this->_getLogin();
		return $this->_restApiOk(json_decode("[
        {
          path: \"/\",
          name: \"工作台\",
          icon: \"setting\",
          sort: 0
        },
        {
          path: \"/user/\",
          name: \"用户中心\",
          icon: \"team\",
          sort: 1,
          children: [
            {
              path: \"/user/users.html\",
              name: \"用户列表\",
              icon: \"user\"
            },
            {
              path: \"/user/roles.html\",
              name: \"角色管理\",
              icon: \"user\"
            },
            {
              path: \"/user/menus.html\",
              name: \"菜单管理\",
              icon: \"user\"
            },
            {
              path: \"/user/profile.html\",
              name: \"个人中心\",
              icon: \"user\",
              sort: 0
            },
            {
              path: \"/user/departments.html\",
              name: \"部门管理\",
              icon: \"user\"
            },
            {
              path: \"/user/statistics.html\",
              name: \"用户统计\",
              icon: \"user\"
            }
          ]
        },
        {
          path: \"/content/\",
          name: \"内容管理\",
          icon: \"setting\",
          sort: 2,
          children: [
            {
              path: \"/content/article/editor.html\",
              name: \"新建文章\",
              icon: \"setting\",
            },
            {
              path: \"/content/article/list.html\",
              name: \"文章管理\",
              icon: \"setting\",
            },
            {
              path: \"/content/tags/list.html\",
              name: \"标签管理\",
              icon: \"setting\",
            },
            {
              path: \"/content/categories/list.html\",
              name: \"分类管理\",
              icon: \"setting\",
            },
            {
              path: \"/content/comments/list.html\",
              name: \"评论管理\",
              icon: \"setting\",
            },
            {
              path: \"/content/files/list.html\",
              name: \"文件管理\",
              icon: \"setting\",
            },
            {
              path: \"/content/statistics.html\",
              name: \"数据统计\",
              icon: \"setting\",
            }
          ]
        },
        {
          path: \"/setting/\",
          name: \"系统设置\",
          icon: \"setting\",
          sort: 1000,
          children: [
            {
              path: \"/setting/modules.html\",
              name: \"功能管理\",
              icon: \"setting\"
            },
            {
              path: \"/setting/plugins.html\",
              name: \"插件管理\",
              icon: \"setting\"
            },
            {
              path: \"/setting/themes.html\",
              name: \"主题管理\",
              icon: \"setting\"
            },
            {
              path: \"/setting/configs.html\",
              name: \"配置中心\",
              icon: \"setting\"
            }
          ]
        }
      ]"));
	}
}
