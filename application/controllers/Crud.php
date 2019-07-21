<?php 
 
class Crud extends CI_Controller{
 
	function __construct(){
		parent::__construct();		
		$this->load->model('M_data');
                $this->load->helper('url');
	}
 
	function index(){
		$data['kelas'] = $this->M_data->tampil_data()->result();
		$this->load->view('Crud/V_tampil',$data);
	}
	function tambah(){
		$this->load->view('Crud/V_input');
	}

	function tambah_aksi(){
		$id = $this->input->post('Id');
		$prodi = $this->input->post('Prodi');
		$mpkur = $this->input->post('Kurikulum');
		$nama = $this->input->post('Nama');
		$paralel = $this->input->post('Kelas >1');
		$prediksi = $this->input->post('Jumlah');
		$rata = $this->input->post('Wajib/Non');
		$grup = $this->input->post('Group');


 
		$data = array(
			'kls_id' => $id,
			'kls_kode_prodi' => $prodi,
			'kls_mpkur_id' => $mpkur,
			'kls_nama' => $nama,
			'kls_kode_paralel' => $paralel,
			'kls_jml_peserta_prediksi' => $prediksi,
			'kls_jadwal_merata' => $rata,
			'kls_id_grup_jadwal' => $grup,
			);
		$this->M_data->input_data($data,'kelas');
		redirect('crud/index');
	}

	function hapus($kls_id){
		$where = array('kls_id'=>$kls_id);
		$this->M_data->hapus_data($where,'kelas');
		redirect('crud/index');
	}

	function edit($id){
		$where = array('kls_id' => $id);
		$data['kelas'] = $this->M_data->edit_data($where,'kelas')->result();
		$this->load->view('V_edit',$data);
	}
	function update(){
		$id = $this->input->post('Id');
		$prodi = $this->input->post('Prodi');
		$mpkur = $this->input->post('Kurikulum');
		$nama = $this->input->post('Nama');
		$paralel = $this->input->post('Kelas >1');
		$prediksi = $this->input->post('Jumlah');
		$rata = $this->input->post('Wajib/Non');
		$grup = $this->input->post('Group');

	 
		$data = array(
			'kls_id' => $id,
			'kls_kode_prodi' => $prodi,
			'kls_mpkur_id' => $mpkur,
			'kls_nama' => $nama,
			'kls_kode_paralel' => $paralel,
			'kls_jml_peserta_prediksi' => $prediksi,
			'kls_jadwal_merata' => $rata,
			'kls_id_grup_jadwal' => $grup,
		);
	 
		$where = array(
			'kls_id' => $id
		);
	 
		$this->M_data->update_data($where,$data,'kelas');
		redirect('crud/index');
	}
}