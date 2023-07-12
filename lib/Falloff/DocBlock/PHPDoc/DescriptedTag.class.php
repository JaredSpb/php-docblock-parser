<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc;

abstract class DescriptedTag extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Text $description;

	protected function parsePlan(){
		
		return [
			'description' => Text::class,
		];

	}

}