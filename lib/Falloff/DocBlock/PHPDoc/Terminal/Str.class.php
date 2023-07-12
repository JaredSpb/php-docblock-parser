<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Str extends \Falloff\DocBlock\PHPDoc\Terminal {

	const TOKENS_OKAY = [
		'ESCAPED_CHARACTER',
		'VERSION_ALIKE',
		'QUOTE',
		'INT',
		'FUNCTION_NAME',
		'PARENTHESIS_OPEN',
		'PARENTHESIS_CLOSE',
		'AT',
		'SPACE',
		'WORD',
		'SYMBOL',		
	];

	protected string $value = '';

	static function accept( Token $token ){

		return in_array(
			$token->type, 
			static::TOKENS_OKAY
		);

	}

	function process( Token $token ) : ?Token {

		$stream = $this->stream->get();

		while( $token and in_array($token->type, static::TOKENS_OKAY) ){
			$this->value .= $token->value;

			if( $stream->eof ){
				$this->value = trim($this->value);
				return null;
			}

			$token = $stream();
		}

		$this->value = trim($this->value);
		return $token;

	}

	function __toString(){
		return $this->value;
	}

}