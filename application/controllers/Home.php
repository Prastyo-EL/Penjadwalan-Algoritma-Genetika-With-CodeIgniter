<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	var $parse;
	public function index()
	{
		$data['title'] = "Home";
		$content = 'page/view_home';
		$this->parse['parse_content'] = $this->load->view($content, $data, true);
		$this->load->view('page/view_page', $this->parse);
	}	
}