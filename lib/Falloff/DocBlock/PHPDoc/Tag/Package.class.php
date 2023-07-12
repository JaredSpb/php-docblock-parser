<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class Package extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\Hierarchy $hierarchy = null;

	protected function parsePlan(){

		return [
			'hierarchy' => Terminal\Hierarchy::class,
		];

	}

	function __get( string $what ){
		if( $what == 'hierarchy' )
			return $this->hierarchy;
		
		return parent::__get($what);
	}

}
