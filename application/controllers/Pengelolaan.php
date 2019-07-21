<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengelolaan extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->library('bantu');
		$this->load->model(array('m_pengelolaan','m_guru','m_prodi','m_ruang','m_waktu','m_user'));
    }

    function render_view($param, $content)
    {
    	$this->parse['parse_content'] = $this->load->view($content, $param, true);
		$this->load->view('page/view_page', $this->parse);
    }

	function index()
	{
		$param['title'] = "Pengelolaan";
		$content = 'page/view_pengelolaan';
		$this->render_view($param, $content);
	}

/****************************Konfigurasi*******************************/

	function konfig()
	{
		$param['filter'] = null;
		$param['data'] = $this->m_pengelolaan->get_konfig($param['filter']);
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$param['url_edit'] = base_url().'pengelolaan/konfig_edit';

		$param['title'] = "Konfigurasi";
		$content = 'konfig/view_konfig';
		$this->render_view($param, $content);
	}

	function konfig_edit()
	{
		$filter['id'] = $_GET['id'];
		$param['filter'] = $filter;
		$param['data'] = $this->m_pengelolaan->get_konfig($param['filter']);

		$param['url_konfig'] = base_url().'pengelolaan/konfig';
		$param['url_submit'] = base_url().'pengelolaan_proses/konfig_save';
		$param['display'] = 'style="display:none"';
		
		$param['title'] = "Ubah Konfigurasi";
		$content = 'konfig/view_konfig_edit';
		$this->render_view($param, $content);
	}

