<?php


namespace link\hefang\cms\core\plugin\events;


use link\hefang\cms\application\plugin\models\PluginModel;

class PluginEvent
{
	private $data = null;
	private $event = "";
	private $pluginPath = [];

	public function __construct(string $event, $data = null)
	{
		$this->data = $data;
		$this->event = $event;
	}

	/**
	 * @return string
	 */
	public function getEvent(): string
	{
		return $this->event;
	}

	/**
	 * @return null|mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param null|mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return PluginModel[]
	 */
	public function getPluginPath(): array
	{
		return array_merge([], $this->pluginPath);
	}

	public function addPluginPath(PluginModel $model): PluginEvent
	{
		$this->pluginPath[] = $model;
		return $this;
	}
}
