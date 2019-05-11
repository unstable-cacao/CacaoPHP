<?php
namespace CacaoPHP\Objects;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property float $PingTime
 * @property float $PongTime
 */
class Pong extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'PingTime' => LiteSetup::createDouble(),
			'PongTime' => LiteSetup::createDouble()
		];
	}
	
	
	public function getDelay(): float
	{
		return $this->PongTime - $this->PingTime;
	}
}