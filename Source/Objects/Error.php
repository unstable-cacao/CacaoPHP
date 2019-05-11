<?php
namespace CacaoPHP\Objects;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property int 	$ID
 * @property string $Message
 */
class Error extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'ID' 		=> LiteSetup::createInt(),
			'Message'	=> LiteSetup::createString()
		];
	}
}