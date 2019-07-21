<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjadwalan extends CI_Controller {
	var $data;

	function __construct()
	{
        parent::__construct();
        $this->load->library('bantu');
		$this->load->model('m_penjadwalan');
    }

    function render_view($param, $content)
    {
    	$this->parse['parse_content'] = $this->load->view($content, $param, true);
		$this->load->view('page/view_page', $this->parse);
    }

	public function index()
	{
		$param['title'] = "Generate";
		$content = 'kelas/view_generate_kelas';
		$this->render_view($param, $content);
	}

	function generate_kelas()
	{
		$param['url_kelas'] =base_url().'penjadwalan/kelas';
		$param['url_submit'] = base_url().'penjadwalan_proses/generating_kelas';
		$param['title'] = "Generate";
		$content = 'kelas/view_generate_kelas';
		$this->render_view($param, $content);
	}

	public function kelas()
	{
		$guru_lengkap = $this->m_penjadwalan->cek_guru_kelas_lengkap();
		$param['display_warning_gurukelas'] = !$guru_lengkap?'':'style="display:none"';
		$param['display_buat_jadwal'] = !$guru_lengkap?'style="display:none"':'';

		$jadwal = $this->m_penjadwalan->get_all_jadwal_pelajaran();
		$param['display_list_jadwal'] = count($jadwal)==0?'style="display:none"':'';

		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$filter['search'] = $this->input->post('search_query');
		$param['filter'] = $filter;
		$param['data'] = $this->m_penjadwalan->get_kelas($param['filter']);
		$param['jumlah_data'] = $this->m_penjadwalan->get_count_kelas($param['filter']);

		$url_this = base_url().'penjadwalan/kelas';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$param['url_pilih_guru'] = base_url().'penjadwalan/get_guru';
		$param['url_proses_jadwal'] = base_url().'penjadwalan/generate_jadwal';
		$param['url_list_jadwal'] = base_url().'penjadwalan/jadwal_pelajaran';
		$param['search_query'] = $filter['search'];
		$param['title'] = "Kelas";
		$content = 'kelas/view_kelas';
		$this->render_view($param, $content);
	}

	function reset_kls()
	{
		$this->m_penjadwalan->del_jadwal();
		$this->m_penjadwalan->del_grkelas_ref_kelas();
		$this->m_penjadwalan->del_record_kelas();
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Kelas berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		redirect('penjadwalan/kelas');	
	}

	function reset_jadwal()
	{
		$this->m_penjadwalan->del_jadwal();
		$notif = array('style' => 'success',
					   'msg' => '<strong>Selamat!</strong> Jadwal Pelajaran berhasil dihapus.'
					  );
		$this->session->set_flashdata('notif', $notif);
		redirect('penjadwalan/jadwal_pelajaran');
	}

	public function get_guru()
	{
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');  
		$filter['idkls'] = $_POST['idkls'];
		$param['filter'] = $filter;

		$param['data_guru'] = $this->m_penjadwalan->get_guru($param['filter']);
		$param['jumlah_data_guru'] = $this->m_penjadwalan->get_count_guru($param['filter']);

		$url_this = base_url().'penjadwalan/get_guru';
		$param['paging_guru'] = $this->bantu->getPaging($url_this, $param['jumlah_data_guru']);

		$param['id_kelas'] = $_POST['idkls'];
		$param['url_submit'] = base_url().'penjadwalan_proses/save_guru_kelas';

		$content = 'page/view_guru_kelas_ajax';
		echo $this->load->view($content, $param, true);
	}	

	function generate_jadwal()
	{
		$param['url_jadwal'] = base_url().'penjadwalan/jadwal_pelajaran';
		$param['url_submit'] = base_url().'penjadwalan_proses/generating_jadwal';
		$param['display'] = 'style="display:none"';
		$param['title'] = "Generate";
		$content = 'jadwal/view_generate_jadwal';
		$this->render_view($param, $content);
	}

	function jadwal_pelajaran(){
		// $kelas = $this->m_penjadwalan->get_all_kelas();
		$ruang = $this->m_penjadwalan->get_ruang();
		$waktu = $this->m_penjadwalan->get_all_waktu();
		$jadwal = $this->m_penjadwalan->get_all_jadwal_pelajaran();

		$param['display'] = 'style="display:none"';
		$param['notif_style'] = '';
		$param['notif_message'] = '';
		$flash_message = $this->session->flashdata('notif');
		if ($flash_message) {
			$param['display'] = '';
			$param['notif_style'] = $flash_message['style'];
			$param['notif_message'] = $flash_message['msg'];
		}

		$grup_hari = array('senin','selasa','rabu','kamis','jumat');
		$waktu_transform = array();
		foreach ($grup_hari as $key => $value) {
			$waktu_transform[$key]['hari'] = $value;
			$waktu_transform[$key]['data'] = array();
			$jam_ke = 1;
			foreach ($waktu as $i => $item) {

				if ($value == $item['waktu_hari'] AND $item['waktu_hari'] == 'jumat' AND $jam_ke == 6) {
					$jam_ke = $jam_ke + 2;
				}
				if ($value == $item['waktu_hari'] AND $item['waktu_hari'] == 'senin' AND $jam_ke == 1) {
					$jam_ke = $jam_ke + 1;
				}

				if ($value == $item['waktu_hari']) {
					$item['jam_ke'] = $jam_ke++;
					$waktu_transform[$key]['data'][] = $item;
				}
			}
		}


		$table_header = '<thead style="position:relative;"><tr >';
		$table_header .= '<th style="vertical-align:middle;text-align:center;width:100px;" rowspan="2">R/W</th>';
		$table_header2 = '<tr>';
		$jml_kolom_data_header = count($waktu);
		foreach ($waktu_transform as $key => $value) {

			if ($value['hari']=='senin') {
				$table_header .= '<th style="text-align:center;" colspan="7" >'.$value['hari'].'</th>';
			}elseif ($value['hari']=='jumat') {
				$table_header .= '<th style="text-align:center;" colspan="4" >'.$value['hari'].'</th>';
			}else{
				$table_header .= '<th style="text-align:center;" colspan="8" >'.$value['hari'].'</th>';			
			}

			foreach ($value['data'] as $i => $item) {
				$table_header2 .= '<th style="width:300px;text-align:center;">'.$item['jam_ke'].'</th>';
			}
		}
		$table_header2 .= '</tr>';
		$table_header .= '</tr>';
		$table_header .= $table_header2;
		$table_header .= '</thead>';

		$table_body = '<tbody>';
		foreach ($ruang as $key => $value) {
			$table_body .= '<tr style="height:80px;">';
			$table_body .= '<td style="vertical-align:middle;text-align:center;">'.$value['ru_kode'].'</td>';
			$period = 0;
			// echo $value['ru_id'].' : ';
			foreach ($waktu as $i => $time) {
				$label = '';
				$edit = '';
				$colspan = '';
				$style = '';
				if ($period == 0) {
					foreach ($jadwal as $j => $item) {
						if ($value['ru_id'] == $item['jp_ru_id'] AND $time['waktu_id'] == $item['jp_wkt_id']) {
							$label = $item['kd_prodi'].' '.$item['label_kls'].' Semester '.$item['smt'].'<br>'.$item['nama_mapel'].'<br>'.$item['jp_label'];
							if ($this->session->userdata('level') == 'admin') {
								$edit = '<div>
										<a href="'.base_url('penjadwalan/jadwal_edit').'?id='.$item['jp_id'].'" class="btn btn-xs btn-round btn-primary"><span class="fa fa-edit"></span> Ubah </a>
										</div>';
							}
							//$colspan = 'colspan="'.$item['jp_period'].'"';
							$style = 'style="background-color: #F0F0C5;border-color: #E0E08D;"';
							//$period = $item['jp_period']-1;
							// echo $i.', ';
							
						}
					}
					$table_body .= '<td '.$colspan.' '.$style.' >'.$label.' '.$edit.'</td>';
				}else{
					$period--;
				}
				
			}
			
			$table_body .= '</tr>';
		}
		$table_body .= '</tbody>';

		$param['table_header'] = $table_header;
		$param['table_body'] = $table_body;
		$param['url_cetak_jadwal'] =base_url().'pengelolaan/printjadwal';
		$param['url_cetak_laporan'] =base_url().'pengelolaan/printlaporan';

		$param['title'] = "Jadwal";
		$content = 'jadwal/view_jadwal';
		$this->render_view($param, $content);
	}
    
	function jadwal_edit(){

		$ruang = $this->m_penjadwalan->get_ruang();
		$waktu = $this->m_penjadwalan->get_all_waktu();
		$jadwal = $this->m_penjadwalan->get_all_jadwal_edit();
		$jadwal_edit = $this->m_penjadwalan->get_jadwal_by_id($_GET['id']);

		$free_time = null;
		foreach ($ruang as $a => $ru) {
			foreach ($waktu as $b => $wt) {
				$times[] = array(
					'id_ruang'          => $ru['ru_id'],
					'nama_ruang'		=> $ru['ru_nama'],
                    'id_waktu'          => $wt['waktu_id'],
                    'waktu_hari'        => $wt['waktu_hari'],
                    'waktu_jam_mulai'   => $wt['waktu_jam_mulai'],
                    'waktu_jam_selesai' => $wt['waktu_jam_selesai'],
                    'waktu_period'      => $wt['waktu_is_belajar'],
                    'label'             => $ru['ru_nama'].', '.$wt['waktu_hari'].' '.$wt['waktu_jam_mulai'].'-',
                    'jns_ruang'        	=> $ru['ru_jenis']
				);
			}
		}

		for ($i=0; $i < count($times); $i++) {
			$status = true;
			for ($j=0; $j < count($jadwal); $j++) { 
				if (
					($times[$i]['jns_ruang']!=$jadwal_edit[0]['jns_mapel'] || $times[$i]['waktu_period']!=$jadwal_edit[0]['jp_period']) ||
					($times[$i]['id_ruang']==$jadwal[$j]['jp_ru_id'] && $times[$i]['id_waktu']==$jadwal[$j]['jp_wkt_id']) || 
					($jadwal_edit[0]['guru_id']==$jadwal[$j]['guru_id'] && $times[$i]['id_waktu']==$jadwal[$j]['jp_wkt_id'])
				   ) {
					$status = false;
					break;
				}
			}
			if ($status) {
				$free_time[] = array(
					'id_ruang' 			=> $times[$i]['id_ruang'],
					'nama_ruang' 		=> $times[$i]['nama_ruang'],
					'id_waktu' 			=> $times[$i]['id_waktu'],
					'waktu_hari'		=> $times[$i]['waktu_hari'],
					'jam_mulai'			=> $times[$i]['waktu_jam_mulai'],
					'jam_selesai'		=> $times[$i]['waktu_jam_selesai']
				);
			}
		}

		$param['data_mapel'] = $jadwal_edit;
		$param['data_waktu'] = $free_time;
		$param['url_jadwal'] = base_url().'penjadwalan/jadwal_pelajaran';
		$param['url_submit'] = base_url().'penjadwalan/jadwal_edit_save';
		$param['notif_style'] = 'success';
		$param['display'] = 'style="display:none"';
		$param['notif_message'] = '';
		$param['title'] = "Ubah";
		$content = 'jadwal/view_jadwal_edit';
		$this->render_view($param, $content);		
	}

	function jadwal_edit_save(){
		$sts = true;
		$this->db->trans_start();
		if (isset($_POST['waktu'])) {
			$arr_waktu = explode('-', $_POST['waktu']);
			$param = array(
				'id' 	=> $arr_waktu[0], 
				'id_wk' => $arr_waktu[1], 
				'id_ru' => $arr_waktu[2], 
				'jm' 	=> $arr_waktu[3],
				'js' 	=> $arr_waktu[4],
				'rn' 	=> $arr_waktu[5],
				'wh' 	=> $arr_waktu[6]
			);
			$sts = $sts && $this->m_penjadwalan->update_jadwal($param);
			$this->db->trans_complete();
		}
		if ($this->db->trans_status() === true){
			$notif = array(
				'style' => 'success',
				'msg' => '<strong>Selamat!</strong> Jadwal Berhasil Diubah.',
			);
		}else{
		    $notif = array(
				'style' => 'error',
				'msg' => '<strong>Peringatan!</strong> Jadwal Gagal Diubah.',
			);
		}
		$url_ret = base_url().'penjadwalan/jadwal_pelajaran';
		$this->session->set_flashdata('notif', $notif);
		redirect($url_ret);
		exit(); 	
	}

	function jadwal_list()
	{
		if (isset($_GET['pd'])) {
			$filter['pd'] = $_GET['pd'];
		}
		$filter['search'] = $this->input->post('search_query');
		$filter['start'] = $this->uri->segment(3)!=null?$this->uri->segment(3):0; 
		$filter['display'] = $this->bantu->getConfig('item_per_page');
		$param['filter'] = $filter;
		$param['data'] = $this->m_penjadwalan->list_jadwal($param['filter']);
		$param['prodi'] =$this->m_penjadwalan->get_all_prodi();

		$param['jumlah_data'] = $this->m_penjadwalan->get_count_jadwal($param['filter']);
		$url_this = base_url().'penjadwalan/jadwal_list';
		$param['paging'] = $this->bantu->getPaging($url_this, $param['jumlah_data']);
		
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
		$param['url_cetak_jadwal'] =base_url().'pengelolaan/printjadwal';
		$param['url_cetak_laporan'] =base_url().'pengelolaan/printlaporan';
		$param['title'] = "Jadwal";
		$content = 'jadwal/view_jadwal_list';
		$this->render_view($param,$content);
	}

}