<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_bantu extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function up_logproses($param){
        if (is_array($param))
            extract($param);
        $sql = "
            UPDATE log_proses
            SET logproses_data = ?
            WHERE logproses_kode = ?
        ";
        return $this->db->query($sql, array($data, $kode)); 
    }    

    function get_data_logproses($kode){
        $query = "
            SELECT 
			lp.logproses_data
			FROM log_proses lp
			WHERE lp.logproses_kode = '$kode'
        ";
        $ret = $this->db->query($query);
        $ret = $ret->result_array();
        return $ret[0]['logproses_data'];
    }

}