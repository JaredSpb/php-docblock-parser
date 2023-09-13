<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;

class License extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?Terminal\Str $license = null;
	protected ?Terminal\URL $url = null;


	protected function parsePlan(){
		
		return [
			'url'	=> Terminal\URL::class,
			'license' => Terminal\Str::class,
		];

	}

}
