<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class submit extends Input {
	private $sType = 'submit';

	public function getType(){
		return $this->sType;
	}

	public function getLabelHtml(){
		return null;
	}

	public function getValue(){
		return empty($this->mValue) ? $this->getLabel() : $this->mValue;
	}
}

?>