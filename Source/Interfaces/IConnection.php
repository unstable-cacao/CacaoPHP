<?php
namespace CacaoPHP\Interfaces;


interface IConnection
{
	public function send(array $data): array;
}