<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\Visitable;

use Falloff\DocBlock\{ConacatenatingContainer, Receptacle, Entity};

class TextBlock extends Entity {

	use Visitable;

	protected Text $summary;
	protected Text $text;

	protected bool $summary_compete = false;

	protected static $tokens_okay = [
		'WORD'
		,'SPACE'
		,'INT'
		,'NEWLINE'
		,'QUOTE'
		,'SYMBOL'
		,'PARENTHESIS_CLOSE'
		,'PARENTHESIS_OPEN'
		,'FUNCTION_NAME'
		,'DOUBLE_NEWLINE'
		,'DOT_NEWLINE'
		,'ESCAPED_CHARACTER'
		,'CURLY_OPEN_BRACKET'
	];

	function __construct( Stream $stream ){
		parent::__construct( $stream );
		$this->summary = new Text( $stream );
		$this->summary->singleLine();
		$this->text = new Text( $stream );
	}

	function getVisitorPayload() : array{
		return [
			'summary' => $this->summary,
			'text' => $this->text
		];
	}

	function getVisitableProperties() : array {
		return ['summary', 'text'];
	}


	static function accept( Token $token ){

		return 
			in_array( $token->type, self::$tokens_okay ) 
			or in_array( $token->type, [ 'DOUBLE_NEWLINE', 'CURLY_OPEN_BRACKET' ] );

	}
	
	function process( Token $token ): ?Token{

		$stream = $this->stream->get();

		// On initial linefeed switch to the text section instantly
		if( in_array($token->type, ['DOUBLE_NEWLINE' ]) ){
			return $this->text->process( $stream() );
		}

		$token = $this->summary->process( $token );

		if( $stream->eof )
			return null;

		if( Text::accept( $token ) )
			return $this->text->process( $token );

		return $token;

		
	}

	function __get( string $name ){

		if( in_array($name, ['summary','text']) )
			return $this->$name;

		throw new \Exception("`$name` property unavailable for " . __CLASS__ . ' object');
	}


}
