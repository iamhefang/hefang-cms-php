<?php


namespace link\hefang\cms\user\models;


use link\hefang\mvc\models\BaseLoginModel;

class AccountModel extends BaseLoginModel
{
	private $id = "";
	private $role = "";
	private $name = "";
	private $password = "";
	private $registerTime = 0;
	private $registerType = "";
	private $email = "";
	private $locked = false;
	private $lockedTime = 0;
	private $enable = true;

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return AccountModel
	 */
	public function setId(string $id): AccountModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRole(): string
	{
		return $this->role;
	}

	/**
	 * @param string $role
	 * @return AccountModel
	 */
	public function setRole(string $role): AccountModel
	{
		$this->role = $role;
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
	 * @return AccountModel
	 */
	public function setName(string $name): AccountModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return AccountModel
	 */
	public function setPassword(string $password): AccountModel
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRegisterTime(): int
	{
		return $this->registerTime;
	}

	/**
	 * @param int $registerTime
	 * @return AccountModel
	 */
	public function setRegisterTime(int $registerTime): AccountModel
	{
		$this->registerTime = $registerTime;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRegisterType(): string
	{
		return $this->registerType;
	}

	/**
	 * @param string $registerType
	 * @return AccountModel
	 */
	public function setRegisterType(string $registerType): AccountModel
	{
		$this->registerType = $registerType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return AccountModel
	 */
	public function setEmail(string $email): AccountModel
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLocked(): bool
	{
		return $this->locked;
	}

	/**
	 * @param bool $locked
	 * @return AccountModel
	 */
	public function setLocked(bool $locked): AccountModel
	{
		$this->locked = $locked;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLockedTime(): int
	{
		return $this->lockedTime;
	}

	/**
	 * @param int $lockedTime
	 * @return AccountModel
	 */
	public function setLockedTime(int $lockedTime): AccountModel
	{
		$this->lockedTime = $lockedTime;
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
	 * @return AccountModel
	 */
	public function setEnable(bool $enable): AccountModel
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
			"role",
			"name",
			"password",
			"register_time" => "registerTime",
			"register_type" => "registerType",
			"email",
			"locked",
			"locked_time" => "lockedTime",
			"enable",
		];
	}

	public function isAdmin(): bool
	{
		return $this->isSuperAdmin() || $this->getRole() === "admin";
	}

	public function isSuperAdmin(): bool
	{
		return $this->getId() === "root";
	}

	/**
	 * @return string|null
	 */
	public function getRoleName()
	{
		return $this->getRole();
	}

	/**
	 * @return string|null
	 */
	public function getRoleId()
	{
		return $this->getRole();
	}

	public function setRoleId(string $roleId)
	{
		$this->setRole($roleId);
		return $this;
	}
}
