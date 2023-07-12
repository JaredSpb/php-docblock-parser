<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Email extends \Falloff\DocBlock\PHPDoc\Terminal {

	static function accept( Token $token ){
		return $token->type == 'ANGLE_OPEN_BRACKET';
	}

	function process( Token $token ): ?Token{

		if( $token->type != 'ANGLE_OPEN_BRACKET' )
			throw new UnexpectedTokenException("Cannot treat token of type `{$token->type}` with the value of `{$token->value}` as email");

		while( $token = ($this->stream->get())() ){

			if( $token->type == 'ANGLE_CLOSE_BRACKET' ){

				return ($this->stream->get())();

			} elseif( $token->type == 'WORD' || $token->type == 'AT' ){

				$this->value .= $token->value;

			} else{

				throw new UnexpectedTokenException("Cannot treat token of type `{$token->type}` with the value of `{$token->value}` as email");

			}

		}


	}

}