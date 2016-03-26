<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class text extends Input {
	private $sType = 'text';

	public function getType(){
		return $this->sType;
	}
}