<?php
namespace CacaoPHP\Interfaces;


interface IConnectionDecorator extends IConnection
{
	public function setChild(IConnection $child): void;
	public function getChild(): ?IConnection;
}