<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\UnexpectedTokenException;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Param extends Terminal {

	use \Falloff\DocBlock\Visitable;

	protected string $name = '';
	protected ?string $type = null;

	static function accept( Token $token ){
		return $token->type == 'SYMBOL' and $token->value == '$';
	}

	function getVisitorPayload() : array {
		return ['name' => $this->name, 'type' => $this->type];
	}

	function getVisitableProperties() : array{
		return ['name','type'];
	}


	function process( Token $token ) : ?Token{

		$this->value = $token->value;
		$stream = $this->stream->get();

		while( $token ){

			// Skipping whitespaces
			if( $token->type == 'SPACE' ){

				$token = $stream();

			} elseif( $token->type == 'SYMBOL' and $token->value == '$' ){

				$token = $stream();

				if( $token->type != 'WORD' )
					throw new UnexpectedTokenException("Cannot use token of type `{$token->type}` with the value of `{$token->value}` as param name");

				$this->name = '$' . $token->value;

				return (
					$stream->eof
					? null
					: $stream()
				);

			} elseif( $token->type == 'PARENTHESIS_CLOSE' ){

				return $token;

			} else {

				$this->type = $token->value;
				$token = $stream();

				while(in_array($token->type, ['SYMBOL','WORD','ESCAPED_CHARACTER'])){
					$this->type .= $token->value;
					$token = $stream();
				}

			}
			
			
		}

		return null;

	}

	function __toString(){
		return $this->type . ' ' . $this->name;
	}

	function __get($name){
		if( in_array($name, ['name','type']) )
			return $this->$name;
	}

}