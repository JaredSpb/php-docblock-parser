<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;

class Author extends \Falloff\DocBlock\PHPDoc\Tag {

	protected ?PHPDoc\Terminal\Str $author = null;
	protected ?Terminal\Email $email = null;


	protected function parsePlan(){
		
		return [
			'author' => PHPDoc\Terminal\Str::class,
			'email'	=> Terminal\Email::class,
		];

	}

}