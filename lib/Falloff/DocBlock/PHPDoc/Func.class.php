<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\Visitable;
use Falloff\DocBlock\Receptacle;
use Falloff\DocBlock\PHPDoc\Terminal\Param;


class Func extends \Falloff\DocBlock\Entity implements \ArrayAccess, \Iterator, \Countable {

	use Receptacle;

	protected string $name;
	// protected ParamsList $params;

	static function accept( Token $token ){

		$accept = in_array(
			$token->type, 
			['FUNCTION_NAME']
		);

		return $accept;
	}

	function getVisitorPayload() : array {
		return [
			'name' => $this->name,
		];
	}

	function getVisitableProperties() : array {
		return [
			'name',
		];
	}

	function process( Token $token ): ?Token{

		$this->name = $token->value;
		$token = ($this->stream->get())();

		while( $token ){

			if( $token->type == 'PARENTHESIS_OPEN' ){

				$token = ($this->stream->get())();

			} elseif( $token->type == 'PARENTHESIS_CLOSE' ){

				return ($this->stream->get())();

			} else{

				if( $token->type == 'SYMBOL' and $token->value == ',' )
					$token = ($this->stream->get())();

				$param = new Param( $this->stream->get() );
				$token = $param->process( $token );
				$this->addChild( $param );

			}

		}

		return null;

	}

	function __get( string $what ) : mixed {
		if( in_array($what, ['params','name']) )
			return $this->$what;

		return parent::__get($name);

	}

}