<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_prodi extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    public function get_base_mpprodid_by_mpid($mpid){
        $query = '
            SELECT 
                ps.prodi_nama,
                ps.prodi_kode,
                mp.`mpkprod_id`,
                mp.`mpkprod_porsi_kelas`,
                mpk.`mpkur_prod_jml_peminat`,
                (
                SELECT SUM(mpv.mpkprod_porsi_kelas)
                FROM mapel_kur_prodi mpv
                WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                AND (mpv.`mpkprod_related_id` IS NULL or mpv.`mpkprod_related_id`="0")
                GROUP BY mpkprod_mpkur_id
                ) AS t,
                mpkprod_porsi_kelas * (mpk.`mpkur_prod_jml_peminat` DIV
                (
                    SELECT SUM(mpv.mpkprod_porsi_kelas)
                    FROM mapel_kur_prodi mpv
                    WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                    AND (mpv.`mpkprod_related_id` IS NULL or mpv.`mpkprod_related_id`="0")
                    GROUP BY mpkprod_mpkur_id
                )) AS jml_porsi,    
                mpk.`mpkur_prod_jml_peminat` MOD
                (
                    SELECT SUM(mpv.mpkprod_porsi_kelas)
                    FROM mapel_kur_prodi mpv
                    WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                    AND (mpv.`mpkprod_related_id` IS NULL or mpv.`mpkprod_related_id`="0")
                    GROUP BY mpkprod_mpkur_id
                ) AS sisa
            FROM program_studi ps
            LEFT JOIN mapel_kur_prodi mp ON ps.prodi_id = mp.mpkprod_prodi_id
            LEFT JOIN mapel_kurikulum mpk ON mp.`mpkprod_mpkur_id` = mpk.`mpkur_id`
            WHERE mp.mpkprod_mpkur_id = "'.$mpid.'"
            AND (mp.`mpkprod_related_id` IS NULL or mp.`mpkprod_related_id`="0")
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret;
    }
        
    public function get_concat_data_prodi($param){   
        $perpanjangan = true;
        $id = $param['prodi_id'];
        while ($perpanjangan) {
            $query_2 = '
                SELECT 
                    ps.prodi_nama,
                    ps.prodi_kode,
                    mp.`mpkprod_id`,
                    mp.`mpkprod_related_id`
                FROM program_studi ps
                LEFT JOIN mapel_kur_prodi mp ON ps.prodi_id = mp.mpkprod_prodi_id
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mpkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['mpkprod_id'];
                $param['prodi_nama'] .= ', '.$ret2[0]['prodi_nama'];
                $param['prodi_kode'] .= '-'.$ret2[0]['prodi_kode'];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $param;
    }

    private function get_mpprodid_by_mpid($mpid){
        $ret = $this->get_base_mpprodid_by_mpid($mpid);

        foreach ($ret as $key => $value) {
            $param = array(
                "prodi_id" => $value['mpkprod_id'],
                "prodi_kode" => $value['prodi_kode'],
                "prodi_nama" => $value['prodi_nama']
            );
            $prodi[$key] = $this->get_concat_data_prodi($param);
        }        

        $strret = "";
        if (isset($prodi)) {
            foreach ($prodi as $key => $value) {
                $strret .= $value['prodi_nama'].";<br>";
            }
        }        
        return $strret;
    }

    function get_count_mapel_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(mpkur_id) as total
            FROM mapel_kurikulum mpk
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_mapel_prodi($filter){
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
                IF(mpk.mpkur_is_universal=1,"display:none;","") AS display
            FROM mapel_kurikulum mpk
            --search--
            ORDER BY mpk.mpkur_is_universal
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mpprodid_by_mpid($value['id']);
        }      

        return $ret;
    }

    function get_count_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang r
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_count_program_studi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(prodi_id) as total
            FROM program_studi 
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }


    public function get_data_prodi_child($arr){
        $perpanjangan = true;
        $test[] = $arr;
        $id = $arr['id'];

        while ($perpanjangan) {
            $query_2 = '
                SELECT
                    mp.`mpkprod_id` AS id,
                    ps.`prodi_kode` AS kode,
                    ps.`prodi_nama` AS nama,
                    mp.`mpkprod_porsi_kelas` AS porsi,
                    mp.`mpkprod_related_id` AS rel_id
                FROM mapel_kur_prodi mp
                LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mpkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['id'];
                $test[] = $ret2[0];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $test;
    }

    function get_mapel_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);

        $query = '
            SELECT
                mp.`mpkprod_id` AS id,
                ps.`prodi_kode` AS kode,
                ps.`prodi_nama` AS nama,
                mp.`mpkprod_porsi_kelas` AS porsi,
                mp.`mpkprod_related_id` AS rel_id
            FROM mapel_kur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.mpkprod_mpkur_id = "'.$id.'"
            AND (mp.`mpkprod_related_id` IS NULL or mp.`mpkprod_related_id`="0")
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();


        // $ret = $this->get_base_mpprodid_by_mpid($id);
        $arr_ret = array();
        foreach ($ret as $key => $value) {
            $arr_ret[] = $this->get_data_prodi_child($value);
        }

        return $arr_ret;
    }

    public function get_data_prodi_last_child($arr){        
        $perpanjangan = true;
        $test = $arr;
        $id = $arr['id'];

        while ($perpanjangan) {
            $query_2 = '
                SELECT
                    mp.`mpkprod_id` AS id,
                    ps.`prodi_kode` AS label
                FROM mapel_kur_prodi mp
                LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
                WHERE 1=1
                --cond--
                
            ';

            $str = 'and mp.`mpkprod_related_id` = "'.$id.'"';
            $query_2 = str_replace('--cond--', $str, $query_2);

            $ret2 = $this->db->query($query_2);
            $ret2 = $ret2->result_array();
            if (!empty($ret2)) {
                $id = $ret2[0]['id'];
                $test = $ret2[0];
                $perpanjangan = true;
            }else{
                $perpanjangan = false;
            }
        }

        return $test;
    }

    function get_mapel_prodi_by_id_for_cb_parent($id){
        $query = '
            SELECT
                mp.`mpkprod_id` AS id,
                ps.`prodi_kode`,
                ps.`prodi_nama` AS label
            FROM mapel_kur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.mpkprod_mpkur_id = "'.$id.'"
            AND (mp.`mpkprod_related_id` IS NULL or mp.`mpkprod_related_id`="0")
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $arr_ret = array();
        foreach ($ret as $key => $value) {
            $arr_ret[] = $this->get_data_prodi_last_child($value);
        }

        $ret_cb[] = array(
            "id" => '0', "label" => "--pilih--"
        );
        if (!empty($arr_ret)) {
        foreach ($arr_ret as $key => $value) {
                $ret_cb[] = $value;
            } 
        }
        

        return $ret_cb;
    }

    function get_count_mapel_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(mpkprod_id) as total
            FROM mapel_kur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
            WHERE 1=1
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

    function get_program_studi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND prodi_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `prodi_id` AS id,
                  `prodi_kode` AS kode,
                  `prodi_nama` AS nama,
                  `prodi_prefix_mp` AS akronim,
                  `prodi_smt` AS smt,
                  `prodi_jml_siswa` AS jml_siswa
            FROM `program_studi`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function get_program_studi_except_in_mpid($idmp){
        $query = '
            SELECT 
                prodi_id as id,
                prodi_kode,
                prodi_nama as label
            FROM program_studi
            WHERE prodi_id NOT IN (
            SELECT
                ps.`prodi_id`
            FROM mapel_kur_prodi mp
            LEFT JOIN program_studi ps ON mp.`mpkprod_prodi_id` = ps.`prodi_id`
            WHERE mp.`mpkprod_mpkur_id` = "'.$idmp.'"
            )
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $ret_cb[] = array(
            "id" => '', "label" => "--pilih--"
        );
        foreach ($ret as $key => $value) {
            $ret_cb[] = $value;
        }

        return $ret_cb;
    }

    function update_program_studi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `program_studi`
            SET
                  `prodi_kode` = ?,
                  `prodi_nama` = ?,
                  `prodi_prefix_mp` = ?,
                  `prodi_smt` = ?,
                  `prodi_jml_siswa` = ?
            WHERE `prodi_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama, $akronim, $smt_all, $sw_all, $id));
    }

    function add_program_studi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `program_studi`
            (`prodi_kode`,`prodi_nama`,`prodi_prefix_mp`,`prodi_smt`,`prodi_jml_siswa`)
            VALUES (?,?,?,?,?)
        ";

        return $this->db->query($sql, array($kode, $nama, $akronim, $smt_all, $sw_all));
    }

    function del_program_studi($param){
        $sql = 'DELETE FROM program_studi WHERE prodi_id = "'.$param.'" ';
        return $this->db->query($sql); 
    }

    function get_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        if (isset($id_mp)) {
            $subquery = '
                SELECT mpkprod_prodi_id
                FROM mapel_kur_prodi
                WHERE mpkprod_mpkur_id = '.$id_mp
            ;
        }

        if (isset($id_ru)) {
            $subquery = '
                SELECT ru_id
                FROM ruang
                WHERE ru_id = '.$id_ru
            ;
        }

        $query = '
            SELECT
                ps.`prodi_id`,
                ps.`prodi_kode`,
                ps.`prodi_nama`,
                IF(prodi_id IN
                (
                '.$subquery.'
                ),"checked","") AS checked
            FROM program_studi ps
            --search--
            ORDER BY prodi_nama ASC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);
        
        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT
                count(prodi_id) as total
            FROM program_studi ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    function del_prodiru_by_idru($id){
        $query = '
            DELETE FROM ruang where ru_id = "'.$id.'"
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_prodiru($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO ruang
            (`ru_id`, `ru_kode`, `ru_prodi_id`,`ru_nama`, `ru_kapasitas`, `ru_jenis`)
            VALUES (?,?,?,?,?,?);
        ";
        
        return $this->db->query($sql, array($id, $kode,$prodi,$nama,$kapasitas,$is_cad)); 
    }

    function add_program_studi_on_mapel_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `mapel_kur_prodi`
            (`mpkprod_mpkur_id`,`mpkprod_prodi_id`,`mpkprod_related_id`,`mpkprod_porsi_kelas`)
            VALUES (?,?,?,?);
        ";
        
        return $this->db->query($sql, array($idmp, $prodi, $prodi_parent, $porsi)); 
    }

    function update_program_studi_on_mapel_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `mapel_kur_prodi`
            SET
                  `mpkprod_porsi_kelas` = ?
            WHERE `mpkprod_id` = ?
        ";

        return $this->db->query($sql, array($porsi, $id));
    }

    function get_mapel_prodi_by_idjoin($param){
        $query = '
            SELECT
              `mpkprod_id` AS id,
              `mpkprod_mpkur_id` AS idmp,
              `mpkprod_prodi_id` AS prodi_id,
              `mpkprod_related_id` AS rel_id,
              `mpkprod_porsi_kelas` AS porsi,
              (
              SELECT prodi_nama
              FROM program_studi
              WHERE prodi_id = mpkprod_prodi_id
              ) AS nama,
              (
              SELECT prodi_nama
              FROM program_studi
              WHERE prodi_id = (
                SELECT mpkprod_prodi_id
                FROM mapel_kur_prodi
                WHERE mpkprod_id = mp.mpkprod_related_id
                )
              ) AS program_studi_parent
            FROM `mapel_kur_prodi` mp
            WHERE mpkprod_id = "'.$param['id'].'"
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0];
    }

    function del_program_studi_on_mapel_prodi($param){
        $query = "DELETE FROM mapel_kur_prodi where mpkprod_id = '$param'";
        $ret = $this->db->query($query);
        return $ret;
    }

    function get_all_prodi(){
        $query = "SELECT prodi_id, prodi_kode, prodi_nama FROM program_studi";
        $ret = $this->db->query($query);
        return $ret->result_array();
    }
}