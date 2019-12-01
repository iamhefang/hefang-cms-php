<?php


namespace link\hefang\cms\user\models;


use link\hefang\cms\admin\models\MenuModel;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class RoleModel extends BaseModel
{
	private $id = "";
	private $parentId = null;
	private $name = "";
	private $description = null;
	private $enable = false;
	private $menus = [];

	/**
	 * @return array
	 */
	public function getMenus(): array
	{
		return $this->menus;
	}

	/**
	 * @param array $menuIds
	 * @return bool
	 * @throws SqlException
	 */
	public function bindMenus(array $menuIds): bool
	{
		$table = Mvc::getTablePrefix() . "role_function";
		$sqls = [
			new Sql("DELETE FROM `{$table}` WHERE `role_id` = :roleId", [
				"roleId" => $this->getId()
			])
		];
		foreach ($menuIds as $menuId) {
			$sqls[] = new Sql("INSERT INTO `{$table}`(`role_id`,`function_id`) VALUES (:roleId,:functionId)", [
				"roleId" => $this->getId(),
				"functionId" => $menuId
			]);
		}
		$res = self::database()->transaction($sqls) > 0;
		if ($res) {
			$ids = "'" . join("'", $menuIds) . "'";
			$this->menus = MenuModel::pager(1, 1000, null, "id IN ({$ids})");
		}
		return $res;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return RoleModel
	 */
	public function setId(string $id): RoleModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param string $parentId
	 * @return RoleModel
	 */
	public function setParentId(string $parentId): RoleModel
	{
		$this->parentId = $parentId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return RoleModel
	 */
	public function setName(string $name): RoleModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return RoleModel
	 */
	public function setDescription(string $description): RoleModel
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEnable(): bool
	{
		return $this->enable;
	}

	/**
	 * @param bool $enable
	 * @return RoleModel
	 */
	public function setEnable(bool $enable): RoleModel
	{
		$this->enable = $enable;
		return $this;
	}

	/**
	 * 返回主键
	 * @return array
	 */
	public static function primaryKeyFields(): array
	{
		return ["id"];
	}

	/**
	 * 返回模型和数据库对应的字段
	 * key 为数据库对应的字段名, value 为模型字段名
	 * key 不写或为数字时将被框架忽略, 使用value值做为key
	 * @return array
	 */
	public static function fields(): array
	{
		return [
			"id",
			"parent_id" => "parentId",
			"name",
			"description",
			"enable",
		];
	}
}
