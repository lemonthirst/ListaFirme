<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends CI_Controller {
	
	
	public function index() {
		
		$this->load->model('Updater');
		
		echo "<pre>";
		print_r($this->Updater->get_firma_mfinante('28247354'));
		
		
		
	}	
	
	
}