<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class Licence extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\URL $url = null;
	protected ?Terminal\Str $licence = null;

	protected function parsePlan(){
		
		return [
			'url'	=> Terminal\URL::class,
			'licence' => Terminal\Str::class,
		];

	}

}
