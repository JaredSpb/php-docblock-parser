<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class License extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?PHPDoc\TextBlock $license = null;
	protected ?Terminal\URL $url = null;


	protected function parsePlan(){
		
		return [
			'license' => PHPDoc\TextBlock::class,
			'url'	=> Terminal\URL::class,
		];

	}

}
