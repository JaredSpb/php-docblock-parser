<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class GenericWord extends Str {

	const TOKENS_OKAY = [
		'ESCAPED_CHARACTER',
		'VERSION_ALIKE',
		'ANGLE_OPEN_BRACKET',
		'ANGLE_CLOSE_BRACKET',
		'QUOTE',
		'INT',
		'FUNCTION_NAME',
		'PARENTHESIS_OPEN',
		'PARENTHESIS_CLOSE',
		'AT',
		'WORD',
		'SYMBOL',		
	];


}