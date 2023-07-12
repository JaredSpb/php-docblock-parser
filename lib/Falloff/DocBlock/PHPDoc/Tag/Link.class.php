<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class Link extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\URL $url = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'url'	=> Terminal\URL::class,
			'description' => PHPDoc\Text::class,
		];

	}

	protected function requiredPlanSteps(){
		return ['url'];
	}

}
