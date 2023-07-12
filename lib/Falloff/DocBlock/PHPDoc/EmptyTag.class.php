<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

abstract class EmptyTag extends \Falloff\DocBlock\PHPDoc\Tag {

	protected function parsePlan(){
		return [];
	}


	function process( Token $token ) : ?Token {
		return $token;
	}

}