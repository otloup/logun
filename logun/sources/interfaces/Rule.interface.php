<?php

	interface RuleInterface {
		public function getName();
		public function getMessage();
		public function getRuleType();

		public function explain();

		public function validate($mValue);
	}

?>