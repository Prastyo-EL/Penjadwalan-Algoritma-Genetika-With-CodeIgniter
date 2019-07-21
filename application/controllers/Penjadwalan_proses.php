<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjadwalan_proses extends CI_Controller {
	var $data;

	function __construct() {
        parent::__construct();
        $this->load->library(array('bantu','algen'));
        $this->load->model('m_penjadwalan');
    }

/**********************************Simpan Guru Kelas**************************************************/

	public function save_guru_kelas(){
		$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->m_penjadwalan->del_grkelas_by_idkelas($_POST['id_kelas']);
		if (!empty($_POST['guru'])) {			
			foreach ($_POST['guru'] as $a => $item) {
				$param = array(
					'id_guru' => $item,
					'id_kelas' => $_POST['id_kelas']
				);
				$sts = $sts && $this->m_penjadwalan->ins_gurukelas($param);
			}
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> Guru kelas berhasil disimpan.'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> Guru kelas gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_kelas = base_url().'penjadwalan/kelas';
		redirect($url_kelas	);
		exit();
	}

/**********************************Fungsi Generate Kelas**********************************************/
	
public function generating_kelas(){

	/* PENTING!!! dalam pembangkitan kelas, jumlah peserta per kelas tidak boleh melebihi rata'' kapasitas kelas*/

	$url_kelas = base_url().'penjadwalan/kelas';
	if (isset($_POST['back'])) {
		redirect($url_kelas, 'refresh');
	}
	$semester_aktif = $this->bantu->getConfig('semester_aktif');
	$data_mapel = $this->m_penjadwalan->get_data_mapel($semester_aktif);
	
	// echo '<pre>'; print_r($_POST); echo '</pre>';
	// echo '<pre>'; print_r($data_mapel); echo '</pre>'; exit();

	if (!empty($data_mapel)) {
		foreach ($data_mapel as $key => $value) {
			// $maks_kelas = ($value['maks_kelas']!=null)?$value['maks_kelas']:$_POST['batas_jml_kelas'];
			$maks_kelas = $_POST['batas_jml_kelas'];
			$is_bersama = substr($value['kode'], 2, 1)=='B'?1:0;
			$param = array(
				'idmp' => $value['id'],
				'kode' => $value['kode'],
				'nama' => $value['nama'],
				'jml_pert' => $value['jml_pert'],
				'pkt_smt' => $value['pkt_smt'],
				'jml_peminat' => $value['jml_peminat'],
				'batas_jml_kelas_min' => $_POST['batas_jml_kelas_min'],
				'batas_jml_kelas' => $maks_kelas,
				'is_universal' => $value['is_universal'],
				'sifat' => $value['sifat'],
				'is_bersama' => $is_bersama
			);
			$arr_kelas[] = $this->build_kelas_per_mapel($param);
		}

		// echo 'daftar kelas : <pre>'; print_r($arr_kelas);
		// exit();
	}

	if (!empty($arr_kelas)) {
		foreach ($arr_kelas as $key => $value) {
			if (!empty($value[0])) {
				foreach ($value as $i => $item) {
					$arr_kelas_new[] = $item;
				}
			}
		}
	}

	// echo '<pre>'; print_r($data_mapel);
	// echo 'Semua Kelas: <pre>'; print_r($arr_kelas_new);exit();

	$sts = true;
	if (!empty($arr_kelas_new)) {
		$this->db->trans_start();

		$sts = $sts && $this->m_penjadwalan->del_grkelas_ref_kelas();
		$sts = $sts && $this->m_penjadwalan->del_record_kelas();
		foreach ($arr_kelas_new as $a => $item) {
			$sts = $sts && $this->m_penjadwalan->ins_kelas($item);
		}

		$this->db->trans_complete();

	}

	if ($this->db->trans_status() === true){
		$notif = array(
			'style' => 'success',
			'msg' => '<strong>Selamat!</strong> Kelas berhasil dibuat.'
		);
	}else{
		$notif = array(
			'style' => 'error',
			'msg' => '<strong>Peringatan!</strong> Kelas gagal dibuat.'
		);
	}

	$this->session->set_flashdata('notif', $notif);
	redirect($url_kelas);
	exit();
}

function build_kelas_per_mapel($param){
	$mpprodi = $this->m_penjadwalan->get_base_mpprodid_by_mpid($param['idmp']);
	$prodi_smt = $this->m_penjadwalan->get_prodi_smt_by_mp_smt($param['pkt_smt']);

	$kelas = array();
	
	//if($param['jml_peminat'] >= $param['batas_jml_kelas_min'] && $param['jml_peminat'] <= $param['batas_jml_kelas']){

	// Kelas universal
	if ($param['is_universal']==1 OR count($mpprodi)==0) {
	
		$nama_kelas = '';
		foreach ($prodi_smt as $key => $item) {
			$pdsmt = explode(";", $item['prodi_smt']);
			$pdsiswa = explode(";", $item['prodi_jml_siswa']);
			for ($i=0; $i < count($pdsmt); $i++) { 
				if ($pdsmt[$i] == $param['pkt_smt']) {
					$jml_siswa = $pdsiswa[$i];
				}
			}
			if ($jml_siswa > $param['batas_jml_kelas']) {
				
				$param_klsf = array(
					"jml_porsi" => $jml_siswa,
					"batas_jml_kelas" => $param['batas_jml_kelas'],
					"jml_pert" => $param['jml_pert'],
					"pkt_smt" => $param['pkt_smt'],
					"prodi_mapel" => $item['prodi_kode'],
					"kode_mapel" => $param['kode'],
					"nama_mapel" => $param['nama'],
					"id_mapel" => $param['idmp'],
					"uni" => true,
					"bersama" => $param['is_bersama']
				);
				$kelas_temp = $this->klasifikasi($param_klsf);
				foreach ($kelas_temp as $key => $kls) {
					$kelas[] = $kls;
				}

			}else{
				
				$kelas[] = array(
					'kelas' 				=> null,
					'prodi' 				=> $item['prodi_kode'],
					'nama_kelas'	 		=> $item['prodi_kode'].'-'.$param['kode'],
					'jumlah_per_kelas' 		=> $jml_siswa,
					'jml_pert' 				=> $param['jml_pert'],
					'pkt_smt' 				=> $param['pkt_smt'],
					'kode_mapel' 			=> $param['kode'],
					'nama_mapel' 			=> $param['nama'],
					'id_mapel' 				=> $param['idmp'],
					'kls_jadwal_merata' 	=> 1,
					'kls_id_grup_jadwal' 	=> null
				);
			}
		}

	}else{

		// Kelas Prodi
		foreach ($mpprodi as $key => $value) {

			$pdsmt = explode(";", $value['prodi_smt']);
			$pdsiswa = explode(";", $value['prodi_jml_siswa']);
			$kd_prd = $value['prodi_kode'];
			for ($i=0; $i < count($pdsmt); $i++) { 
				if ($pdsmt[$i] == $param['pkt_smt']) {
					$jml_siswa = $pdsiswa[$i];
				}
			}

			if ($jml_siswa > $param['batas_jml_kelas']) {
				$param_klsf = array(
					"jml_porsi" => $jml_siswa,
					"batas_jml_kelas" => $param['batas_jml_kelas'],
					"jml_pert" => $param['jml_pert'],
					"pkt_smt" => $param['pkt_smt'],
					"prodi_mapel" => $value['prodi_kode'],
					"kode_mapel" => $param['kode'],
					"nama_mapel" => $param['nama'],
					"id_mapel" => $param['idmp'],
					"uni" => false,
					"bersama" => $param['is_bersama']
				);
				$kelas_temp = $this->klasifikasi($param_klsf);
				foreach ($kelas_temp as $key => $kls) {
					$kelas[] = $kls;
				}

			}else{

				$kelas[] = array(
					'kelas' 				=> null,
					'prodi' 				=> $value['prodi_kode'],
					'nama_kelas' 			=> $value['prodi_kode'].'-'.$param['kode'],
					'jumlah_per_kelas' 		=> $jml_siswa,
					'jml_pert' 				=> $param['jml_pert'],
					'pkt_smt' 				=> $param['pkt_smt'],
					'kode_mapel' 			=> $param['kode'],
					'nama_mapel' 			=> $param['nama'],
					'id_mapel' 				=> $param['idmp'],
					'kls_jadwal_merata' 	=> $param['is_bersama'],
					'kls_id_grup_jadwal' 	=> null
				);
			}
		}
	}
	return $kelas;    	
}

function klasifikasi($param){
	$kelas_bagi = ceil($param['jml_porsi']/$param['batas_jml_kelas']);
	$mod = $param['jml_porsi'] % $kelas_bagi;
	for ($i=0; $i < $kelas_bagi; $i++) {
		
			$jumlah_per_kelas = floor($param['jml_porsi'] / $kelas_bagi);
			
			if ($mod > 0 and $i==0) {
				$jumlah_per_kelas = ( floor($param['jml_porsi'] / $kelas_bagi)) + $mod;
			}
			$kelas_nama = '';
			if ($param['uni']) {
				if ($kelas_bagi>1) {
					$kelas_nama = chr($i+65);
				}
				if ($param['prodi_mapel'] == '') {
					$nama_kelas = $param['kode_mapel'].'-'.($kelas_nama!=''?$kelas_nama:'');
				}else{
					$nama_kelas = $param['prodi_mapel'].'-'.($kelas_nama!=''?$kelas_nama.'-'.$param['kode_mapel']:$param['kode_mapel']);
				}
				$kls_jadwal_merata = 1;

			}else{

				if ($kelas_bagi>1) {
					$kelas_nama = chr($i+65);
				}
				if ($param['prodi_mapel'] == '') {
					$nama_kelas = $param['kode_mapel'].'-'.($kelas_nama!=''?$kelas_nama:'');
				}else{
					$nama_kelas = $param['prodi_mapel'].'-'.($kelas_nama!=''?$kelas_nama.'-'.$param['kode_mapel']:$param['kode_mapel']);
				}
				$kls_jadwal_merata = $param['bersama'];
				
			}
			$kelas[] = array(
				'kelas' => $kelas_nama,
				'prodi' => $param['prodi_mapel'],
				'nama_kelas' => $nama_kelas,
				'jumlah_per_kelas' => $jumlah_per_kelas,
				'kode_mapel' => $param['kode_mapel'],
				'nama_mapel' => $param['nama_mapel'],
				'id_mapel' => $param['id_mapel'],
				'kls_jadwal_merata' => $kls_jadwal_merata,
				'kls_id_grup_jadwal' => null
			);
	}

	return $kelas;
}

/**********************************Fungsi Generate Jadwal********************************************/

	function generating_jadwal(){
		
		$kelas = $this->m_penjadwalan->get_all_kelas();
		$ruang = $this->m_penjadwalan->get_all_ruang();
		$waktu = $this->m_penjadwalan->get_all_waktu();
		$prodi = $this->m_penjadwalan->get_all_prodi();

		if (!empty($kelas) && !empty($ruang) && !empty($waktu) ) {
			foreach ($kelas as $key => $value) {
				$guru = $this->m_penjadwalan->get_idguru_by_idkelas($value['id']);
				$kelas[$key]['guru'] = $guru;
			}

			$min_prosen_capacity = $this->bantu->getConfig('min_persen_kelas');
			
			/*
			** PROSES ALGORITMA GENETIKA
			*/
			$waktu_start = microtime(true);
			$this->algen->initialize($kelas, $ruang, $waktu, $_POST, $prodi, $min_prosen_capacity);
			for ($i=0; $i < $_POST['generation']; $i++) {
				if ($i == 0) {
					$this->algen->generate_population();
				}else{
					$this->algen->update_population();
				}
				
				$this->algen->count_fitness();
				$this->algen->roulette_wheel_selection();
				$this->algen->crossover();
				$this->algen->mutation();
				$this->algen->update_selection();

			}
			$solusi = $this->algen->get_solution();
			$solusi = $solusi['arr_gen'];
			// $total_waktu = microtime(true) - $waktu_start;
			$total_waktu = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

			// $result = $this->bantu->getDataLogproses('algen_penjadwalan');
			// echo 'max_fitness: <pre>'; print_r($result['max_fitness']); echo '</pre>';
			// echo '<pre>Total Waktu: '; print_r($total_waktu); echo '</pre>';
			// echo '<pre>Solusi: '; print_r($solusi); echo '</pre>';
			// exit();
		}


    	// echo '<pre>'; print_r($_POST); 
    	// exit();

    	$sts = true;
		$this->db->trans_start();
		$sts = $sts && $this->m_penjadwalan->del_jadwal();
		if (!empty($solusi)) {			
			foreach ($solusi as $a => $item) {
				$param = array(
					'id_kelas' => $item['id_kelas'],
					'id_waktu' => $item['id_waktu'],
					'id_ruang' => $item['id_ruang'],
					'period' => $item['period'],
					'label' => $item['label_timespace']
				);
				$sts = $sts && $this->m_penjadwalan->ins_jadwal($param);
			}
			$this->db->trans_complete();
		}

    	if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> Jadwal pelajaran berhasil disimpan. Waktu pemrosesan algoritma genetika <strong>'.$total_waktu.'</strong> detik dengan '.$_POST['generation'].' iterasi .'
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> Jadwal kuliah gagal disimpan.'
			);
		}
		$this->session->set_flashdata('notif', $notif);

		$url_jadwal = base_url().'penjadwalan/jadwal_pelajaran';
		redirect($url_jadwal);
		exit();
    }

}