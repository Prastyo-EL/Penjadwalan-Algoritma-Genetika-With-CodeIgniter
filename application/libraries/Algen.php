<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time', 0);

class Algen {
    var $CI;
    var $populasi = array();
    var $pc = null;
    var $pm = null;
    var $kelas = null;
    var $ruang = null;
    var $waktu = null;
    var $timespace = null;
    var $post = null;
    var $prodi = null;
    var $min_prosen_capacity = null;
    var $populasi_breeding = array();
    var $populasi_breeding_selected = array();
    var $total_fitness = 0;
    var $individu_breed = array();
    var $individu_update_calon = array();
    var $populasi_baru = array();
    var $kromosom = array();
    var $err_msg = '';
    var $status_rule = '';

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->library('bantu');
        $this->CI->load->library('aturan_jadwal');
    }

/**************************Initialize*********************************/

    public function initialize($kelas, $ruang, $waktu, $post, $prodi, $min_prosen_capacity){
        
        $this->ruang = $ruang;
        $this->waktu = $waktu;
        $this->post = $post;
        $this->pc = $post['pc'];
        $this->pm = $post['pm'];
        $this->prodi = $prodi;
        $this->min_prosen_capacity = $min_prosen_capacity;

        // Membuat kelas sesuai dengan jumlah pertemuan perminggu
        foreach ($kelas as $i => $item) {
            for ($i=0; $i < $item['jml_pert']; $i++) { 
                $this->kelas[] = $item;
            }
        }

        // Menginisiasi matriks timespace yg berisi ruang, hari, dan jam
        $i = 0;
        foreach ($this->ruang as $key => $value) {
            foreach ($this->waktu as $a => $item) {
                $this->timespace[] = array(
                    'id_timespace'      => $i++,
                    'id_ruang'          => $value['ru_id'],
                    'id_waktu'          => $item['waktu_id'],
                    'waktu_hari'        => $item['waktu_hari'],
                    'waktu_jam_mulai'   => $item['waktu_jam_mulai'],
                    'waktu_period'      => $item['waktu_is_belajar'],
                    'label'             => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-',
                    'kap_ruang'         => $value['ru_kapasitas'],
                    'jns_ruang'        	=> $value['ru_jenis'],
                    'status'            => ''
                );
            }
        }
    }

    public function generate_population(){
        // buat individu
        $this->kromosom = $this->create_information_class();
        // echo '<pre>'; print_r($this->kromosom); exit();
        
        // Bangkitkan populasi yang terdiri dari sejumlah individu
        $this->classinfo = array();
        $this->populasi = array();
        for ($i=0; $i < $this->post['jml_individu']; $i++) {
            $individu = $this->create_individu();
            $this->populasi[] = $individu; 
        }
    }

    public function create_information_class(){
        $class = array();
        $id_individu = 0;
        foreach ($this->kelas as $key => $value) {
            $period_waktu = $value['sks'];
            $arr_data = compact('class','period_waktu','value', 'id_individu');
            $ret_data = $this->make_class($arr_data);
            extract($ret_data);
        }
        return $class;
    }

    public function make_class($arr_data){
        extract($arr_data);
        // menyimpan hasil ruang & waktu kelas, beserta periodenya.
        $class[] = array(
            'id_individu'           => $id_individu,
            'id_kelas'              => $value['id'],
            'id_mpkur'              => $value['mpkur_id'],
            'praktikum'             => $value['praktikum'],
            'period'                => $period_waktu,
            'kd_prodi'              => $value['kd_prodi'],
            'label_kelas'           => $value['label_kelas'],
            'nama_kelas'            => $value['nama_kelas'],
            'jml_peserta_kls'       => $value['jml_peserta_kls'],
            'paket_smt'             => $value['paket_smt'],
            'sifat_mapel'           => $value['sifat_mapel'],
            'guru'                  => $value['guru'],
            'kelas_prodi'           => $value['kelas_prodi'],
            'alternatif_waktu_ajar' => $value['alternatif_waktu_ajar'],
            'is_universal'          => $value['is_universal']
        );
        $id_individu++;
        $ret_data = compact('class','period_waktu','value', 'id_individu');
        return $ret_data;
    }

    public function create_individu(){
        $individu = array(); // untuk menampung sejumlah individu yang mewakili jadwal
        $timespace = $this->timespace; // matriks data ruang, hari, dan waktu
        $status_reset_individu = false;

        foreach ($this->kromosom as $key => $value) {
            $arr_data = compact('timespace','individu','value', 'status_reset_individu');

            $ret_data = $this->get_feasible_individu($arr_data);
            if ($status_reset_individu) {
                unset($individu);
                unset($timespace);
            	return $this->create_individu();
            }
            extract($ret_data);
        }
        // echo '<pre>'; print_r($individu); echo '</pre>'; exit();
         //$this->CI->bantu->debugPreviewJadwal($individu); exit();
        return $individu;
    }

    public function get_feasible_individu($arr_data){
        extract($arr_data);

        $period_waktu = $value['period'];
        $individu_classprodi = $this->break_individu_prodi($individu);
        $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, 0);

        if ($id_timespace == 'nochance') {
        	$status_reset_individu = true;
        }else{
            // menyimpan hasil ruang & waktu untuk kelas, beserta periodenya.
	        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
	        $individu[] = array(
	            'id_kromosom'              => $value['id_individu'],
                'kd_prodi'                 => $value['kd_prodi'],
                'label_kelas'              => $value['label_kelas'],
	            'nama_kelas'               => $value['nama_kelas'],
                'paket_smt'                => $value['paket_smt'],
                'praktikum'                => $value['praktikum'],
	            'id_timespace'             => $timespace[$id_timespace]['id_timespace'],
	            'id_waktu'                 => $timespace[$id_timespace]['id_waktu'],
	            'period'                   => $value['period'],
	            'waktu_hari'               => $timespace[$id_timespace]['waktu_hari'],
	            'id_ruang'                 => $timespace[$id_timespace]['id_ruang'],
	            'waktu_jam_mulai'          => $timespace[$id_timespace]['waktu_jam_mulai'],
	            'label_timespace'          => $timespace[$id_timespace],
	            'kap_ruang'                => $timespace[$id_timespace]['kap_ruang']
	        );
	        $timespace[$id_timespace]['status'] = 1;
        }
        $ret_data = compact('timespace','individu','value', 'status_reset_individu');
        return $ret_data;
    }

    function break_individu_prodi($individu){
        $u = null;
        $p = null;
        foreach ($individu as $t => $ind) {
            if ($this->kromosom[$ind['id_kromosom']]['is_universal'] == '1') {
                $u[] = $ind;
            }else{
                foreach ($this->prodi as $t => $pr) {
                    $kelas_prodi = explode('|', $this->kromosom[$ind['id_kromosom']]['kelas_prodi']);
                    if (!empty($kelas_prodi) AND in_array($pr['prodi_id'],$kelas_prodi)) {
                        $p[$t][] = $ind;
                    }
                }
            }
        }
        $return = array(
            "uni" => $u,
            "pro" => $p,
        );
        return $return;
    }

    function getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu=null, $id_ts=null ){
        $iteration++;
        if ($iteration == 100) {
        	return 'nochance';
        }
        
        $ind_ru_id = array();
        $id_ruang_pd = array();
        $timespace_prodi = array();

        if ($id_ts==null) {

            if (!empty($individu) && $value['praktikum']!=1) {
                foreach ($individu as $in => $ind) {
                    if (
                        $this->kromosom[$ind['id_kromosom']]['kd_prodi']==$value['kd_prodi'] && 
                        $this->kromosom[$ind['id_kromosom']]['paket_smt']==$value['paket_smt'] && 
                        $this->kromosom[$ind['id_kromosom']]['label_kelas']==$value['label_kelas'] && 
                        $this->kromosom[$ind['id_kromosom']]['praktikum']==$value['praktikum']
                       ) {
                        $id_ruang_pd[] = $ind['id_ruang'];
                        break;
                    }
                }
            }
            if (empty($id_ruang_pd)) {
                foreach ($individu as $i => $in) {
                    $ind_ru_id[] = $in['id_ruang'];
                }
                }

        	if (!empty($id_ruang_pd)) {
        		foreach ($timespace as $key => $item) {
        			if (
                        $value['praktikum'] == 1 && 
                        in_array($item['id_ruang'], $id_ruang_pd) && $item['jns_ruang']==1 
                       ) {
        				$timespace_prodi[] = array('id_ts_pd' => $key);
        			}
                    elseif (
                        $value['praktikum'] == 0 && $item['status']!=1 &&
                        in_array($item['id_ruang'], $id_ruang_pd) && $item['jns_ruang']==0 && 
                        $item['waktu_period']==$value['period']
                        ) {
                        $timespace_prodi[] = array('id_ts_pd' => $key);
                    }
        		}
        	}

            if (!empty($timespace_prodi)) {
                $id_time = mt_rand(0,(count($timespace_prodi)-1));
                $id_timespace = $timespace_prodi[$id_time]['id_ts_pd'];
            }else{
                $id_timespace = mt_rand(0,(count($timespace)-1));
            }

        }else{
            $id_timespace = $id_ts;
        }
        
        $sts = true;
        if (
        	!isset($timespace[$id_timespace]) OR $timespace[$id_timespace]['status'] == 1 OR 
        	$timespace[$id_timespace]['jns_ruang'] != $value['praktikum'] OR
        	$timespace[$id_timespace]['waktu_period'] != $value['period']
           ) {
            $sts = false;
        }

        if (!$sts) {
            return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
        }else{
            $rule_ok = $this->check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration);
            if ($rule_ok) {
                return $id_timespace;
            }else{
                return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
            }
        }     
    }

    public function check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration=null){
        if (!empty($individu)) {
            $sts = true;
            $stsrule_1 = $this->CI->aturan_jadwal->check_time_notover_limit($id_timespace, $timespace, $value, $period_waktu);
            $stsrule_2 = $this->CI->aturan_jadwal->check_class_samepacket_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_3 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $value);
            $stsrule_4 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_5 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
            $stsrule_6 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);

            $sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 && $stsrule_6;
            return $sts;
        }else{
            return true;
        }
    }

    public function get_jam_selesai_kelas($waktu_jam_mulai, $period_waktu){
    	$waktu_jam_mulai_kls = strtotime($waktu_jam_mulai);
        $lama_menit_kelas = $period_waktu * 45;
        $waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
        return $waktu_jam_selesai_kls;
    }

