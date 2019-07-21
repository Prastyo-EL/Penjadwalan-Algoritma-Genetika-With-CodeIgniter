<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user extends CI_Model {
	
	public function cek_user($data)
	{
		$query = $this->db->get_where('user',$data);
		return $query;
	}

    function get_count_user($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(user_id) as total
            FROM user ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_user($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE `user_nama` LIKE '%$search%' 
                    OR `user_email` LIKE '%$search%' 
                    OR `user_level` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `user_id` AS id,
                  `user_nama` AS nama,
                  `user_email` AS email,
                  `user_pwd` AS pwd,
                  `user_level` AS level
            FROM `user`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_user_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND user_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `user_id` AS id,
                  `user_nama` AS nama,
                  `user_email` AS email,
                  `user_pwd` AS pwd,
                  `user_level` AS level
            FROM `user`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_user($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `user`
            SET
                  `user_nama` = ?,
                  `user_email` = ?,
                  `user_pwd` = ?,
                  `user_level` = ?
            WHERE `user_id` = ?
        ";

        return $this->db->query($sql, array($nama, $email, $pwd, $level, $id));
    }

    function add_user($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `user`
            (`user_nama`,`user_email`,`user_pwd`, user_level)
            VALUES (?,?,?,?)
        ";

        return $this->db->query($sql, array($nama, $email, $pwd, $level));
    }

    function del_user($param){
        $sql = "DELETE FROM user WHERE user_id = '$param'";
        return $this->db->query($sql); 
    }

    function get_search($filter){
        if (is_array($filter))
            extract($filter);

        $str = '';
        if (!empty($search)) {
            $str = "WHERE mpk.`mpkur_kode` LIKE '%$search%' 
                    OR mpk.`mpkur_nama` LIKE '%$search%' 
                    OR mpk.`mpkur_paket_semester` LIKE '%$search%' 
                    OR mpk.`mpkur_semester` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mpk.`mpkur_id` AS id,
                mpk.`mpkur_kode` AS kode,
                mpk.`mpkur_nama` AS nama,
                mpk.`mpkur_paket_semester` AS paket,
                mpk.`mpkur_semester` AS smt,
                "null" AS nama_prodi,
                mpk.`mpkur_sks` AS sks,
                IF(mpkur_pred_jml_peminat IS NULL,"Belum diketahui",mpkur_pred_jml_peminat) AS pred_jml_peminat
            FROM mapel_kurikulum mpk
            -- LEFT JOIN program_studi ps ON mpk.`mpkur_prodi_id` = ps.`prodi_id`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

}