<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CoreController {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		// Enable the 'head' layout
		$this->layout->use('head', TRUE);
		
		// Set the 'view' layout to 'welcome'
		$this->layout->use('view', 'welcome');

		// insert image to use in style
		$this->layout->style('shakuganNoShana', resource('/images/shakugan-no-shana.jpg'));
		
		// Render the layout
		$this->layout->render();
	}
}
