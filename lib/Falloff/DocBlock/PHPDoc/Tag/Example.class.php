<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class Example extends \Falloff\DocBlock\PHPDoc\Tag {

	protected PHPDoc\Terminal\Location $location;
	protected ?PHPDoc\Terminal\Integer $start_line = null;
	protected ?PHPDoc\Terminal\Integer $number_of_lines = null;
	protected ?PHPDoc\Text $description = null;

	protected function parsePlan(){
		
		return [
			'location' => PHPDoc\Terminal\Location::class,
			'start_line' => PHPDoc\Terminal\Integer::class,
			'number_of_lines' => PHPDoc\Terminal\Integer::class,
			'description' => PHPDoc\Text::class,
		];

	}

	protected function requiredPlanSteps(){
		return ['location'];
	}


}