<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;
use Falloff\DocBlock\ObligatoryParameterMissingException;
use Falloff\DocBlock\Visitable;

abstract class Tag extends \Falloff\DocBlock\Entity {

	use Visitable;

	static function create( Token $name, Stream $stream ){

		if( $name->type != 'WORD' )
			throw new UnexpectedTokenException("Cannot treat token of type `{$token->type}` with the value of `{$token->value}` as tag name");

		$klassname = static::word2klassname( $name->value );

		$full_klassname =  'Falloff\\DocBlock\\PHPDoc\\Tag\\' . $klassname;

		if( class_exists($full_klassname) )
			return new $full_klassname( $stream );
		else {
			$tag = new GenericTag( $stream );
			$tag->setName( $name->value );
			return $tag;
		}
	}

	abstract protected function parsePlan();

	protected function requiredPlanSteps(){return [];}

	static function accept( Token $token ){
		return $token->type == 'AT';
	}


	function getVisitorPayload() : array{
		$payload = [];
		foreach( $this->parsePlan() as $prop => $klass ){
			$payload[ $prop ] = $this->$prop;
		}
		return $payload;
	}

	function getVisitableProperties() : array {
		return array_keys( $this->parsePlan() );
	}

	function process( Token $token ) : ?Token{

		// Skip the space coming right after the token name
		if( $token->type == 'SPACE' )
			$token = ($this->stream->get())();

		$plan = $this->parsePlan();
		$required = $this->requiredPlanSteps();

		$plan_map = array_keys($plan);

		while( $token and !empty( $plan_map ) ) {

			$reciever = array_shift( $plan_map );

			$candidate_klasses = (
				is_array( $plan[ $reciever ] )
				? $plan[ $reciever ]
				: [$plan[ $reciever ]]
			);

			// The reciever klass must report that
			// it takes the token provided as initial
			$klass = array_filter( $candidate_klasses, fn( $klassname ) => $klassname::accept( $token ) );

			if( empty( $klass ) ){

				if( in_array($reciever, $required) ) {
					throw new ObligatoryParameterMissingException(
						"`$reciever` field is required for the " 
						. '`' . strtolower( preg_replace("/.*\\\\/", '', static::class) ) . '`' 
						. ' tag'
					);
				}

			} else{

				// Taking only the first candidate that
				// takes the token provided
				$klass = array_shift( $klass );

				$this->$reciever = new $klass( $this->stream->get() );
				$token = $this->$reciever->process( $token );

				if( empty( $token ) )
					return null;

				// Space was returned, throwing it away
				if( $token->type == 'SPACE' ){
					$token = ($this->stream->get())();
				}
			}


			if( empty($token) || empty( $plan_map ) || $token->type == 'AT' ){
				return $token;
			}
	
		};

		return $token;

	}

	function handleEof() : void{
		$required = $this->requiredPlanSteps();

		foreach( $required as $required_field ){

			if( empty( $this->$required_field ) ){
				throw new ObligatoryParameterMissingException(
					"`$required_field` parameter is required for the " 
					. '`' . strtolower( preg_replace("/.*\\\\/", '', static::class) ) . '`' 
					. ' tag'
				);
			}
		}
	}

	function __get( string $name ){

		if(property_exists($this, $name) ){

			if( 
				( $this->$name instanceof Tag )
				or
				( $this->$name instanceof Terminal )
				or
				( $this->$name instanceof Text )

			){
				return $this->$name;
			}

			if( !isset( $this->$name ) )
				return null;

		}

		throw new \Exception("No such field `$name` for the " . self::class . ' instance');

	}

	const RESERVED_KEYWORDS = [
		'Abstract',
		'And',
		'Array',
		'As',
		'Break',
		'Callable',
		'Case',
		'Catch',
		'Class',
		'Clone',
		'Const',
		'Continue',
		'Declare',
		'Default',
		'Die',
		'Do',
		'Echo',
		'Else',
		'Elseif',
		'Empty',
		'Enddeclare',
		'Endfor',
		'Endforeach',
		'Endif',
		'Endswitch',
		'Endwhile',
		'Eval',
		'Exit',
		'Extends',
		'Final',
		'Finally',
		'Fn',
		'For',
		'Foreach',
		'Function',
		'Global',
		'Goto',
		'If',
		'Implements',
		'Include',
		'Include_once',
		'Instanceof',
		'Insteadof',
		'Interface',
		'Isset',
		'List',
		'Match',
		'Namespace',
		'New',
		'Or',
		'Print',
		'Private',
		'Protected',
		'Public',
		'Readonly',
		'Require',
		'Require_once',
		'Return',
		'Static',
		'Switch',
		'Throw',
		'Trait',
		'Try',
		'Unset',
		'Use',
		'Var',
		'While',
		'Xor',
		'Yield',
	];

	protected static function word2klassname( string $klassname ) : string{
		$klassname = preg_replace_callback("/(^[a-z]|-[a-z])/", function( $matches ){
				return ucfirst(
					strlen($matches[1]) == 1
					? $matches[1]
					: substr($matches[1], 1)
				);
		}, $klassname);

		if( in_array($klassname, static::RESERVED_KEYWORDS) ){
			$klassname .= 'Tag';
		}

		return $klassname;

	}

}