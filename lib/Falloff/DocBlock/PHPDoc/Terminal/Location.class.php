<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use \Falloff\DocBlock\PHPDoc\QuotedStr;

class Location extends \Falloff\DocBlock\PHPDoc\Terminal {

	protected QuotedStr $strict_value;

	static function accept( Token $token ){
		return in_array($token->type, ['WORD','SYMBOL','QUOTE','INT']);
	}

	function process( Token $token ): ?Token{

		// When quote is given, parse
		// strict quoted string
		if( $token->type == 'QUOTE' ){
			$this->strict_value = new QuotedStr( $this->stream->get() );
			return $this->strict_value->process( $token );
		}

		while($token and in_array($token->type, ['WORD','SYMBOL','INT'])){
			$this->value .= $token->value;
			$token = ($this->stream->get())();
		}		

		return $token;
	}

	function __toString(){
		if( isset( $this->strict_value ) )
			return (string)$this->strict_value;

		return parent::__toString();
	}

}