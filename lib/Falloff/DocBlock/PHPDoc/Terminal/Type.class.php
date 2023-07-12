<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Type extends GenericWord {

	const TOKENS_OKAY = [
		'ESCAPED_CHARACTER',
		'VERSION_ALIKE',
		'ANGLE_OPEN_BRACKET',
		'ANGLE_CLOSE_BRACKET',
		'QUOTE',
		'INT',
		'PARENTHESIS_OPEN',
		'PARENTHESIS_CLOSE',
		'AT',
		'WORD',
		'SYMBOL',		
	];

	static function accept( Token $token ){
		
		// Types do not start with the `$` sign, thats a 
		// variable name most likely
		if( $token->type == 'SYMBOL' and $token->value == '$' )
			return false;
		// String starting with quote is not a type most likely
		if( $token->type == 'QUOTE' )
			return false;

		return parent::accept($token);

	}

}