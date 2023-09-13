<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class License extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?PHPDoc\Text $license = null;
	protected ?Terminal\URL $url = null;


	protected function parsePlan(){
		
		return [
			'license' => PHPDoc\Text::class,
			'url'	=> Terminal\URL::class,
		];

	}

}
