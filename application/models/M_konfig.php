<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_konfig extends CI_Model {

    function __construct(){
        parent::__construct();
    }    

    function get_configval_by_name($name_config){
        $str = 'AND c.`conf_name` = "'.$name_config.'"';

        $query = '
            SELECT 
                c.`conf_value` AS val
            FROM config c
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['val'];
    }

}

?>