<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_waktu extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_count_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(waktu_id) as total
            FROM waktu ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `waktu_hari` LIKE '%$search%' 
                    OR `waktu_jam_mulai` LIKE '%$search%' 
                    OR `waktu_jam_selesai` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `waktu_id` AS id,
                  `waktu_hari` AS hari,
                  `waktu_jam_mulai` AS jam_mulai,
                  `waktu_jam_selesai` AS jam_selesai,
                  `waktu_is_belajar` AS is_blj_label,
                  IF(waktu_is_belajar=0,"Tidak","Ya") AS is_blj
            FROM `waktu`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_waktu_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND waktu_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `waktu_id` AS id,
                  `waktu_hari` AS hari,
                  `waktu_jam_mulai` AS jam_mulai,
                  `waktu_jam_selesai` AS jam_selesai,
                  `waktu_is_belajar` AS is_blj
            FROM `waktu`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_waktu($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `waktu`
            SET
                  `waktu_hari` = ?,
                  `waktu_jam_mulai` = ?,
                  `waktu_jam_selesai` = ?,
                  `waktu_is_belajar` = ?
            WHERE `waktu_id` = ?
        ";

        return $this->db->query($sql, array($hari, $jam_mulai, $jam_selesai, $is_blj, $id));
    }

    function add_waktu($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `waktu`
            (`waktu_hari`,`waktu_jam_mulai`,`waktu_jam_selesai`, waktu_is_belajar)
            VALUES (?,?,?,?)
        ";

        return $this->db->query($sql, array($hari, $jam_mulai, $jam_selesai, $is_blj));
    }

    function del_waktu($param){
        $sql = "DELETE FROM waktu WHERE waktu_id = '$param'";
        return $this->db->query($sql); 
    }

}