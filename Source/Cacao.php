<?php
namespace CacaoPHP;


use CacaoPHP\Interfaces\IConnection;


class Cacao
{
	private $configs 		= [];
	private $connections 	= [];
	
	
	public function config(string $name = 'default', ?array $config = null): CacaoConfig
	{
		$configObject = $this->configs[$name] ?? new CacaoConfig();
		
		if ($config)
		{
			$configObject->setConfig($config);
		}
		
		$this->configs[$name] = $configObject;
		
		return $configObject;
	}
	
	public function connection(string $name = 'default'): IConnection
	{
		if (!isset($this->connections[$name]))
		{
			$connection = new CacaoConnection();
			$connection->setConfig($this->config($name));
			$this->connections[$name] = $connection;
		}
		
		return $this->connections[$name];
	}
}