<?php
namespace Falloff\DocBlock;

interface Visitor{

	function in( Entity|string|null $entity, array $payload, int|string $idx );
	function out( Entity|string|null $entity, array $payload, int|string $idx );

}
