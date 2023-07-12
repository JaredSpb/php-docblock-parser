<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Varname extends \Falloff\DocBlock\PHPDoc\Terminal {

	protected static $tokens_okay = [
		'WORD'
		,'INT'
		,'QUOTE'
		,'SYMBOL'
		,'PARENTHESIS_CLOSE'
		,'PARENTHESIS_OPEN'
		,'FUNCTION_NAME'
	];

	static function accept( Token $token ){

		if( 
			($token->type == 'SYMBOL' and $token->value == '$' ) // The vars like $some_var_name
			or 
			$token->type == 'WORD' // For constants like SOME_CONSTANT_NAME
		)
			return true;

		return false;
	}

	function process( Token $token ): ?Token{

		$this->value = $token->value;

		while( $token = ($this->stream->get())() ){
			if( preg_match("/[a-z0-9_]+/i", $token->value) ){
				$this->value .= $token->value;
			} else{
				return $token;
			}

		};

		return null;

	}

}