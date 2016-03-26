<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class email extends Input {
	private $sType = 'email';

	public function getType(){
		return $this->sType;
	}

	public function getInputSetup(){
		return [
			'rules'	=>	[
				'emailCompatible'	=>	['Supplied value is not of valid email format']
			]
		];
	}
}
