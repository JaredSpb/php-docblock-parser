<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

abstract class VariableTag extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\Type $type = null;
	protected ?Terminal\Varname $name = null;
	protected ?PHPDoc\Text $description = null;


	protected function parsePlan(){
		
		return [
			'type' => Terminal\Type::class,
			'name' => Terminal\Varname::class,
			'description' => PHPDoc\Text::class,
		];

	}


}

