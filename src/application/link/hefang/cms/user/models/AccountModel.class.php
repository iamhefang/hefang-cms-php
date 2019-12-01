<?php


namespace link\hefang\cms\user\models;


use link\hefang\mvc\models\BaseLoginModel;

class AccountModel extends BaseLoginModel
{
	private $id = "";
	private $roleId = "";
	private $name = "";
	private $password = "";
	private $registerTime = null;
	private $registerType = "";
	private $email = "";
	private $locked = false;
	private $lockedTime = null;
	private $avatar = null;
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
	public function getRoleId(): string
	{
		return $this->roleId;
	}

	/**
	 * @param string $roleId
	 * @return AccountModel
	 */
	public function setRoleId(string $roleId): AccountModel
	{
		$this->roleId = $roleId;
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
	 * @return string
	 */
	public function getRegisterTime(): string
	{
		return $this->registerTime;
	}

	/**
	 * @param int $registerTime
	 * @return AccountModel
	 */
	public function setRegisterTime($registerTime): AccountModel
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
	 * @return string
	 */
	public function getLockedTime(): string
	{
		return $this->lockedTime;
	}

	/**
	 * @param string $lockedTime
	 * @return AccountModel
	 */
	public function setLockedTime(string $lockedTime): AccountModel
	{
		$this->lockedTime = $lockedTime;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * @param string $avatar
	 * @return AccountModel
	 */
	public function setAvatar(string $avatar): AccountModel
	{
		$this->avatar = $avatar;
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
			"role_id" => "roleId",
			"name",
			"avatar",
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
		return $this->isSuperAdmin() || $this->getRoleId() === "admin";
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
		return $this->getRoleId();
	}
}
