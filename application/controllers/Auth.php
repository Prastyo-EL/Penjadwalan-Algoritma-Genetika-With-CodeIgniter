<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		$param['display'] = 'style="display:none"';
		$param['notif_message'] = '';
		$this->load->view('page/view_login',$param);
	}

	public function cek_login()
	{
		$this->load->model('m_user');
		$data = array('user_nama' => $this->input->post('username'),
					  'user_pwd' => $this->input->post('password')
				);
		$hasil = $this->m_user->cek_user($data);
		if ($hasil->num_rows() == 1){
			foreach($hasil->result() as $sess)
            {
              $sess_data['id'] = $sess->user_id;
              $sess_data['username'] = $sess->user_nama;
              $sess_data['email'] = $sess->user_email;
              $sess_data['level'] = $sess->user_level;
              $this->session->set_userdata($sess_data);
            }

			redirect('home');
		}
		else
		{
			$param['display'] = '';
			$param['notif_message'] = '<strong>Peringatan!</strong> <p>Username atau Password Salah!</p>';
			$this->load->view('page/view_login',$param);
		}
		
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('auth');
	}

}