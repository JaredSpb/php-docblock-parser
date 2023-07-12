<?php
namespace Falloff\DocBlock\PHPDoc\Tag;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\UnexpectedTokenException;

class Method extends \Falloff\DocBlock\PHPDoc\Tag {

	protected readonly bool $static;
	protected ?Terminal\Type $type = null;
	protected ?PHPDoc\Func $definition = null;
	protected ?PHPDoc\Text $description = null;


	protected function parsePlan(){
		
		return [
			'type' => Terminal\Type::class,
			'definition' => PHPDoc\Func::class,
			'description' => PHPDoc\Text::class,
		];

	}

	function process( Token $token ) : ?Token {

		$stream = $this->stream->get();

		if( $token->type == 'SPACE' )
			$token = $stream();

		if( $token->type == 'WORD' and $token->value == 'static' ){
			$this->static = true;
			return parent::process( $stream() );
		} else{
			$this->static = false;
			return parent::process( $token );
		}

	}

	function __get( string $name ) : mixed{
		if( $name == 'definition' )
			return $this->definition;
		if( $name == 'static' )
			return $this->static;
		return parent::__get($name);
	}

}