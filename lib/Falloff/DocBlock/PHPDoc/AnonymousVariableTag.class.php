<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

abstract class AnonymousVariableTag extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\Type $type = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'type' => Terminal\Type::class,
			'description' => [PHPDoc\Text::class, PHPDoc\QuotedStr::class],
		];

	}


}