/****************************Mata Pelajaran****************************/

	function mapel()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$param['filter'] = $filter;
		$param['data'] = $this->m_pengelolaan->get_mapel($param['filter']);
		
		$param['jumlah_data'] = $this->m_pengelolaan->get_count_mapel($param['filter']);
		$url_this = base_url().'pengelolaan/mapel';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'/pengelolaan/mapel_add';
		$param['url_edit'] = base_url().'pengelolaan/mapel_edit';
		$param['url_del'] = base_url().'pengelolaan/mapel_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';

		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Mata Pelajaran";
		$content = 'mapel/view_mapel';
		$this->render_view($param,$content);
	}

	function mapel_alter($id)
	{
		$filter['id'] = $id;
		$param['filter'] = $filter;
		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['guru'] = null;
		$param['data']['gurunama'] = null;
		$param['data']['sks'] = null;
		$param['data']['jml_pert'] = null;
		$param['data']['smt'] = null;
		$param['data']['sifat'] = null;
		$param['data']['paket'] = null;
		$param['data']['is_univers'] = null;
		$param['data']['maks_kelas'] = null;
		$param['data']['jns_mp'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_pengelolaan->get_mapel_by_id($param['filter']);
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$gurunm = $this->m_guru->get_guru(0);
				//echo '<pre>'; print_r($gurunm); echo '</pre>'; exit();
		$param['gurunm'] = $gurunm;

		$arr_semester = array(
			array('id' => '', 'label' => '--pilih--'),
			array('id' => 'ganjil', 'label' => 'Ganjil'),
			array('id' => 'genap', 'label' => 'Genap'),
		);
		$param['cb_smt'] = $this->bantu->combobox($arr_semester, 'smt', $param['data']['smt']);

		$arr_semester = array(
			array('id' => 'w', 'label' => 'Wajib'),
			array('id' => 'p', 'label' => 'Pilihan')
		);
		$param['rd_sft'] = $this->bantu->radio($arr_semester, 'sifat', isset($param['data']['sifat'])?$param['data']['sifat']:null);
		
		$arr_paket = array(
			array('id' => '', 'label' => '--pilih--'),
			array('id' => '1', 'label' => 'Semester 1'),
			array('id' => '2', 'label' => 'Semester 2'),
			array('id' => '3', 'label' => 'Semester 3'),
			array('id' => '4', 'label' => 'Semester 4'),
			array('id' => '5', 'label' => 'Semester 5'),
			array('id' => '6', 'label' => 'Semester 6')
		);
		$param['cb_paket'] = $this->bantu->combobox($arr_paket, 'paket', $param['data']['paket']);

		$arr_univr = array(
			array('id' => '1', 'label' => 'Ya'),
			array('id' => '0', 'label' => 'Tidak')
		);
		$param['rd_univers'] = $this->bantu->radio($arr_univr, 'is_univr', isset($param['data']['is_univers'])?$param['data']['is_univers']:null);
		
		$arr_univr = array(
			array('id' => '0', 'label' => 'Teori'),
			array('id' => '1', 'label' => 'Praktik')
		);
		$param['rd_jns'] = $this->bantu->radio($arr_univr, 'jns_mp', isset($param['data']['jns_mp'])?$param['data']['jns_mp']:null);

		$param['url_mapel'] = base_url().'pengelolaan/mapel';
		$param['url_submit'] = base_url().'pengelolaan_proses/mapel_save';
		return $param;
	}

	function mapel_add()
	{
		$param = $this->mapel_alter(null);
		$param['title'] = "Tambah";
		$content = 'mapel/view_mapel_add';
		$this->render_view($param, $content);
	}

	function mapel_edit()
	{
		$param = $this->mapel_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'mapel/view_mapel_add';
		$this->render_view($param, $content);
	}

	function mapel_del($id)
	{
		$this->m_pengelolaan->del_mapel($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Mata pelajaran berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/mapel';
		redirect($url_kelas	);
		exit();
	}

/****************************Program Studi*****************************/

	function prodi()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$param['filter'] = $filter;
		$param['data'] = $this->m_prodi->get_program_studi($param['filter']);
		
		$param['jumlah_data'] = $this->m_prodi->get_count_program_studi($param['filter']);

		$url_this = base_url().'pengelolaan/prodi';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/prodi_add';
		$param['url_edit'] = base_url().'pengelolaan/prodi_edit';
		$param['url_del'] = base_url().'pengelolaan/prodi_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Program Studi";
		$content = 'prodi/view_prodi';
		$this->render_view($param, $content);
	}

	function prodi_alter($id)
	{

		$filter['id'] = $id;
		$param['filter'] = $filter;

		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['akronim'] = null;
		$param['S1']['smt'] = null;
		$param['S2']['smt'] = null;
		$param['S3']['smt'] = null;
		$param['S4']['smt'] = null;
		$param['S5']['smt'] = null;
		$param['S6']['smt'] = null;
		$param['S1']['jml_siswa'] = null;
		$param['S2']['jml_siswa'] = null;
		$param['S3']['jml_siswa'] = null;
		$param['S4']['jml_siswa'] = null;
		$param['S5']['jml_siswa'] = null;
		$param['S6']['jml_siswa'] = null;
		$param['S1']['checked'] = null;
		$param['S2']['checked'] = null;
		$param['S3']['checked'] = null;
		$param['S4']['checked'] = null;
		$param['S5']['checked'] = null;
		$param['S6']['checked'] = null;
		$param['S1']['disabled'] = 'disabled';
		$param['S2']['disabled'] = 'disabled';
		$param['S3']['disabled'] = 'disabled';
		$param['S4']['disabled'] = 'disabled';
		$param['S5']['disabled'] = 'disabled';
		$param['S6']['disabled'] = 'disabled';
		if ($id != null) {			
			$param['data'] = $this->m_prodi->get_program_studi_by_id($param['filter']);
			$pdsmt = explode(";", $param['data']['smt']);
			$pdsiswa = explode(";", $param['data']['jml_siswa']);
			for ($i=0; $i < count($pdsmt); $i++) { 
				$param['S'.$pdsmt[$i]] = array(
					'smt' 		=> $pdsmt[$i],
					'jml_siswa' => $pdsiswa[$i],
					'checked'	=> 'checked',
					'disabled'	=> null
				);
			}
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';

		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_prodi'] = base_url().'pengelolaan/prodi';
		$param['url_submit'] = base_url().'pengelolaan_proses/prodi_save';
		return $param;
	}

	function prodi_add()
	{
		$param = $this->prodi_alter(null);	
		$param['title'] = "Tambah";
		$content = 'prodi/view_prodi_add';
		$this->render_view($param, $content);
	}

	function prodi_edit(){
		$param = $this->prodi_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'prodi/view_prodi_add';
		$this->render_view($param, $content);
	}

	function prodi_del($id)
	{
		$this->m_prodi->del_program_studi($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Program Studi berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/prodi';
		redirect($url_kelas	);
		exit();
	}

/****************************Guru**************************************/

	function guru()
	{
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$filter['search'] = $this->input->post('search_query');
		$param['filter'] = $filter;
		$param['data'] = $this->m_guru->get_guru($param['filter']);

		$param['jumlah_data'] = $this->m_guru->get_count_guru($param['filter']);

		$url_this = base_url().'pengelolaan/guru';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/guru_add';
		$param['url_edit'] = base_url().'pengelolaan/guru_edit';
		$param['url_del'] = base_url().'pengelolaan/guru_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Guru";
		$content = 'guru/view_guru';
		$this->render_view($param, $content);
	}

	function guru_alter($id)
	{
		$filter['id'] = $id;
		$param['filter'] = $filter;

		$param['data']['nip'] = null;
		$param['data']['nama'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_guru->get_guru_by_id($param['filter']);
		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';

		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_guru'] = base_url().'pengelolaan/guru';
		$param['url_submit'] = base_url().'pengelolaan_proses/guru_save';
		return $param;
	}

	function guru_add()
	{
		$param = $this->guru_alter(null);
		$param['title'] = "Tambah";
		$content = 'guru/view_guru_add';
		$this->render_view($param, $content);
	}

	function guru_edit()
	{
		$param = $this->guru_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'guru/view_guru_add';
		$this->render_view($param, $content);
	}

	function guru_del($id)
	{
		$this->m_guru->del_guru($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/guru';
		redirect($url_kelas	);
		exit();
	}

/****************************Ruang*************************************/

	function ruang()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->m_ruang->get_ruang_prodi($param['filter']);
		$param['jumlah_data'] = $this->m_ruang->get_count_ruang_prodi($param['filter']);
		

		$url_this = base_url().'pengelolaan/ruang';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/ruang_add';
		$param['url_edit'] = base_url().'pengelolaan/ruang_edit';
		$param['url_del'] = base_url().'pengelolaan/ruang_del';
		
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Ruang";
		$content = 'ruang/view_ruang';
		$this->render_view($param, $content);
	}
	function ruang_alter($id)
	{

		$filter['id'] = $id;
		$param['filter'] = $filter;
		$param['data']['id'] = null;
		$param['data']['kode'] = null;
		$param['data']['prodinama'] = null;
		$param['data']['prodi'] = null;
		$param['data']['nama'] = null;
		$param['data']['kapasitas'] = null;
		$param['data']['is_cad'] = null;
		$param['judul'] = "Tambah";
		if ($id != null) {			
			$param['data'] = $this->m_ruang->get_ruang_by_id($param['filter']);
		}

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);

		$param['rd_iscad'] = $this->bantu->radio($arr_univr, 'is_cad', isset($param['data']['is_cad'])?$param['data']['is_cad']:null);
		
		$prodinm = $this->m_prodi->get_program_studi(0);
		//echo '<pre>'; print_r($prodinm); echo '</pre>'; exit();
		$param['prodinm'] = $prodinm;

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}


		$param['url_ruang'] = base_url().'pengelolaan/ruang';
		$param['url_submit'] = base_url().'pengelolaan_proses/ruang_save';
		return $param;
	}

	/*public function get_program_studi()
	{
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		
		if (isset($_POST['id_mp'])) {
			$filter['from'] = 'id_mp';
			$submit = 'prodi_mp_save';
			$filter['id_mp'] = $_POST['id_mp'];
			$id = $_POST['id_mp'];
		}
		if (isset($_POST['id_ru'])) {
			$filter['from'] = 'id_ru';
			$submit1 = 'prodi_ru_save';
			$filter['id_ru'] = $_POST['id_ru'];
			$id = $_POST['id_ru'];
		}
		
		$param['filter'] = $filter;
		$param['data'] = $this->m_prodi->get_prodi($param['filter']);
		$param['jumlah_data'] = $this->m_prodi->get_count_prodi($param['filter']);

		$url_this = base_url().'pengelolaan/get_program_studi';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['id'] = $id;
		$param['url_submit1'] = base_url().'pengelolaan_proses/'.$submit1;

		$content = 'page/view_prodi_ajax';
		echo $this->load->view($content, $param, true);
	}*/	
	
	function ruang_add()
	{
		$param = $this->ruang_alter(null);
		$param['title'] = "Tambah";
		$content = 'ruang/view_ruang_add';
		$this->render_view($param, $content);
	}
	
	function ruang_edit()
	{
		$param = $this->ruang_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'ruang/view_ruang_add';
		$this->render_view($param, $content);
	}

	function ruang_del($id)
	{
		$this->m_ruang->del_ruang($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/ruang';
		redirect($url_kelas	);
		exit();
	}

/****************************Waktu*************************************/

	function waktu()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->m_waktu->get_waktu($param['filter']);
		$param['jumlah_data'] = $this->m_waktu->get_count_waktu($param['filter']);

		$url_this = base_url().'pengelolaan/waktu';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/waktu_add';
		$param['url_edit'] = base_url().'pengelolaan/waktu_edit';
		$param['url_del'] = base_url().'pengelolaan/waktu_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Waktu";
		$content = 'waktu/view_waktu';
		$this->render_view($param, $content);
	}

	function waktu_alter($id)
	{
		$filter['id'] = $id;
		$param['filter'] = $filter;
		$param['data']['hari'] = null;
		$param['data']['jam_mulai'] = null;
		$param['data']['jam_selesai'] = null;
		$param['data']['is_blj'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_waktu->get_waktu_by_id($param['filter']);
		}

		$arr_hari = array(
			array('id' => '','label' => '--pilih--'),
			array('id' => 'senin', 'label' => 'Senin'),
			array('id' => 'selasa', 'label' => 'Selasa'),
			array('id' => 'rabu', 'label' => 'Rabu'),
			array('id' => 'kamis', 'label' => 'Kamis'),
			array('id' => 'jumat', 'label' => 'Jumat'),
			array('id' => 'sabtu', 'label' => 'Sabtu')
		);
		$param['cb_hari'] = $this->bantu->combobox($arr_hari, 'hari', $param['data']['hari']);

		$arr_isblj = array(
			array('id' => '1', 'label' => 'Satu Sesi'),
			array('id' => '2', 'label' => 'Dua Sesi')
		);
		$param['rd_isblj'] = $this->bantu->radio($arr_isblj, 'is_blj', isset($param['data']['is_blj'])?$param['data']['is_blj']:null);
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_waktu'] = base_url().'pengelolaan/waktu';
		$param['url_submit'] = base_url().'pengelolaan_proses/waktu_save';
		return $param;
	}

	function waktu_add()
	{
		$param = $this->waktu_alter(null);
		$param['title'] = "Tambah";
		$content = 'waktu/view_waktu_edit';
		$this->render_view($param, $content);
	}

	function waktu_edit()
	{
		$param = $this->waktu_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'waktu/view_waktu_edit';
		$this->render_view($param, $content);
	}

	function waktu_del($id)
	{
		$this->m_waktu->del_waktu($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/waktu';
		redirect($url_kelas	);
		exit();
	}

/****************************Waktu Guru********************************/

	function waktu_guru()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->m_guru->get_guru_waktu($param['filter']);
		$param['jumlah_data'] = $this->m_guru->get_count_guru_waktu($param['filter']);

		$url_this = base_url().'pengelolaan/waktu_guru';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/waktu_guru_add';
		$param['url_edit'] = base_url().'pengelolaan/waktu_guru_edit';
		$param['url_del'] = base_url().'pengelolaan/waktu_guru_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Waktu Guru";
		$content = 'waktu/view_waktu_guru';
		$this->render_view($param, $content);
	}

	function waktu_guru_alter($id){

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['id_guru'] = null;
		$param['data']['nama'] = null;

		$tabel_waktu = '';
		if ($id != null) {			
			$guru = $this->m_guru->get_guru_by_id($param['filter']);
			$param['data']['id_guru'] = $guru['id'];
			$param['data']['nama'] = $guru['nama'];

			$arr_waktu = $this->m_guru->get_waktu_guru_by_id($param['filter']);

			foreach ($arr_waktu as $key => $value) {
				$tabel_waktu .= "<tr class='".$value['id_waktu']."'>";
				$tabel_waktu .= "<td>".$value['id_waktu']."</td>";
				$tabel_waktu .= "<td>".$value['hari']."</td>";
				$tabel_waktu .= "<td>".$value['jam']."</td>";
				$tabel_waktu .= "<td>";
				$tabel_waktu .= "<button type='button' class='btn btn-xs btn-round btn-danger remove'><i class='fa fa-trash'></i> Hapus </button>";
				$tabel_waktu .= "<input type='hidden' name='waktu[]' value='".$value['id_waktu']."' >";
				$tabel_waktu .= "</td>";
				$tabel_waktu .= "</tr>";
			}			
		}
		$param['tabel_waktu'] = $tabel_waktu;		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_pilih_guru'] = base_url().'pengelolaan/get_guru';
		$param['url_pilih_waktu'] = base_url().'pengelolaan/get_waktu';
		$param['url_waktu_guru'] = base_url().'pengelolaan/waktu_guru';
		$param['url_submit'] = base_url().'pengelolaan_proses/waktu_guru_save';

		return $param;
	}

	public function get_guru()
	{
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;

		$param['data_guru'] = $this->m_guru->get_guru($param['filter']);
		$param['jumlah_data_guru'] = $this->m_guru->get_count_guru($param['filter']);

		$url_this = base_url().'pengelolaan/get_guru';
		$param['paging_guru'] = $this->bantu->getPaging($url_this, $param['jumlah_data_guru']);

		$param['url_submit'] = base_url().'pengelolaan_proses/guru_kelas_save';

		$content = 'page/view_guru_ajax';
		$this->load->view($content, $param);
	}

	public function get_waktu()
	{
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data_waktu'] = $this->m_guru->get_waktu($param['filter']);
		
		$param['jumlah_data_waktu'] = $this->m_guru->get_count_waktu($param['filter']);

		$url_this = base_url().'pengelolaan/get_waktu';
		$param['paging_waktu'] = $this->bantu->getPaging($url_this, $param['jumlah_data_waktu']);

		$param['url_submit'] = base_url().'pengelolaan_proses/guru_kelas_save';

		$content = 'page/view_waktu_ajax';
		echo $this->load->view($content, $param, true);
	}

	function waktu_guru_add()
	{
		$param = $this->waktu_guru_alter(null);
		$param['title'] = "Tambah";
		$content = 'waktu/view_waktu_guru_add';
		$this->render_view($param, $content);
	}

	function waktu_guru_edit()
	{
		$param = $this->waktu_guru_alter($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'waktu/view_waktu_guru_add';
		$this->render_view($param, $content);
	}

	function waktu_guru_del($id)
	{
		$this->m_guru->delete_guru_waktu_by_id($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/waktu_guru';
		redirect($url_kelas	);
		exit();
	}

/****************************Mapel Prodi*******************************/

	function mapel_prodi()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		$param['data'] = $this->m_prodi->get_mapel_prodi($param['filter']);

		$param['jumlah_data'] = $this->m_prodi->get_count_mapel_prodi($param['filter']);

		$url_this = base_url().'pengelolaan/mapel_prodi';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_edit'] = base_url().'pengelolaan/mapel_prodi_edit';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Mapel Prodi";
		$content = 'mapel/view_mapel_prodi';
		$this->render_view($param, $content);
	}



	function mapel_prodi_edit()
	{
		$filter['id'] = $_GET['id'];
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 

		$param['filter'] = $filter;

		$param['mapel'] = $this->m_pengelolaan->get_mapel_by_id($param['filter']);
		$param['data'] = $this->m_prodi->get_mapel_prodi_by_id($param['filter']);

		$url_this = base_url().'pengelolaan/prodi';

		$param['url_add'] = base_url().'pengelolaan/add_prodi_on_mapel_prodi?id='.$_GET['id'];
		$param['url_edit'] = base_url().'pengelolaan/edit_prodi_on_mapel_prodi?id='.$_GET['id'];
		$param['url_del'] = base_url().'pengelolaan/del_prodi_on_mapel_prodi';
		$param['url_back'] = base_url().'pengelolaan/mapel_prodi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$param['title'] = "Ubah";
		$content = 'mapel/view_mapel_prodi_edit';
		$this->render_view($param, $content);
	}

	function alter_prodi_on_mapel_prodi($idmp, $id)
	{
		$arr = array('id' => $idmp);
		$param['mapel'] = $this->m_pengelolaan->get_mapel_by_id($arr);

		$filter['id'] = $id;
		$param['filter'] = $filter;

		$param['idmp'] = $idmp;
		$param['data']['id'] = '';
		$param['data']['idmp'] = null;
		$param['data']['prodi_id'] = null;
		$param['data']['rel_id'] = null;
		$param['data']['porsi'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_prodi->get_mapel_prodi_by_idjoin($param['filter']);
			$input_prodi = '<input type="hidden" name="prodi" value="'.$param['data']['prodi_id'].'" /> ';
			$param['cb_prodi'] = $input_prodi.'<span class="form-control input-sm col-sm-3 col-xs-12">'.$param['data']['nama'].'</span>';
			$param['cb_prodi_parent'] = '<span class="form-control input-sm col-sm-2 col-xs-12">'.$param['data']['program_studi_parent'].'</span>';
		}else{
			$arr_prodi = $this->m_prodi->get_program_studi_except_in_mpid($idmp);
			$param['cb_prodi'] = $this->bantu->combobox($arr_prodi, 'prodi', $param['data']['id']);

			$arr_prodi_parent_last = $this->m_prodi->get_mapel_prodi_by_id_for_cb_parent($idmp);
			$param['cb_prodi_parent'] = $this->bantu->combobox($arr_prodi_parent_last, 'prodi_parent', $param['data']['rel_id']);

		}

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_submit'] = base_url().'pengelolaan_proses/prodi_on_mapel_prodi_save';
		return $param;
	}

	function add_prodi_on_mapel_prodi()
	{
		$idmp = $_GET['id'];	
		$param = $this->alter_prodi_on_mapel_prodi($idmp, null);
		$param['title'] = "Tambah";
		$content = 'mapel/view_prodi_on_mapel_prodi_edit';
		$this->render_view($param, $content);
	}

	function edit_prodi_on_mapel_prodi()
	{
		$idmp = $_GET['id'];	
		$idjoin = $_GET['idjoin'];	
		$param = $this->alter_prodi_on_mapel_prodi($idmp, $idjoin);
		$param['title'] = "Tambah";
		$content = 'mapel/view_prodi_on_mapel_prodi_edit';
		$this->render_view($param, $content);
	}

	function del_prodi_on_mapel_prodi($id)
	{
		$idmp[] = $this->m_prodi->get_mapel_prodi_by_idjoin($id);
		$this->m_prodi->del_program_studi_on_mapel_prodi($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/mapel_prodi'; 
		redirect($url_kelas);
		exit();
	}

/****************************Ruang Prodi*******************************/

	/*function ruang_prodi()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$param['filter'] = $filter;
		//$param['data'] = $this->m_prodi->get_ruang_prodi($param['filter']);

		//$param['jumlah_data'] = $this->m_prodi->get_count_ruang_prodi($param['filter']);

		//$url_this = base_url().'pengelolaan/ruang_prodi';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/ruang_prodi_add';
		$param['url_edit'] = base_url().'pengelolaan/ruang_prodi_edit';
		$param['url_del'] = base_url().'pengelolaan/ruang_prodi_del';
		$param['url_pilih_prodi'] =base_url().'pengelolaan/get_program_studi';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Ruang Prodi";
		$content = 'ruang/view_ruang_prodi';
		$this->render_view($param, $content);
	}

	function alter_ruang_prodi($id)
	{
		$filter['id'] = $id;
		$param['filter'] = $filter;

		$param['data']['kode'] = null;
		$param['data']['nama'] = null;
		$param['data']['kapasitas'] = null;
		$param['data']['is_cad'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_ruang->get_ruang_prodi_by_id($param['filter']);
		}

		$arr_univr = array(
			array('id' => '0', 'label' => 'Tidak'),
			array('id' => '1', 'label' => 'Ya')
		);
		$param['rd_iscad'] = $this->bantu->radio($arr_univr, 'is_cad', isset($param['data']['is_cad'])?$param['data']['is_cad']:null);
		

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_ruang_prodi'] = base_url().'pengelolaan/ruang_prodi';
		$param['url_submit'] = base_url().'pengelolaan_proses/ruang_prodi_save';
		return $param;
	}

	function ruang_prodi_add()
	{
		$param = $this->alter_ruang_prodi(null);
		$param['title'] = "Tambah";
		$content = 'page/view_ruang_prodi_edit';
		$this->render_view($param, $content);
	}

	function ruang_prodi_edit()
	{
		$param = $this->alter_ruang_prodi($_GET['id']);
		$param['title'] = "Ubah";
		$content = 'page/view_ruang_prodi_edit';
		$this->render_view($param, $content);
	}

	function ruang_prodi_del($id)
	{
		$ru_prod_id = $this->m_ruang->get_ruang_prodi_by_id($id);
		$this->m_prodi->del_program_studi_on_mapel_prodi($ru_prod_id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Guru berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_kelas = base_url().'pengelolaan/mapel_prodi';
		redirect($url_kelas	);
		exit();
	}
*/
/****************************User**************************************/

	function user()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$param['filter'] = $filter;
		$param['data'] = $this->m_user->get_user($param['filter']);
		
		$param['jumlah_data'] = $this->m_user->get_count_user($param['filter']);
		$url_this = base_url().'pengelolaan/user';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'pengelolaan/user_add';
		$param['url_edit'] = base_url().'pengelolaan/user_edit';
		$param['url_del'] = base_url().'pengelolaan/user_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';

		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}
		$param['search_query'] = $filter['search'];
		$param['title'] = "Akun User";
		$content = 'user/view_user';
		$this->render_view($param,$content);
	}

	function user_alter($id)
	{

		$filter['id'] = $id;
		$param['filter'] = $filter;


		$param['data']['nama'] = null;
		$param['data']['email'] = null;
		$param['data']['pwd'] = null;
		$param['data']['level'] = null;
		if ($id != null) {			
			$param['data'] = $this->m_user->get_user_by_id($param['filter']);
		}

		$arr_level = array(
			array('id' => '', 'label' => '--pilih--'),
			array('id' => 'admin', 'label' => 'Admin'),
			array('id' => 'kepsek', 'label' => 'Kepsek'),
			array('id' => 'guru', 'label' => 'Guru'),
			array('id' => 'siswa', 'label' => 'Siswa'),
		);
		$param['cb_level'] = $this->bantu->combobox($arr_level, 'level', $param['data']['level']);
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
			$param['data'] = $flash_message['post'];
		}

		$param['url_user'] = base_url().'pengelolaan/user';
		$param['url_submit'] = base_url().'pengelolaan_proses/user_save';
		return $param;
	}

	function user_add()
	{
		$param = $this->user_alter(null);
		$param['title'] = "Tambah";
		$content = 'user/view_user_add';
		$this->render_view($param, $content);
	}

	function user_edit()
	{
		$id = ($_GET)?$_GET['id']:$this->session->userdata('id');
		$param = $this->user_alter($id);
		$param['title'] = "Ubah";
		$content = 'user/view_user_add';
		$this->render_view($param, $content);
	}

	function user_del($id)
	{
		$this->m_pengelolaan->del_user($id);
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Mata pelajaran berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		$url_user = base_url().'pengelolaan/user';
		redirect($url_user	);
		exit();
	}

	function user_search()
	{
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$param['filter'] = $filter;
		$param['data'] = $this->m_user->get_search($param['filter']);
		$param['jumlah_data'] = $this->m_user->get_count_user($param['filter']);
		$url_this = base_url().'pengelolaan/user_search';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['url_add'] = base_url().'/pengelolaan/user_add';
		$param['url_edit'] = base_url().'pengelolaan/user_edit';
		$param['url_del'] = base_url().'pengelolaan/user_del';
		
		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';

		$param['title'] = "Cari User";
		$content = 'user/view_user';	
		$param['search_query'] = $filter['search'];
		$this->render_view($param, $content);
	}

/****************************Print Jadwal******************************/

	function printjadwal()
	{
		$smt = strtoupper($this->bantu->getConfig('semester_aktif'));
		$th_akademik = $this->bantu->getConfig('th_akademik');
		$this->load->library('pdf');
		$pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'JADWAL PELAJARAN SMA N 2 Sleman',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,"SEMESTER $smt $th_akademik",0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);

        $prodi = $this->m_prodi->get_all_prodi();
        $jadwal = $this->m_pengelolaan->cetak_jadwal();
        foreach ($prodi as $key => $pd) {
	        $pdf->SetFont('Arial','B',11);
	        $pdf->Cell(190,7,'Program Studi Keahlian: '.$pd['prodi_nama'],1,1,'C');
	        $pdf->SetFont('Arial','B',10);
	        $pdf->Cell(25,6,'Kelas',1,0);
	        $pdf->Cell(10,6,'Smt',1,0);
	        $pdf->Cell(55,6,'Mata Pelajaran',1,0);
	        $pdf->Cell(45,6,'Guru Pengampu',1,0);
	        $pdf->Cell(15,6,'Ruang',1,0);
	        $pdf->Cell(40,6,'Waktu',1,1);
	        $pdf->SetFont('Arial','',8);
	        foreach ($jadwal as $row){
	        	if ($pd['prodi_kode']==$row->kd_prodi) {
		            $pdf->Cell(25,6,$row->kelas,1,0);
		            $pdf->Cell(10,6,$row->smt,1,0);
		            $pdf->Cell(55,6,$row->mapel,1,0);
		            $pdf->Cell(45,6,$row->guru,1,0);
		            $pdf->Cell(15,6,$row->ruang,1,0);
		            $pdf->Cell(40,6,"$row->hari, $row->jam_mulai - $row->jam_selesai",1,1);
		        }
	        }
	        $pdf->Cell(10,7,'',0,1);
        }
        $pdf->Output();
	}

	function printlaporan()
	{
		$kepsek = $this->bantu->getConfig('kepsek');
		$waka_kurikulum = $this->bantu->getConfig('waka_kurikulum');
		$smt = strtoupper($this->bantu->getConfig('semester_aktif'));
		$th_akademik = $this->bantu->getConfig('th_akademik');
		$this->load->library('pdf');
		$pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'JADWAL PELAJARAN SMA N 2 Sleman',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,"SEMESTER $smt $th_akademik",0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);

        $prodi = $this->m_prodi->get_all_prodi();
        $jadwal = $this->m_pengelolaan->cetak_jadwal();
        foreach ($prodi as $key => $pd) {
	        $pdf->SetFont('Arial','B',11);
	        $pdf->Cell(190,7,'Program Studi Keahlian: '.$pd['prodi_nama'],1,1,'C');
	        $pdf->SetFont('Arial','B',10);
	        $pdf->Cell(25,6,'Kelas',1,0);
	        $pdf->Cell(10,6,'Smt',1,0);
	        $pdf->Cell(55,6,'Mata Pelajaran',1,0);
	        $pdf->Cell(45,6,'Guru Pengampu',1,0);
	        $pdf->Cell(15,6,'Ruang',1,0);
	        $pdf->Cell(40,6,'Waktu',1,1);
	        $pdf->SetFont('Arial','',8);
	        foreach ($jadwal as $row){
	        	if ($pd['prodi_kode']==$row->kd_prodi) {
		            $pdf->Cell(25,6,$row->kelas,1,0);
		            $pdf->Cell(10,6,$row->smt,1,0);
		            $pdf->Cell(55,6,$row->mapel,1,0);
		            $pdf->Cell(45,6,$row->guru,1,0);
		            $pdf->Cell(15,6,$row->ruang,1,0);
		            $pdf->Cell(40,6,"$row->hari, $row->jam_mulai - $row->jam_selesai",1,1);
		        }
	        }
	        $pdf->Cell(10,7,'',0,1);
        }
        $pdf->Cell(190,7,'',0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190,6,'Mengetahui:',0,1,'C');
        $pdf->Cell(190,1,'-------------------',0,1,'C');
        $pdf->Cell(190,10,'',0,1,'C');
        $pdf->Cell(60,6,'Kepala Sekolah',0,0,'C');
        $pdf->Cell(70,6,'',0,0);        
        $pdf->Cell(60,6,'WAKA Kurikulum',0,1,'C');
        $pdf->Cell(190,20,'',0,1);
        $pdf->Cell(60,6,"( $kepsek )",0,0,'C');
        $pdf->Cell(70,6,'',0,0);
        $pdf->Cell(60,6,"( $waka_kurikulum )",0,1,'C');

        $pdf->Output();
	}

}