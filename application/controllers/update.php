<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends CI_Controller {
	
	
	public function index($cif) {
		
		
		$this->load->model('Updater');
		
		$this->output->enable_profiler(TRUE);
	
		
		for($cnt =  0 ; $cnt < 100 ; $cnt ++) {
			
			$cif = $this->Updater->next_cif($cif);
			
			$this->benchmark->mark('start');
			$this->Updater->firma($cif);
			$this->benchmark->mark('stop');
			
			$this->output->append_output($cif." - ".$this->benchmark->elapsed_time('start', 'stop')."<br />");
			
		
		}

	}	
	
	
}