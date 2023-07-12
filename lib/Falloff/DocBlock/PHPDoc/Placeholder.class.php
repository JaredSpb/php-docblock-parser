<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\DocBlock\PHPDoc\Tag;
use Falloff\DocBlock\Visitable;

class Placeholder extends \Falloff\DocBlock\Entity{

	use Visitable;

	public readonly Tag $tag;

	static function accept( Token $token ){
		return $token->type == 'CURLY_OPEN_BRACKET';
	}

	function getVisitorPayload() : array{
		return [
			'tag' => $this->tag
		];
	}

	function getVisitableProperties() : array{
		return ['tag'];
	}
	
	function process( Token $token ): ?Token{
		
		$token = ($this->stream->get())();

		// Skip spaces
		if( $token->type == 'SPACE' )
			$token = ($this->stream->get())();

		if( $token->type == 'AT' ){

			$tagname_token = ($this->stream->get())();
			$this->tag = Tag::create( $tagname_token , $this->stream->get() );
			$token = $this->tag->process( ($this->stream->get())() );

			if( $token->type == 'CURLY_CLOSE_BRACKET' ){
				return $token;
			} else{
				throw new UnexpectedTokenException("Token of type `{$token->type}` with the value of `{$token->value}` is missplaced");
			}

		} else {
			throw new UnexpectedTokenException("Token of type `{$token->type}` with the value of `{$token->value}` is missplaced");
		}

	}


}
