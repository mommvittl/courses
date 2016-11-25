<?php
abstract class basePage{
	public $loader;
	public $twig;
	public $template;
	public $templateFile = 'base.tmpl';
	
	public function __construct($templateFileData){	
		if(file_exists(APP . 'templates' . DIRECTORY_SEPARATOR . $templateFileData )){
			$this->templateFile = $templateFileData;
		}
	}
	
	abstract public function displayPage($arrParameter = []);

}