<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ruang extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_count_ruang($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_ruang($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `ru_kode` LIKE '%$search%'
                    OR `ru_prodi_id` LIKE '%$search%'
                    OR `ru_nama` LIKE '%$search%' 
                    OR `ru_kapasitas` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_prodi_id` AS prodi,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_jenis AS is_cad,
                  IF(ru_jenis=0,"Reguler","Lab") AS is_cad_label
            FROM `ruang`
            
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_ruang_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND ru_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_prodi_id` AS prodi,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_jenis AS is_cad
            FROM `ruang`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_ruang($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `ruang`
            SET
                  `ru_kode` = ?,
                  `ru_prodi_id` = ?,
                  `ru_nama` = ?,
                  `ru_kapasitas` = ?,
                  `ru_jenis` = ?
            WHERE `ru_id` = ?
        ";

        return $this->db->query($sql, array($kode, $prodi,$nama, $kapasitas, $is_cad, $id));
    }

    function add_ruang($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `ruang`
            (`ru_kode`,`ru_prodi_id`,`ru_nama`,`ru_kapasitas`, ru_jenis)
            VALUES (?,?,?,?,?)
        ";

        return $this->db->query($sql, array($kode,$prodi, $nama, $kapasitas, $is_cad));
    }

    function del_ruang($param){
        $sql = "DELETE FROM ruang WHERE ru_id = '$param'";
        return $this->db->query($sql); 
    }

    function get_count_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_program_studi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `prodi_kode` LIKE '%$search%' 
                    OR `prodi_nama` LIKE '%$search%' 
                    OR `prodi_prefix_mp` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `prodi_id` AS id,
                  `prodi_kode` AS kode,
                  `prodi_nama` AS nama,
                  `prodi_prefix_mp` AS akronim
            FROM `program_studi`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }
    function get_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_prodi_id` AS prodi,
                  prodi_nama AS prodinama,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_jenis AS is_cad,
                  IF(ru_jenis=0,"Reguler","Lab") AS is_cad_label
            FROM `ruang`
            join program_studi on ruang.ru_prodi_id = program_studi.prodi_id
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_ruang_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND ru_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_prodi_id` AS prodi,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_jenis AS is_cad
            FROM `ruang`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_ruang_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `ruang`
            SET
                  `ru_kode` = ?,
                  `ru_prodi_id` = ?,
                  `ru_nama` = ?,
                  `ru_kapasitas` = ?,
                  `ru_jenis` = ?
            WHERE `ru_id` = ?
        ";

        return $this->db->query($sql, array($kode,$prodi ,$nama, $kapasitas, $is_cad, $id));
    }

    function add_ruang_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `ruang`
            (`ru_kode`,`ru_prodi_id`,`ru_nama` ,`ru_kapasitas`, ru_jenis)
            VALUES (?,?,?,?,?)
        ";

        return $this->db->query($sql, array($kode,$prodi, $nama, $kapasitas,  $is_cad));
    }

    function del_ruang_prodi($param){
        $sql = "DELETE FROM ruang_prodi WHERE ru_id = '$param'";
        return $this->db->query($sql); 
    }

}