/**************************Count Fitness******************************/

    function bentrok_sametime($kls, $kls_compare) {
        
        $sts = $this->timespace[$kls['id_timespace']]['waktu_hari'] == $this->timespace[$kls_compare['id_timespace']]['waktu_hari'];

        $start_time = strtotime($this->timespace[$kls['id_timespace']]['waktu_jam_mulai']);
        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$kls['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls['id_kromosom']]['period']);
        $end_time = strtotime($waktu_jam_selesai_kls);

        $start_compare_time = strtotime($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai']);
        $waktu_jam_selesai_kls_compare = $this->get_jam_selesai_kelas($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls_compare['id_kromosom']]['period']);
        $end_compare_time = strtotime($waktu_jam_selesai_kls_compare);

        $sts_time_between = ( ( ($start_time >= $start_compare_time) && ($start_time <= $end_compare_time) OR ( ($end_time >= $start_compare_time) && ($end_time <= $end_compare_time) ) ) );
        $sts = $sts && $sts_time_between;

        return $sts;
    }

    public function separate_kelas_mapel_wajib_pil($individu){

        $mapel_wajib = array();
        $mapel_pil = array();
        foreach ($individu as $key => $value) {
            if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_mapel']) == 'w') {
                $mapel_wajib[] = $value;
            }
            if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_mapel']) == 'p') {
                $mapel_pil[] = $value;
            }
        }
        $separate_mapel = array(
            'mapel_wajib' => $mapel_wajib,
            'mapel_pil' => $mapel_pil
        );
        return $separate_mapel;
    }

    public function count_fitness_based_rule_kelasmapel_pilihan_wajib_not_sametime($individu){
    	
        $ind_classprodi = $this->break_individu_prodi($individu);
    	foreach ($ind_classprodi['pro'] as $i => $arr_kromosom) {
			foreach ($arr_kromosom as $k => $kr_pr) {
				$data_perprod[$i][] = $kr_pr;
			}
			if (!empty($ind_classprodi['uni'])) {
				foreach ($ind_classprodi['uni'] as $j => $kr_un) {
					$data_perprod[$i][] = $kr_un;
				}
			}
		}

		$jumlah_perprod = 0;
		$jumlah_mapelpil = 0;
		foreach ($data_perprod as $key => $indi_pr) {
			$separate_mapel = $this->separate_kelas_mapel_wajib_pil($indi_pr);
			$jumlah = 0;
	        foreach ($separate_mapel['mapel_pil'] as $i => $pil) {
	            $smt_neighbor = $this->CI->aturan_jadwal->get_neighbor_sametype_semester($this->kromosom[$pil['id_kromosom']]['paket_smt']);

	            $bentrok[$i] = 0;
	            $bentrok_ket[$i] = '';

	            $sts_bentrok = false;
	            foreach ($separate_mapel['mapel_wajib'] as $j => $wjb) {
	                if (in_array($this->kromosom[$wjb['id_kromosom']]['paket_smt'],$smt_neighbor) AND ($this->bentrok_sametime($pil,$wjb)) ) {
	                    $bentrok[$i]++;
	                    $bentrok_ket[$i] .= $this->kromosom[$wjb['id_kromosom']]['id_kelas'].', ';
	                    $sts_bentrok = true;
	                }
	            }

	            $separate_mapel['mapel_pil'][$i]['bentrok'] = $bentrok[$i];
	            $separate_mapel['mapel_pil'][$i]['bentrok_ket'] = $bentrok_ket[$i];

	            if ($sts_bentrok) {
	                $jumlah++;
	            }
	        }
	        $jumlah_mapelpil = $jumlah_mapelpil + count($separate_mapel['mapel_pil']);
	        $jumlah_perprod = $jumlah_perprod + $jumlah;
		}
		$fitness = $jumlah_perprod / $jumlah_mapelpil;
        return $fitness;
    }

    public function count_fitness_based_rule_kelasmapel_on_ruangblokprodi($individu){
        $score = 0;
        $i = 0;
        foreach ($individu as $key => $value) {
            $arr_ruangblokprodi[$key] = explode('|', $this->kromosom[$value['id_kromosom']]['ruang_blok_prodi']);
            if (!empty($arr_ruangblokprodi[$key]) AND !in_array($this->timespace[$value['id_timespace']]['id_ruang'], $arr_ruangblokprodi[$key])) {
                $score++;
            }
            if ( !empty($arr_ruangblokprodi[$key]) ) {
                $i++;
            }
        }
        $fitness = $score / $i;
        // echo '<pre>'; print_r($fitness); exit();
        return $fitness;
    }

    public function count_fitness_based_rule_kelasmapelsepaket_max_10_sks_sehari($individu){
        $arr_hari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat');
        $total_langgar = 0;
        foreach ($arr_hari as $i => $hari) {
            $arr_grup_hari[$i] = array(
                'hari' => $hari,
                'data_prodi' => array()
            );
            $jml_prodi_langgar = 0;
            foreach ($this->prodi as $j => $prodi) {
                $arr_grup_hari[$i]['data_prodi'][$j] = array(
                    'prodi_id' => $prodi['prodi_id'],
                    'data_semester' => array()
                );
                $jml_smt_langgar = 0;
                for ($l=1; $l <= 8; $l++) { 
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l] = array(
                        'paket_semester' => $l,
                        'data_kelas' => array()
                    );
                    $total_sks = 0;
                    foreach ($individu as $k => $kelas_terjadwal) {
                        $arr_kelas_prodi = explode('|', $this->kromosom[$kelas_terjadwal['id_kromosom']]['kelas_prodi']);                       
                        if ($hari == $this->timespace[$kelas_terjadwal['id_timespace']]['waktu_hari'] AND in_array($prodi['prodi_id'], $arr_kelas_prodi) AND $this->kromosom[$kelas_terjadwal['id_kromosom']]['paket_smt'] == $l) {
                            $total_sks += $this->kromosom[$kelas_terjadwal['id_kromosom']]['period'];
                            $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['data_kelas'][] = $kelas_terjadwal;                               
                        }                       
                    }
                    $arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['total_sks'] = $total_sks;
                    if ($total_sks > 10) {
                        $jml_smt_langgar++;
                    }
                }
                $arr_grup_hari[$i]['data_prodi'][$j]['jml_smt_langgar'] = $jml_smt_langgar;
                if ($jml_smt_langgar > 0) {
                    $jml_prodi_langgar++;
                }
            }
            $arr_grup_hari[$i]['jml_prodi_langgar'] = $jml_prodi_langgar;
            $total_langgar += $jml_prodi_langgar;
        }
        $jml_semesta_himp = count($arr_hari) * count($this->prodi);
        $fitness = $total_langgar / $jml_semesta_himp;
        return $fitness;
    }

    public function count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu){
        $melanggar = 0;
        foreach ($individu as $key => $value) {
            $harapan_jml = ($this->min_prosen_capacity / 100) * $this->timespace[$value['id_timespace']]['kap_ruang'];
            $individu[$key]['harapan_jml'] = ceil($harapan_jml);
            $individu[$key]['melanggar'] = 0;
            if ($this->kromosom[$value['id_kromosom']]['jml_peserta_kls'] < ceil($harapan_jml)) {
                $individu[$key]['melanggar'] = 1;
                $melanggar++;
            }
        }
        $fitness = $melanggar / count($individu);
        return $fitness;        
    }

    public function count_fitness_based_rule_kelas_guru_choose_their_time($individu){
        $jml_langgar = 0;
        foreach ($individu as $key => $value) {
            $arr_alternatif_waktu_ajar = explode('|', $this->kromosom[$value['id_kromosom']]['alternatif_waktu_ajar']);
            if (!in_array($this->timespace[$value['id_timespace']]['id_waktu'], $arr_alternatif_waktu_ajar)) {
                $jml_langgar++;
            }
        }
        $fitness = $jml_langgar / count($individu);
        return $fitness;
    }

    public function transform_populasi(){
        foreach ($this->populasi as $key => $individu) {
            $this->populasi_breeding[$key]['fitness'] = 0;
            foreach ($individu as $i => $gen) {
                $this->populasi_breeding[$key]['arr_gen'][$i] = $gen;
            }
        }
    }

    public function count_fitness(){
        
        $this->transform_populasi();
        $this->total_fitness = 0;
        foreach ($this->populasi as $i => $individu) {
            $populasi[$i]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmapel_pilihan_wajib_not_sametime($individu);
            $populasi[$i]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmapelsepaket_max_10_sks_sehari($individu);
            $populasi[$i]['fitness_rule_3'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu);
            $populasi[$i]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_guru_choose_their_time($individu);

            $populasi[$i]['fitness'] = 1-(($populasi[$i]['fitness_rule_1'] +  $populasi[$i]['fitness_rule_2'] + $populasi[$i]['fitness_rule_3'] + $populasi[$i]['fitness_rule_4'])/4);

            $this->populasi_breeding[$i]['fitness'] = $populasi[$i]['fitness'];

            $this->total_fitness += $populasi[$i]['fitness'];
            //echo '<pre>'; print_r($this->total_fitness); exit();
        }        
        unset($populasi);
         //echo '<pre>'; print_r($populasi); 
         //echo '<pre>'; print_r($this->populasi); 
         //echo '<pre>'; print_r($this->populasi_breeding); exit();
    }

