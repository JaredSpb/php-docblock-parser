<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\DocBlock\Visitor;
use Falloff\DocBlock\{Entity,Receptacle,ConacatenatingContainer};

class Text extends Entity implements \ArrayAccess, \Iterator, \Countable {
	use Receptacle, ConacatenatingContainer;

	protected bool $multiline = true;

	protected static $tokens_okay = [
		'WORD'
		,'SPACE'
		,'INT'
		,'NEWLINE'
		,'QUOTE'
		,'ANGLE_OPEN_BRACKET'
		,'ANGLE_CLOSE_BRACKET'
		,'SYMBOL'
		,'PARENTHESIS_CLOSE'
		,'PARENTHESIS_OPEN'
		,'FUNCTION_NAME'
		,'DOT_NEWLINE'
		,'DOUBLE_NEWLINE'
		,'ESCAPED_CHARACTER'
	];

	static function accept( Token $token ){
		return in_array($token->type, self::$tokens_okay);
	}

	function getVisitorPayload() : array {
		return ['multiline' => $this->multiline ];
	}

	function getVisitableProperties() : array{
		return [];
	}

	function process( Token $initial_token ): ?Token{

		$token = $initial_token;
		do{

			// No summary, instantly going for the text
			if( in_array($token->type, ['DOUBLE_NEWLINE','DOT_NEWLINE']) ){

				if( empty( $this->multiline ) ){

					if( $token->type == 'DOT_NEWLINE' ){
						$this->append( $token->value );
					}

					$this->trim();
					return $token;

				} elseif( $token->type == 'DOT_NEWLINE'){

					if($token !== $initial_token )
						$this->append( $token->value );

				} else{

					$this->append( $token->value );

				}				

			} elseif( $token->type == 'QUOTE' ) {

				$qstring = new QuotedStr( $this->stream->get() );
				$this->append( $qstring );

				$qstring->process( $token );

			} elseif( $token->type == 'CURLY_OPEN_BRACKET' ) {

				$placeholder = new Placeholder( $this->stream->get() );
				$this->append( $placeholder );

				$placeholder->process( $token );

			} elseif( in_array($token->type, self::$tokens_okay) ) {

				$this->append( $token->value );

			} else{

				$this->trim();
				return $token;

			}

			$token = ($this->stream->get())();

		} while( $token );

		$this->trim();
		return null;

	}

	function singleLine(){
		$this->multiline = false;
	}

	

}
