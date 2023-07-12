<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class See extends \Falloff\DocBlock\PHPDoc\Tag {

	protected Terminal\GenericWord|Terminal\URL|null $ref = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'ref'	=> [Terminal\URL::class, Terminal\GenericWord::class],
			'description' => PHPDoc\Text::class,
		];

	}

	protected function requiredPlanSteps(){
		return ['ref'];
	}

}
