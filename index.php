<?php

	require_once('logun/LogunForm.php');
/*
 * ->input(
 *      type = type of input field
 *      name = id (if is an array, id increments)
 *      attributes [optional]
 *          -   value: default value of field
 *          -   data: string, integer or an array of available values to select
 *          -   class: string containing class, or classes (separated by commas) assigned to this field
 *          -   id: if id is not to be the same as the field name
 *          -   attr: an array of array pairs - attribute name => attribute value
 *          -   js: an array of array pairs - event name => js function
 *          -   overwrite_defaults: true/false - if default form values are not to be attached
 * )
 *
 * ->*(*)->check / ->input(*)->check(
 *      type = predefined or callable function which check for field validity, eg.:
 *          -   required
 *          -   optional_if: mandatory quantifier array, indicating if some, or all fields, referenced by name, or object, are filled, this field IS NOT madatory
 *          -   required_if: mandatory quantifier array, indicating if some, or all fields, referenced by name, or object, are filled, this field IS madatory
 *          -   optional_if_all: mandatory quantifier array, indicating IF ALL fields, referenced by name, or object, are filled, this field IS NOT madatory
 *          -   required_if_all: mandatory quantifier array, indicating IF ALL fields, referenced by name, or object, are filled, this field IS madatory
 *      valid = message
 *      invalid = message
 *      quantifiers [optional] = array of optional params specyfying validity of check
 * )
 * ->email([...])
 * ->url([...])
 * ->textfield([...])
 * ->password([...])
 * ->textarea([...])
 * ->select(
 *      data = dataset from where to get information. Prefferably array. 
 *          Default construction:
 *          'default'   =>  [key]
 *          [key]   =>  [value]
 *          [key]   =>  [value]
 *          ...
 *          [key]   =>  [   //optgroup
 *              [key]   =>  [value]
 *              [key]   =>  [value]
 *              ...
 *          ]
 *          Hierarchy construction:
 *          'default'   =>  [key]
 *          [parrent_key]   =>  [
 *              [key]   =>  [value]
 *              [key]   =>  [value]
 *              ...
 *          ]
 *          [parrent_key]   =>  [
 *              [key]   =>  [value]
 *              [key]   =>  [value]
 *              ...
 *          ]
 *          ...
 *          [parrent_key]   =>  [
 *              [key]   =>  [   //optgroup
 *                  [key]   =>  [value]
 *                  [key]   =>  [value]
 *                  ...
 *          ]
 *      parent = reference to another LogunSelect object
 *      [...])
 * ->button(value, [...]) 
 */


	$oForm = new LogunForm('testowy', 'http://www.dev.localhost/logun/', LogunForm::TYPE_STANDARD, [
		'method'	=>	LogunForm::METHOD_POST
		,'class'	=>	'forum-class'
        ,'input_defaults'	=>	[
        	'class'	=>	[
        		'all'			=>	'form-input'
        		,'email'		=>	'form-input-email'
        		,'text'			=>	'form-input-text'
        		,'label_all'	=>	'form-label'
        		,'label_email'	=>	'form-label-email'
        		,'label_text'	=>	'form-label-text'
        	]
			,'data-type'	=>	[
        		'all'			=>	'form-input'
        		,'email'		=>	'form-input-email'
        		,'text'			=>	'form-input-text'
        		,'label_all'	=>	'form-label'
        		,'label_email'	=>	'form-label-email'
        		,'label_text'	=>	'form-label-text'
        	]
        ]
        ,'id'	=>	'testowyId'
        ,'attr'	=>	[
        	'onsubmit'	=>	'return:false;'
        ]
	]);

	//disable in production
	$oForm->setAlwaysValidOverride();

	for($i = 0; $i<5; $i++){
		//default validator for email field is 'email'
		$oForm->email('emailField'.$i, 'pole '.$i);
	}

	$oForm->text('textField', 'labelka do text')->addValidators([
			$oForm->ruleRequired("Field is required")
			,$oForm->ruleNonNumeric("Value is numeric")
			,$oForm->ruleMaxLength("Max length of value is 100", 100)
			,$oForm->ruleMinLength("Min length of value is 10", 10)
		]);

	$oForm->submit('submit', 'submit');

	//default validator for url field is 'url'
 	/*$oForm->url('urlField', 'labelka do url');

	$oForm->textfield('textFieldArray', 'labelka do text 2');
 	$oForm->textfield('textFieldArray', 'labelka do text 3');
 	$oForm->password('passwordField', 'labelka do password');
	$oForm->textarea('textareaField', 'labelka do textarea');
	$oForm->select('selectField', 'labelka do select',[
			0	=>	'---choooose---'
			'a'	=>	'aspargan'
			,'b'	=>	'butapren'
			,'c'	=>	'cyjanek'
			'group'	=>	[
				'aa'	=>	'amylina'
				'bb'	=>	'beta-karoten'
				'cc'	=>	'cymbergaj'
			]
		]);
	$a = $oForm->select('selectHierarchyField', 'labelka do hierarchy select',[
			0	=>	'---choooose hierarchy---'
			'd'	=>	'dysplazja'
			'e'	=>	'eukarioty'
			'f'	=>	'feneloftaleina'
		]);
 	$oForm->select('selectHierarchyField', 'labelka do hierarchy select 2',[
			0	=>	'---choooose hierarchy 2---'
			'd'	=>	[
				'dd'	=>	'dendrochronologia'
				'ee'	=>	'eurypides'
				'ff'	=>	'fraktal'
			]
			,'e'	=>	[
				'gg'	=>	'gargantuiczny'
				'hh'	=>	'hipodrom'
				'ii'	=>	'idiom'
			]
			,'f'	=>	[
				'jj'	=>	'jemioła'
				'kk'	=>	'karcenogeny'
				'll'	=>	'lemiesz'
			]
		], $a);
 $oForm->radio();
 $oForm->radio();
 $oForm->radio();
 $oForm->checkbox();
 $oForm->checkbox();
 $oForm->checkbox();
 $oForm->button();
 $oForm->submit();
 $oForm->reset();*/

$aForm = $oForm->getHtmlArray();

if($oForm->isValid()){
	require_once("gratz.html");
}
else{
	require_once("form.html");
}
 //print $oForm;

?>
