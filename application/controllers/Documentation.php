<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentation extends CoreController {

	public function __construct()
	{
		parent::__construct();

		// use config now
		$this->layout->config('documentation');
	}

	public function home()
	{
		// Enable the 'head' layout
		$this->layout->use('head', TRUE);
		$this->layout->use('draw', TRUE);

		// load javascript
    $this->layout->source('javascript', [
      // service
      base_url('application/views/@service/BootstrapToast.js'),
    ]);
		
		// Set the 'view' layout
		$this->layout->use('view', 'home.index');
		
		// Render the layout
		$this->layout->render();
	}
}
