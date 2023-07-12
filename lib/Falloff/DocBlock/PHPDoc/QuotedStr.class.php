<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use \Falloff\DocBlock\{Visitable, ConacatenatingContainer};

class QuotedStr extends \Falloff\DocBlock\Entity{

	use Visitable;

	protected string $delimiter;
	protected string $text = '';

	static function accept( Token $token ){
		return $token->type == 'QUOTE';
	}

	function getVisitorPayload() : array{
		return ['text' => (string)$this];
	}

	function getVisitableProperties() : array{
		return [];
	}
	
	function process( Token $token ): ?Token{

		$this->delimiter = $token->value;

		while( $token = ($this->stream->get())() ){

			if( $token->type == 'QUOTE' and $token->value == $this->delimiter ){
				return ($this->stream->get())();
			} elseif ( $token->type == 'ESCAPED_CHARACTER' and $token->value == '\\' . $this->delimiter ) {
				$this->text .= ltrim($token->value,'\\');
			} else{
				$this->text .= $token->value;
			}

		}

		return null;

	}

	function __toString(){
		return $this->text;
	}
}
