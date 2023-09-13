<?php
namespace Falloff\DocBlock;
use Falloff\Tokenizer\Factory;

class PHPDoc implements \ArrayAccess, \Iterator, \Countable {

	use Receptacle;
	
	protected string $raw;

	function __construct( string $raw ){

		// Cut delimiters
		$raw = preg_replace("/^\/\*\*\s*/", '', $raw);
		$raw = preg_replace("/\*\/$/s", '', $raw);

		if( preg_match("/\R/", $raw) ){

			$strings = preg_split("/\R/", $raw);
			$raw = implode("\n", array_filter($strings, function( $str ){
				return preg_match("/^\h*\*/", $str);
			}));

		}

		// Cut leading asterics and spaces
		$raw = preg_replace("/^\h*\*\h?/m", "", $raw);
		$this->raw = $raw;

		$stream = (new Factory( self::getRules() ))->getStream( $raw );

		$token = $stream();
		$intro_complete = false;

		while( $token ){

			// The initial text, it is supposed to consume everything untill the first `AT` token
			if( 
				$token->type != 'AT' 
				and $token->type != 'NEWLINE' 
				and !$intro_complete
			){

				$child = (new PHPDoc\TextBlock( $stream ));
				$this->addChild( $child );

				$token = $child->process( $token );
				$intro_complete = true;

			}
			// All that follows the initial text are tags
			elseif( $token->type == 'AT' ){

				$tagname_token = $stream();

				if( empty($tagname_token) ){
					throw new \Exception("Unexpected `@` symbol at " . $token->offset);
				}

				$child = PHPDoc\Tag::create( $tagname_token, $stream );

				$this->addChild( $child );

				$token = null;
				if( $stream->eof ){
					$child->handleEof();
				} else{
					$token = $child->process( $stream() );
				}

				$intro_complete = true;

			}
			else{
				
				if( !in_array($token->type, ['NEWLINE', 'SPACE']) ){

print_r($this);
exit;

					throw new UnexpectedTokenException("Token of type `{$token->type}` with the value of `{$token->value}` is not supposed to be here");
				}

				$token = $stream();

			}
			

		}
	}

	function visit( Visitor $visitor ){
		foreach( $this->children as $i => $el ){
			$el->visit( $visitor, $i );
		}
	}

	static protected function getRules(){
		return [
			'ESCAPED_CHARACTER' => '#\\G\\\\.#s',

			'VERSION_ALIKE' => '/\\G\d+\.\d+(\.\d+)*[^\s]*/',

			'CURLY_OPEN_BRACKET' => '/\\G\{/',
			'CURLY_CLOSE_BRACKET' => '/\\G\}/',

			'ANGLE_OPEN_BRACKET' => '/\\G</',
			'ANGLE_CLOSE_BRACKET' => '/\\G\\>/',

			'QUOTE' => '/\\G["\']/',

			'INT' => '/\\G\d+/',

			'URL' => '/\\G\w+:\/\/([-0-9a-z]+)(\.[-a-z0-9]+)+(\/[-a-z0-9._~!$&\'()*+,;=:@%?]*)*/i',

			'FUNCTION_NAME' => '/\\G[a-z0-9_]+(?=\()/i',

			'PARENTHESIS_OPEN' => '/\\G\(/',
			'PARENTHESIS_CLOSE' => '/\\G\)/',

			'AT' => '/\\G@/',

			'DOT_NEWLINE' => '/\\G\.\R/',

			'DOUBLE_NEWLINE' => '/\\G\R\s*?\R/',

			'NEWLINE' => '/\\G\R/s',

			'SPACE' => '/\\G\h+/s',

			'WORD' => '/\\G([\\w]+\\.(?!\R)|[\\w-]+)/',

			'SYMBOL' => '/\\G./',

		];
	}

}