/**************************Main Algorhitm*****************************/

    public function roulette_wheel_selection(){
        $populasi_breeding = $this->populasi_breeding;
        foreach ($populasi_breeding as $key => $value) {
            $prob = $value['fitness'] / $this->total_fitness;
            $populasi_breeding[$key]['idx'] = $key;
            $populasi_breeding[$key]['prob'] = round($prob,5);

            if ($key == 0) {
                $rentangan[$key]['awal'] = 0;
            }else{
                $rentangan[$key]['awal'] = $rentangan[($key-1)]['akhir'] + 0.00001;
            }
            
            $rentangan[$key]['akhir'] = $rentangan[$key]['awal'] + $populasi_breeding[$key]['prob'];
            $random_number[$key] = mt_rand(0,100000)/100000;
        }

        $pick_individu = array();
        foreach ($random_number as $i => $val) {
            foreach ($rentangan as $j => $vale) {
                if ($val >= $vale['awal'] and $val <= $vale['akhir'] ) {
                    $pick_individu[] = $j;
                }
            }
        }

        for ($i=0; $i < $this->post['jml_individu']; $i++) { 
            $populasi_breeding_selected[] = $populasi_breeding[$pick_individu[$i]];
        }

        foreach ($populasi_breeding_selected as $key => $value) {
            $populasi_breeding_selected[$key]['val_random'] = mt_rand(0,100000)/100000; // for selecting on crossover
        }

        $this->total_fitness = 0; // set total fitness 0 karna sudah digunakan 
        $this->populasi_breeding_selected = $populasi_breeding_selected;
    }

    public function repair_kelas_on_hardrule($individu){
        $individu_temp = array();
        $timespace = $this->timespace;
        $status_reset_individu = false;        

        // echo '<pre> Idividu Repair:'; print_r($individu); echo '</pre>'; exit();

        foreach ($this->kromosom as $key => $value) {
            
            $individu_classprodi = $this->break_individu_prodi($individu_temp);
            $period_waktu = $value['period'];
            $id_timespace = $this->getRandomTimespace($individu_classprodi, $individu_temp, $value, $timespace, $period_waktu, 0, null, $individu[$value['id_individu']]['id_timespace']);

            if ($id_timespace == 'nochance') {
                unset($individu_temp);
                unset($timespace);
                $individu_temp = $this->create_individu();
                break;
            }else{
                // menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
                $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
                $individu_temp[] = array(
                    'id_kromosom' => $value['id_individu'],
                    'nama_kelas' => $value['nama_kelas'],
                    'id_timespace' => $timespace[$id_timespace]['id_timespace'],
                    'id_waktu' => $timespace[$id_timespace]['id_waktu'],
                    'period' => $value['period'],
                    'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
                    'id_ruang' => $timespace[$id_timespace]['id_ruang'],
                    'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
                    'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
                    'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
                    'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
                );
                $timespace[$id_timespace]['status'] = 1;
            }
        }
        return $individu_temp;
    }

    public function repairing_individu($individu){
        $individu = $this->repair_kelas_on_hardrule($individu);        
        return $individu;
    }
    
    public function build_offspring_population_crossover_twopoint($parent_1, $parent_2, $point_random){
        $jumlah_gen = count($parent_1['arr_gen']);
        $i = 0;

        $arr_gen_1 = $parent_1['arr_gen'];
        $arr_gen_2 = $parent_2['arr_gen'];
        while ($jumlah_gen>0) {
            if (!in_array($i, $point_random) ) {
                $off_1[] = $arr_gen_1[$i];
                $off_2[] = $arr_gen_2[$i];
                $i++;
                $jumlah_gen--;
            }else{
                $point_random = array_diff($point_random, array($i));
                $point_random = array_values($point_random);

                $temp_1 = $arr_gen_1;
                $temp_2 = $arr_gen_2;
                $arr_gen_1 = $temp_2;
                $arr_gen_2 = $temp_1;
            }
        }

        $offspring[] = array(
            'parent' => $parent_1,
            'offspring' => $off_1
        );
        $offspring[] = array(
            'parent' => $parent_2,
            'offspring' => $off_2
        );

        foreach ($offspring as $key => $value) {
            $offspring[$key]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmapel_pilihan_wajib_not_sametime($value['offspring']);
            $offspring[$key]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmapelsepaket_max_10_sks_sehari($value['offspring']);
            $offspring[$key]['fitness_rule_3'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($value['offspring']);
            $offspring[$key]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_guru_choose_their_time($value['offspring']);
            
            $offspring[$key]['fitness'] = 1-(($offspring[$key]['fitness_rule_1'] +  $offspring[$key]['fitness_rule_2'] + $offspring[$key]['fitness_rule_3'] + $offspring[$key]['fitness_rule_4'] )/4);
            $offspring[$key]['randvalmut'] = mt_rand(0,1);
        }
        $this->individu_breed[] = $offspring;
    }

    public function crossover(){
        $populasi_breeding_crossover_selected = array();
        foreach ($this->populasi_breeding_selected as $key => $value) {
            if ($value['val_random'] <= $this->pc) {
                $populasi_breeding_crossover_selected[] = $value;
            }
        }

        if (empty($populasi_breeding_crossover_selected)) {
            $url_gnrt = base_url('penjadwalan/generate_jadwal');
        	echo "Tidak ada nilai lebih kecil dari Probabilitas Crossover!<hr>";
            echo "Ubah Nilai Probabilitas Crossover & <a href='$url_gnrt'>Generate Ulang!</a><hr>";
            exit();
        }

        $n_gen = count($populasi_breeding_crossover_selected[0]['arr_gen']);
        $n_ind = count($populasi_breeding_crossover_selected);
        
        $point_random = array(mt_rand(2,$n_gen-1), mt_rand(2,$n_gen-1) );
        for ($i=0; $i < $n_ind-1 ; $i++) { 
            $this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[$i], $populasi_breeding_crossover_selected[$i+1], $point_random);
        }
        $this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[($n_ind-1)], $populasi_breeding_crossover_selected[0], $point_random);
    }

    public function mutasi_kromosom($individu){
        $timespace = $this->timespace;

        $pos_mutasi = mt_rand(0,count($individu)-1);
        $id_timespace = mt_rand(0,(count($timespace)-1));

        $gen = $individu[$pos_mutasi];

        $waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $this->kromosom[$gen['id_kromosom']]['period']);
        
    	$individu[$pos_mutasi] = array(
            'id_kromosom' => $gen['id_kromosom'],
            'id_timespace' => $timespace[$id_timespace]['id_timespace']
            // 'id_waktu' => $timespace[$id_timespace]['id_waktu'],
            // 'id_ruang' => $timespace[$id_timespace]['id_ruang'],
            // 'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
            // 'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
            // 'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
            // 'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
            // 'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
        );
        $individu = $this->repairing_individu($individu);
        return $individu;
    }

    public function mutation(){
        foreach ($this->individu_breed as $key => $value) {
            foreach ($value as $i => $item) {
                if ($item['randvalmut'] < $this->pm) {                    
                    $this->individu_breed[$key][$i]['offspring'] = $this->mutasi_kromosom($item['offspring']);
                }
                $this->individu_update_calon[] = $this->individu_breed[$key][$i];
            }
        }
        //echo '<pre>'; print_r($this->individu_breed);
        $this->individu_breed = null;
    }

    public function count_total_fitness_populasi_breeding(){
        $total = 0;
        foreach ($this->populasi_breeding as $key => $value) {
            $total += $value['fitness'];
        }
        return $total;
    }

    public function update_selection(){
        $populasi_breeding = $this->populasi_breeding;
        $this->populasi_breeding = array();
        foreach ($populasi_breeding as $key => $value) {
            $this->populasi_breeding[] = array(
                'fitness' => $value['fitness'],
                'arr_gen' => $value['arr_gen']
            );
        }

        foreach ($this->individu_update_calon as $key => $value) {
            $this->populasi_breeding[] = array(
                'fitness' => $value['fitness'],
                'arr_gen' => $value['offspring']
            );
        }

        $this->total_fitness = $this->count_total_fitness_populasi_breeding();        

        $this->roulette_wheel_selection();

        foreach ($this->populasi_breeding_selected as $key => $value) {
            $this->populasi_baru[] = $value['arr_gen'];
        }
        //echo '<pre>'; print_r($this->individu_update_calon); //exit();
        $this->individu_update_calon = null;
    }

    public function update_population(){
        $this->populasi = array();
        foreach ($this->populasi_baru as $key => $value) {
            $this->populasi[] = $value;
        }
        //echo '<pre>'; print_r($this->populasi_baru); exit();
        $this->populasi_baru = null;
    }

    public function get_solution(){
        $max_fitness = 0;
        $idx = null;
        foreach ($this->populasi_breeding_selected as $key => $value) {
            if ($value['fitness'] > $max_fitness) {
                $max_fitness = $value['fitness'];
                $idx = $key;
            }
        }
        foreach ($this->populasi_breeding_selected[$idx]['arr_gen'] as $key => $value) {
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_kelas'] = $this->kromosom[$value['id_kromosom']]['id_kelas'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_waktu'] = $this->timespace[$value['id_timespace']]['id_waktu'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_ruang'] = $this->timespace[$value['id_timespace']]['id_ruang'];
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['period'] = $this->kromosom[$value['id_kromosom']]['period'];

        	$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$value['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$value['id_kromosom']]['period'] );
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['jam_selesai'] = $waktu_jam_selesai_kls;
        	$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['label_timespace'] = $this->timespace[$value['id_timespace']]['label'].$waktu_jam_selesai_kls;
        }
        //echo '<pre>'; print_r($this->populasi_breeding_selected);
        //echo 'Gen Terbaik pada individu ke : ' .($idx+1);
        //echo '<pre>'; print_r($this->populasi_breeding_selected[$idx]); 
        //exit();
        return $this->populasi_breeding_selected[$idx];
        
    }

}