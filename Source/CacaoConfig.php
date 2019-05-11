<?php
namespace CacaoPHP;


use CacaoPHP\Exceptions\CacaoException;
use CacaoPHP\Interfaces\IConnection;
use CacaoPHP\Interfaces\IConnectionDecorator;


class CacaoConfig
{
	private const POSSIBLE_CONFIG = [
		'path' 		=> ['path', 'filepath', 'file_path', 'socket', 'file'],
		'timeout' 	=> ['timeout', 'readtimeout', 'read_timeout']
	];
	
	
	/** @var string */
	private $path = '';
	
	/** @var int */
	private $timeout = 0;
	
	/** @var IConnectionDecorator|null */
	private $decorator;
	
	
	/**
	 * @param mixed $default
	 * @param string $name
	 * @param array $config
	 * @return mixed
	 */
	private function getValue($default, string $name, array $config)
	{
		$result = $default;
		
		foreach (self::POSSIBLE_CONFIG[$name] as $key)
		{
			if (key_exists($key, $config))
			{
				$result = $config[$key];
				break;
			}
		}
		
		return $result;
	}
	
	
	public function setConfig(array $config): void
	{
		$config = array_change_key_case($config, CASE_LOWER);
		
		$this->setFilePath($this->getValue($this->path, 'path', $config));
		$this->setReadTimeout($this->getValue($this->timeout, 'timeout', $config));
	}
	
	public function setFilePath(string $path): void
	{
		$this->path = $path;
	}
	
	public function getFilePath(): string
	{
		return $this->path;
	}
	
	public function setReadTimeout(int $timeout): void
	{
		$this->timeout = $timeout;
	}
	
	public function getReatTimeout(): int
	{
		return $this->timeout;
	}
	
	/**
	 * @param string|IConnectionDecorator $decorator
	 */
	public function addDecorator($decorator): void
	{
		if (is_string($decorator))
		{
			$decorator = new $decorator();
		}
		
		if (!($decorator instanceof IConnectionDecorator))
			throw new CacaoException("Decorator must be of type IConnectionDecorator");
		
		if ($this->decorator)
		{
			$decorator->setChild($this->decorator);
		}
		
		$this->decorator = $decorator;
	}
	
	public function decorate(IConnection $connection): IConnection
	{
		if (!$this->decorator)
			return $connection;
		
		$decorator = $this->decorator;
		
		while ($decorator->getChild())
		{
			$decorator = $decorator->getChild();
		}
		
		$decorator->setChild($connection);
		
		return $this->decorator;
	}
}