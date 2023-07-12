<?php
namespace Falloff\DocBlock;

trait ArrayAlike{

	protected array $children = [];
	protected int $pointer = 0;

	function offsetExists($offset): bool {
		return array_key_exists($offset, $this->children);
	}
	function offsetGet(mixed $offset): mixed {
		return $this->children[ $offset ];
	}
	function offsetSet(mixed $offset, mixed $value): void {
		// Setting element is performed via separate method
	}
	function offsetUnset(mixed $offset): void{
		array_splice($this->children, $offset, 1);
	}

	
	function current():mixed {
		return $this->children[ $this->pointer ];
	}
	function key(): mixed{
		return $this->pointer;
	}
	function next(): void{
		++$this->pointer;
	}
	function rewind(): void{
		$this->pointer = 0;
	}
	function valid(): bool{
		return array_key_exists($this->pointer, $this->children);
	}

	function count(): int{
		return count( $this->children );
	}

}
