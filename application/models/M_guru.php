<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_guru extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_count_guru($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(guru_id) as total
            FROM guru ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_guru($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `guru_nip` LIKE '%$search%' OR `guru_nama` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `guru_id` AS id,
                  `guru_nip` AS nip,
                  `guru_nama` AS nama
            FROM `guru`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
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
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `waktu_id` AS id,
                  `waktu_hari` AS hari,
                  concat(waktu_jam_mulai," - ",waktu_jam_selesai) AS jam
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

    function get_guru_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND guru_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `guru_id` AS id,
                  `guru_nip` AS nip,
                  `guru_nama` AS nama
            FROM `guru`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_guru($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `guru`
            SET
                  `guru_nip` = ?,
                  `guru_nama` = ?
            WHERE `guru_id` = ?
        ";

        return $this->db->query($sql, array($nip, $nama, $id));
    }

    function add_guru($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `guru`
            (`guru_nip`,`guru_nama`)
            VALUES (?,?)
        ";

        return $this->db->query($sql, array($nip, $nama));
    }

    function del_guru($param){
        $sql = "DELETE FROM guru WHERE guru_id = '$param'";
        return $this->db->query($sql); 
    }

    function get_guru_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `guru_nip` LIKE '%$search%' OR `guru_nama` LIKE '%$search%'";
        }
       
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
              `guru_id` AS id,
              `guru_nip` AS nip,
              `guru_nama` AS nama
            FROM guru_waktu dw
            LEFT JOIN `guru` d ON dw.`grwkt_gr_id` = d.`guru_id`
            --search--
            GROUP BY guru_id
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_count_guru_waktu($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT COUNT(*) AS total FROM (
                SELECT COUNT(guru_id)
                FROM guru_waktu dw
                LEFT JOIN `guru` d ON dw.`grwkt_gr_id` = d.`guru_id`
                --search--
                GROUP BY guru_id
            ) t
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function add_guru_waktu($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `guru_waktu`
            (`grwkt_gr_id`,`grwkt_wkt_id`)
            VALUES (?,?)
        ";

        return $this->db->query($sql, array($id_guru, $id_waktu));
    }

    function get_waktu_guru_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND grwkt_gr_id = $id";   
        }

        $query = '
            SELECT
                  grwkt_id AS id,
                  grwkt_gr_id AS id_guru,
                  grwkt_wkt_id AS id_waktu,
                  w.`waktu_hari` AS hari,
                  CONCAT(w.`waktu_jam_mulai`," - ",w.`waktu_jam_selesai`) AS jam
            FROM guru_waktu dw
            LEFT JOIN waktu w ON dw.`grwkt_wkt_id` = w.`waktu_id`
            where 1=1 
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function delete_guru_waktu_by_id($id){
        $sql = "DELETE FROM `guru_waktu` WHERE grwkt_gr_id = '$id'";
        return $this->db->query($sql); 
    }

}