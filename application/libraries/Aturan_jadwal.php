<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aturan_jadwal {
    var $CI;

    public function __construct(){
        $this->CI =& get_instance();
    }
    
    public function check_time_notover_limit($id_timespace, $timespace, $value, $period_waktu){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }
        return $sts;
    }

    public function check_class_samepacket_not_sametime($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }
        if (!empty($individu)) {
            foreach ($individu as $i => $item) {
                if (
                    $kromosom[$item['id_kromosom']]['kd_prodi'] == $value['kd_prodi'] AND 
                	$kromosom[$item['id_kromosom']]['paket_smt'] == $value['paket_smt'] AND 
                    $kromosom[$item['id_kromosom']]['label_kelas'] == $value['label_kelas'] AND 
                	$item['id_waktu'] == $timespace[$id_timespace]['id_waktu']
                   ) {
                    $sts = false;
                    break;
                }
            }
        }
        return $sts;
    }

    public function check_capacity_class_ok($id_timespace, $timespace, $value){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }
        if ($value['jml_peserta_kls'] > $timespace[$id_timespace]['kap_ruang']) {
            $sts = false;
        }
        return $sts;
    }

    public function check_lecture_class_not_sametime($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }        
        
        foreach ($individu as $i => $item) {
            if (!empty($kromosom[$item['id_kromosom']]['guru'])) {
                $sama = 0;
                foreach ($kromosom[$item['id_kromosom']]['guru'] as $j => $item_dsn) {
                    if (!empty($value['guru'])) {
                        foreach ($value['guru'] as $k => $item_dsn_current_class) {
                            if ($item_dsn == $item_dsn_current_class
                            ) {
                                $sama++;
                            }
                        }
                    }
                }

                if(count($kromosom[$item['id_kromosom']]['guru']) == $sama AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']){
                    $sts = false;
                    break;
                }
            }
        }
        return $sts;
    }   

    public function check_separatesameclass_not_sameday($kromosom, $timespace_utama, $individu, $value, $id_timespace, $timespace){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }
        foreach ($individu as $i => $item) {
            if ($kromosom[$item['id_kromosom']]['id_kelas'] == $value['id_kelas'] 
                AND $timespace_utama[$item['id_timespace']]['waktu_hari'] == $timespace[$id_timespace]['waktu_hari']) {
                $sts = false;
                break;
            }            
        }
        return $sts;
    }

    public function check_neighborpacketclass_not_sametime($kromosom, $timespace_utama, $individu_classprodi, $value, $id_timespace, $timespace, $prodi){
        $sts = true;
        if (!isset($timespace[$id_timespace])) {
            $sts = false;
            return $sts;
        }

        $smt_neighbor = $this->get_neighbor_sametype_semester($value['paket_smt']);
        $value_prodi = explode('|', $value['kelas_prodi']);

        if (!empty($individu_classprodi['uni'])) {
            foreach ($individu_classprodi['uni'] as $i => $item) {
                if ($kromosom[$item['id_kromosom']]['sifat_mapel'] == $value['sifat_mapel'] 
                    AND $value['sifat_mapel'] == 'W' 
                    AND (in_array($kromosom[$item['id_kromosom']]['paket_smt'], $smt_neighbor)) 
                    AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']
                ) {
                    $sts = false;
                    break;
                }            
            } 
        }

        if ($value['is_universal'] == '0' && !empty($individu_classprodi['pro'])) {
            foreach ($prodi as $t => $pr) {
                if (isset($individu_classprodi['pro'][$t]) && !empty($individu_classprodi['pro'][$t]) AND in_array($pr['prodi_id'],$value_prodi)) {
                    foreach ($individu_classprodi['pro'][$t] as $i => $item) {
                        if ($kromosom[$item['id_kromosom']]['sifat_mapel'] == $value['sifat_mapel'] 
                            AND $value['sifat_mapel'] == 'W' 
                            AND (in_array($kromosom[$item['id_kromosom']]['paket_smt'], $smt_neighbor)) 
                            AND $timespace_utama[$item['id_timespace']]['id_waktu'] == $timespace[$id_timespace]['id_waktu']
                        ) {
                            $sts = false;
                            break;
                        }            
                    }
                }                
            }
        }
        return $sts;
    }

    function get_neighbor_sametype_semester($smt){
        $arr_smt_ganjil = array(1,3,5);
        $arr_smt_genap = array(2,4,6);
        $arr_neighbor = array();

        if ( in_array($smt, $arr_smt_ganjil)) {
            foreach ($arr_smt_ganjil as $key => $value) {
                if ( ($smt == $value) ) {
                    if ( isset($arr_smt_ganjil[$key-1]) ) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key-1];
                    }
                    if ( isset($arr_smt_ganjil[$key+1]) ) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key+1];
                    }
                    
                }
            }
        }else{
            foreach ($arr_smt_genap as $key => $value) {
                if ( ($smt == $value) ) {
                    if ( isset($arr_smt_genap[$key-1]) ) {
                        $arr_neighbor[] = $arr_smt_genap[$key-1];
                    }
                    if ( isset($arr_smt_genap[$key+1]) ) {
                        $arr_neighbor[] = $arr_smt_genap[$key+1];
                    }
                    
                }
            }
        }

        return $arr_neighbor;
    }
    
}
