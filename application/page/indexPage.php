<?php
class indexPage extends basePage{
	
	public function displayPage($arrParameter = []){
		
		require_once APP . 'Twig/Autoloader.php';
		Twig_Autoloader::register();
		try{
			$this->loader = new Twig_Loader_Filesystem(APP . 'templates');
			$this->twig = new Twig_Environment($this->loader);
			$this->template = $this->twig->loadTemplate( $this->templateFile );
			echo $this->template->render( $arrParameter);
		} catch(Exeption $e){
			exit('Sorry...Error: '.$e->getMessage());
		}
	}

}