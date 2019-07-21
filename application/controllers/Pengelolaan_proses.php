<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengelolaan_proses extends CI_Controller {
	var $data;
	function __construct() {
        parent::__construct();
    	$this->load->model(array('m_pengelolaan','m_guru','m_prodi','m_ruang','m_waktu','m_user'));
    }

    function konfig_save(){
		$sts = true;
		$this->db->trans_start();

		if (!empty($_POST['id'])) {			
			$param = array(
				'nilai' => $_POST['nilai'],
				'id' => $_POST['id']
			);
			$sts = $sts && $this->m_pengelolaan->update_konfig($param);
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> proses berhasil.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> proses gagal.'
			);
		}
		$this->session->set_flashdata('notif', $notif);
		redirect(base_url().'pengelolaan/konfig');
		exit(); 	
    }

    function mapel_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode mata kuliah', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama mata kuliah', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('sks', 'SKS', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('smt', 'Semester', 'required');

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('paket', 'Paket semester', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/mapel_add';
			}else{
				$url_ret = base_url().'pengelolaan/mapel_edit?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'guru' => $_POST['guru'],
					'sks' => $_POST['sks'],
					'smt' => $_POST['smt'],
					'sifat' => (isset($_POST['sifat'])?$_POST['sifat']:null),
					'paket' => $_POST['paket'],
					'jml_pert' => $_POST['jml_pert'],
					'is_univr' => (isset($_POST['is_univr'])?$_POST['is_univr']:null),
					'maks_kelas' => $_POST['maks_kelas'],
					'jns_mp' => (isset($_POST['jns_mp'])?$_POST['jns_mp']:null),
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_pengelolaan->update_mapel($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' => $_POST['kode'],
					'nama' => $_POST['nama'],
					'guru' => $_POST['guru'],
					'sks' => $_POST['sks'],
					'smt' => $_POST['smt'],
					'sifat' => (isset($_POST['sifat'])?$_POST['sifat']:null),
					'paket' => $_POST['paket'],
					'jml_pert' => $_POST['jml_pert'],
					'is_univr' => (isset($_POST['is_univr'])?$_POST['is_univr']:null),
					'maks_kelas' => $_POST['maks_kelas'],
					'jns_mp' => (isset($_POST['jns_mp'])?$_POST['jns_mp']:null)
				);
				$sts = $sts && $this->m_pengelolaan->add_mapel($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Mata pelajaran berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Mata pelajaran gagal disimpan.',
					'post' => $_POST
				);
			}
			$url_ret = base_url().'pengelolaan/mapel';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	
    }

    function prodi_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode program studi', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama program studi', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('akronim', 'Akronim', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/prodi_add';
			}else{
				$url_ret = base_url().'pengelolaan/prodi_edit?id='.$_POST['id'];
			}
		}else{

			$smt_x = null; $smt_xi = null; $smt_xii = null;
			$sw_x = null; $sw_xi = null; $sw_xii = null;
			if (!empty($_POST['smt_x']) && !empty($_POST['smt_xi']) && !empty($_POST['smt_xii'])) {
				$smt_x 		= $_POST['smt_x'];
				$smt_xi 	= $_POST['smt_xi'];
				$smt_xii 	= $_POST['smt_xii'];
				$smt 		= array($smt_x,$smt_xi,$smt_xii);
				$smt_all 	= implode(";", $smt);
			}
			if (!empty($_POST['smt_x']) && !empty($_POST['smt_xi']) && empty($_POST['smt_xii'])) {
				$smt_x 		= $_POST['smt_x'];
				$smt_xi 	= $_POST['smt_xi'];
				$smt 		= array($smt_x,$smt_xi);
				$smt_all 	= implode(";", $smt);
			}
			if (!empty($_POST['smt_x']) && empty($_POST['smt_xi']) && empty($_POST['smt_xii'])) {
				$smt_x 		= $_POST['smt_x'];
				$smt_all 	= $smt_x;
			}
			if (empty($_POST['smt_x']) && !empty($_POST['smt_xi']) && !empty($_POST['smt_xii'])) {
				$smt_xi		= $_POST['smt_xi'];
				$smt_xii 	= $_POST['smt_xii'];
				$smt 		= array($smt_xi,$smt_xii);
				$smt_all 	= implode(";", $smt);
			}
			if (!empty($_POST['smt_x']) && empty($_POST['smt_xi']) && !empty($_POST['smt_xii'])) {
				$smt_x 		= $_POST['smt_x'];
				$smt_xii 	= $_POST['smt_xii'];
				$smt 		= array($smt_x,$smt_xii);
				$smt_all 	= implode(";", $smt);
			}
			if (empty($_POST['smt_x']) && empty($_POST['smt_xi']) && !empty($_POST['smt_xii'])) {
				$smt_xii 	= $_POST['smt_xii'];
				$smt_all 	= $smt_xii;
			}
			if (empty($_POST['smt_x']) && !empty($_POST['smt_xi']) && empty($_POST['smt_xii'])) {
				$smt_xi 	= $_POST['smt_xi'];
				$smt_all 	= $smt_xi;
			}
			if (empty($_POST['smt_x']) && empty($_POST['smt_xi']) && empty($_POST['smt_xii'])) {
				$smt_all 	= null;
			}

			if (!empty($_POST['sw_x']) && !empty($_POST['sw_xi']) && !empty($_POST['sw_xii'])) {
				$sw_x 		= $_POST['sw_x'].';'.$_POST['sw_x'];
				$sw_xi 		= $_POST['sw_xi'].';'.$_POST['sw_xi'];
				$sw_xii 	= $_POST['sw_xii'].';'.$_POST['sw_xii'];
				$sw 		= array($sw_x,$sw_xi,$sw_xii);
				$sw_all 	= implode(";", $sw);
			}
			if (!empty($_POST['sw_x']) && !empty($_POST['sw_xi']) && empty($_POST['sw_xii'])) {
				$sw_x 		= $_POST['sw_x'].';'.$_POST['sw_x'];
				$sw_xi 		= $_POST['sw_xi'].';'.$_POST['sw_xi'];
				$sw 		= array($sw_x,$sw_xi);
				$sw_all 	= implode(";", $sw);
			}
			if (!empty($_POST['sw_x']) && empty($_POST['sw_xi']) && empty($_POST['sw_xii'])) {
				$sw_x 		= $_POST['sw_x'].';'.$_POST['sw_x'];
				$sw_all 	= $sw_x;
			}
			if (empty($_POST['sw_x']) && !empty($_POST['sw_xi']) && !empty($_POST['sw_xii'])) {
				$sw_xi 		= $_POST['sw_xi'].';'.$_POST['sw_xi'];
				$sw_xii 	= $_POST['sw_xii'].';'.$_POST['sw_xii'];
				$sw 		= array($sw_xi,$sw_xii);
				$sw_all 	= implode(";", $sw);
			}
			if (!empty($_POST['sw_x']) && empty($_POST['sw_xi']) && !empty($_POST['sw_xii'])) {
				$sw_x 		= $_POST['sw_x'].';'.$_POST['sw_x'];
				$sw_xii 	= $_POST['sw_xii'].';'.$_POST['sw_xii'];
				$sw 		= array($sw_x,$sw_xii);
				$sw_all 	= implode(";", $sw);
			}
			if (empty($_POST['sw_x']) && empty($_POST['sw_xi']) && !empty($_POST['sw_xii'])) {
				$sw_xii 	= $_POST['sw_xii'].';'.$_POST['sw_xii'];
				$sw_all 	= $sw_xii;
			}
			if (empty($_POST['sw_x']) && !empty($_POST['sw_xi']) && empty($_POST['sw_xii'])) {
				$sw_xi 		= $_POST['sw_xi'].';'.$_POST['sw_xi'];
				$sw_all 	= $sw_xi;
			}
			if (empty($_POST['sw_x']) && empty($_POST['sw_xi']) && empty($_POST['sw_xii'])) {
				$sw_all 	= null;
			}

			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' 		=> $_POST['kode'],
					'nama' 		=> $_POST['nama'],
					'akronim' 	=> $_POST['akronim'],
					'id' 		=> $_POST['id'],
					'smt_all'	=> $smt_all,
					'sw_all'	=> $sw_all
				);
				$sts = $sts && $this->m_prodi->update_program_studi($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' 		=> $_POST['kode'],
					'nama' 		=> $_POST['nama'],
					'akronim' 	=> $_POST['akronim'],
					'smt_all'	=> $smt_all,
					'sw_all'	=> $sw_all
				);
				// echo '<pre>'; print_r($smt_all);
				// echo '<pre>'; print_r($sw_all);exit();

				$sts = $sts && $this->m_prodi->add_program_studi($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Program studi berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Program studi gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = base_url().'pengelolaan/prodi';
		}

		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

    function guru_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nip', 'NIP dosen', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama dosen', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/guru_add';
			}else{
				$url_ret = base_url().'pengelolaan/guru_edit?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'nip' => $_POST['nip'],
					'nama' => $_POST['nama'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_guru->update_guru($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'nip' => $_POST['nip'],
					'nama' => $_POST['nama']
				);
				$sts = $sts && $this->m_guru->add_guru($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Guru berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Guru gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = base_url().'pengelolaan/guru';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

    function ruang_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kode', 'Kode ruang', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama ruang', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/ruang_add';
			}else{
				$url_ret = base_url().'pengelolaan/ruang_edit?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'kode' => $_POST['kode'],
					'prodi' => $_POST['prodi'],
					'nama' => $_POST['nama'],
					'kapasitas' => $_POST['kapasitas'],
					'is_cad' => (isset($_POST['is_cad'])?$_POST['is_cad']:null),
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_ruang->update_ruang($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'kode' => $_POST['kode'],
					'prodi' => $_POST['prodi'],
					'nama' => $_POST['nama'],
					'kapasitas' => $_POST['kapasitas'],
					'is_cad' => (isset($_POST['is_cad'])?$_POST['is_cad']:null)
				);
				$sts = $sts && $this->m_ruang->add_ruang($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Ruang berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Ruang gagal disimpan.',
					'post' => $_POST
				);
			}
			$url_ret = base_url().'pengelolaan/ruang';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

    function waktu_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/waktu_add';
			}else{
				$url_ret = base_url().'pengelolaan/waktu_edit?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'hari' => $_POST['hari'],
					'jam_mulai' => $_POST['jam_mulai'],
					'jam_selesai' => $_POST['jam_selesai'],
					'is_blj' => (isset($_POST['is_blj'])?$_POST['is_blj']:null),
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_waktu->update_waktu($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'hari' => $_POST['hari'],
					'jam_mulai' => $_POST['jam_mulai'],
					'jam_selesai' => $_POST['jam_selesai'],
					'is_blj' => (isset($_POST['is_blj'])?$_POST['is_blj']:null)
				);
				$sts = $sts && $this->m_waktu->add_waktu($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Waktu berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Waktu gagal disimpan.',
					'post' => $_POST
				);
			}
			$url_ret = base_url().'pengelolaan/waktu';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

    function waktu_guru_save(){
		$sts = true;
		$this->db->trans_start();

		$_POST['waktu'] = array_unique($_POST['waktu']);

		$this->form_validation->set_message('required', '%s harus dipilih');
		$this->form_validation->set_rules('id_guru', 'Guru', 'required');
		$this->form_validation->set_message('required', '%s harus dipilih');
		$this->form_validation->set_rules('waktu[]', 'Waktu', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/waktu_guru_add';
			}else{
				$url_ret = base_url().'pengelolaan/waktu_guru_edit?id='.$_POST['id'];
			}
		}else{

			$sts = $sts && $this->m_guru->delete_guru_waktu_by_id($_POST['id_guru']);
			foreach ($_POST['waktu'] as $key => $value) {
				$param = array(
					'id_guru' => $_POST['id_guru'],
					'id_waktu' => $value
				);
				$sts = $sts && $this->m_guru->add_guru_waktu($param);
			}				
			$this->db->trans_complete();

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Waktu Guru berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Waktu Guru gagal disimpan.',
					'post' => $_POST
				);
			}

			$url_ret = base_url().'pengelolaan/waktu_guru';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

	public function prodi_on_mapel_prodi_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('prodi', 'Program studi', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if(empty($_POST['id'])) {
				$url_ret = base_url().'pengelolaan/add_prodi_on_mapel_prodi?id='.$_POST['idmp'];
			}else{
				$url_ret = base_url().'pengelolaan/edit_prodi_on_mapel_prodi?id='.$_POST['idmp'].'&idjoin='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'porsi' => $_POST['porsi'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_prodi->update_program_studi_on_mapel_prodi($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'prodi' => $_POST['prodi'],
					'idmp' => $_POST['idmp'],
					'porsi' => $_POST['porsi'],
					'prodi_parent' => (isset($_POST['prodi_parent'])?$_POST['prodi_parent']:null)
				);
				$sts = $sts && $this->m_prodi->add_program_studi_on_mapel_prodi($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> Data berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> Data gagal disimpan.',
					'post' => $_POST
				);
			}
			$url_ret = base_url().'pengelolaan/mapel_prodi_edit?id='.$_POST['idmp'];
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit();
	}

	public function ruang_prodi_save(){
		$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->m_prodi->del_prodiru_by_idru($_POST['id']);
		if (!empty($_POST['prodi'])) {			
			foreach ($_POST['prodi'] as $a => $item) {
				$param = array(
					'id_prodi' => $item,
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_prodi->ins_prodiru($param);
			}
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> Ruang Prodi berhasil disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> Ruang Prodi gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/ruang_prodi';
		redirect($url_kelas	);
		exit();
	}

	public function prodi_ru_save(){
		$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->m_prodi->del_prodiru_by_idru($_POST['id']);
		if (!empty($_POST['prodi'])) {			
			foreach ($_POST['prodi'] as $a => $item) {
				foreach ($_POST['nama'] as $a => $item1) {
				$param = array(
					'id_prodi' => $item,
					'nama' =>$item1,
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_prodi->ins_prodiru($param);
			}
		}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> Ruang Prodi berhasil disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> Ruang Prodi gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);
		$url_ruang = base_url().'pengelolaan/ruang';
		redirect($url_ruang);
		exit();
	}

    function user_save(){
		$sts = true;
		$this->db->trans_start();

		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('nama', 'Nama User', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('email', 'Alamat Email', 'required');
		$this->form_validation->set_message('required', '%s harus diisi');
		$this->form_validation->set_rules('pwd', 'Kata Sandi', 'required');

		if ($this->form_validation->run() == FALSE){
			$notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> '.validation_errors(),
				'post' => $_POST
			);
			if (empty($_POST['id'])) {	
				$url_ret = base_url().'pengelolaan/user_add';
			}else{
				$url_ret = base_url().'pengelolaan/user_edit?id='.$_POST['id'];
			}
		}else{
			if (!empty($_POST['id'])) {	
				$param = array(
					'nama' => $_POST['nama'],
					'email' => $_POST['email'],
					'pwd' => $_POST['pwd'],
					'level' => $_POST['level'],
					'id' => $_POST['id']
				);
				$sts = $sts && $this->m_user->update_user($param);
				$this->db->trans_complete();
			}else {
				$param = array(
					'nama' => $_POST['nama'],
					'email' => $_POST['email'],
					'pwd' => $_POST['pwd'],
					'level' => $_POST['level']
				);
				$sts = $sts && $this->m_user->add_user($param);
				$this->db->trans_complete();
			}

			if ($this->db->trans_status() === true){
				$notif = array(
					'style' => 'success',
					'msg' => '<strong>Selamat!</strong> User berhasil disimpan.',
					'post' => $_POST
				);
			}else{
			    $notif = array(
					'style' => 'error',
					'msg' => '<strong>Peringatan!</strong> User gagal disimpan.',
					'post' => $_POST
				);
			}
			$url_ret = base_url().'pengelolaan/user';
		}
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	 	
    }

}