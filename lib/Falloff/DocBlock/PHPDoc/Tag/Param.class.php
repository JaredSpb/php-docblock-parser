<?php
namespace Falloff\DocBlock\PHPDoc\Tag;

class Param extends \Falloff\DocBlock\PHPDoc\VariableTag {


	protected function requiredPlanSteps(){
		return ['name'];
	}


}