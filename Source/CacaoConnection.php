<?php
namespace CacaoPHP;


use CacaoPHP\Exceptions\CacaoException;
use CacaoPHP\Exceptions\ServerException;
use CacaoPHP\Interfaces\IConnection;
use UnixSocks\Client;
use UnixSocks\IClient;


class CacaoConnection implements IConnection
{
	private const TERMINATING_CHARACTER = "\0";
	
	
	/** @var IClient|null */
	private $driver;
	
	/** @var CacaoConfig */
	private $config;
	
	
	private function createDriver(): void
	{
		$this->driver = new Client($this->config->getFilePath());
	}
	
	private function connect(): void
	{
		if (!$this->config)
			throw new CacaoException("Config is not set");
		
		if (!$this->config->getFilePath())
			throw new CacaoException("File path is not set");
		
		if (!$this->driver)
			$this->createDriver();
		
		if (!$this->driver->tryConnect())
			throw new CacaoException("Failed to connect to socket with file path {$this->config->getFilePath()}");
	}
	
	
	public function setConfig(CacaoConfig $config): void
	{
		$this->config = $config;
	}
	
	public function send(array $data): array
	{
		$this->connect();
		
		$this->driver->write(jsonencode($data));
		$serverResponse = $this->driver->readUntil(
			self::TERMINATING_CHARACTER,
			$this->config->getReatTimeout()
		);
		
		$response = jsondecode($serverResponse, true);
		
		if (is_null($response))
			throw new ServerException("Unexpected response from server");
		
		return $response;
	}
}