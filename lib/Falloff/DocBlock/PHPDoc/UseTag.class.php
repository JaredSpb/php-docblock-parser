<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class UseTag extends Tag {

	protected ?Terminal\GenericWord $ref = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'ref'	=> Terminal\GenericWord::class,
			'description' => PHPDoc\Text::class,
		];

	}

	protected function requiredPlanSteps(){
		return ['ref'];
	}


}


