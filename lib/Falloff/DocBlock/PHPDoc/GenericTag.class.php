<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\Receptacle;
use Falloff\Tokenizer\Token;

class GenericTag extends \Falloff\DocBlock\PHPDoc\Tag implements \ArrayAccess, \Iterator, \Countable {

	use Receptacle;

	protected readonly string $name;
	
	function parsePlan() : array {
		return [];
	}

	function setName( string $name ){
		$this->name = $name;
	}

	function process( Token $token ): ?Token{

		$stream = $this->stream->get();
		$had_newline = false;

		$str = $token->value;
		while( !$stream->eof ) {

			$token = $stream();

			if( in_array($token->type, ['DOT_NEWLINE','DOUBLE_NEWLINE','NEWLINE',]) ){

				$had_newline = true;

			} elseif( $token->type == 'AT' and $had_newline ){

				if( !empty( $str ) )
					$this->children[] = trim($str);

				return $token;

			} else{

				$str .= $token->value;

			}
		}

		if( !empty( $str ) )
			$this->children[] = trim($str);

		return null;

	}

	function __get( string $what ) : string{
		if( $what == 'name' )
			return $this->name;

		return parent::__get($what);
	}

}