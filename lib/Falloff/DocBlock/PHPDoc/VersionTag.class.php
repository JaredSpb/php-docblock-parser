<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

abstract class VersionTag extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?PHPDoc\Terminal\Version $version = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'version' => PHPDoc\Terminal\Version::class,
			'description' => PHPDoc\Text::class,
		];

	}

	function getDescription(){
		return $this->description;
	}

	function __get(string $what) : mixed{
		if( $what == 'version' )
			return $this->version;

		return parent::__get($what);
	}

}



