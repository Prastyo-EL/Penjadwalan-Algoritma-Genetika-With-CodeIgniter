<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_pengelolaan extends CI_Model {

    function __construct(){
        parent::__construct();
    }

/*************************Konfigurasi**********************************************/
    
    function get_konfig($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        if (!empty($id)) {
            $str = "AND conf_id = $id";   
        }
        
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                c.`conf_id` AS id,
                c.`conf_name` AS nama,
                c.`conf_value` AS nilai
            FROM config c
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();       

        return $ret;
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

    function get_count_konfig($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                count(c.`conf_id`) AS total
            FROM config c
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function update_konfig($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE config
            SET conf_value = ?
            WHERE conf_id = ?
        ";

        return $this->db->query($sql, array($nilai, $id)); 
    }

    function del_konfig($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM config
            WHERE conf_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

/*************************Mata Pelajaran*******************************************/

    function get_mapel($filter){
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
        /*$query = 'SELECT *FROM guru,mapel_kurikulum where guru.guru_id=mapel_kurikulum.id_guru';*/
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mpk.`mpkur_id` AS id,
                mpk.`mpkur_kode` AS kode,
                mpk.`mpkur_nama` AS nama,
                mpk.`mpkur_paket_semester` AS paket,
                mpk.`mpkur_semester` AS smt,
                mpk.`mpkur_sks` AS sks,
                IF(mpkur_jns=0,"Teori","Praktik") AS jns,
                IF(mpkur_prod_jml_peminat IS NULL,"Belum diketahui",mpkur_prod_jml_peminat) AS prod_jml_peminat
            FROM mapel_kurikulum mpk 
            --search--
            ORDER BY paket,nama
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_mapel_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND mpkur_id = $id";
        }

        $query = '
            SELECT
                  `mpkur_id` AS id,
                  `mpkur_kode` AS kode,
                  `mpkur_nama` AS nama,
                  `mpkur_sks` AS sks,
                  `mpkur_semester` AS smt,
                  `mpkur_sifat` AS sifat,
                  `mpkur_paket_semester` AS paket,
                  `mpkur_jumlah_pert` AS jml_pert,
                  `mpkur_is_universal` AS is_univers,
                   mpkur_maks_kelas AS maks_kelas,
                   mpkur_jns AS jns_mp
            FROM `mapel_kurikulum`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function get_all_mapel(){
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mpk.`mpkur_id` AS id,
                mpk.`mpkur_kode` AS kode,
                mpk.`mpkur_nama` AS nama,
                mpk.`mpkur_paket_semester` AS paket,
                mpk.`mpkur_semester` AS smt,
                "null" AS nama_prodi,
                mpk.`mpkur_sks` AS sks,
                "Belum diketahui" AS jml_peminat
            FROM mapel_kurikulum mpk
            -- LEFT JOIN program_studi ps ON mpk.`mpkur_prodi_id` = ps.`prodi_id`
            ORDER BY mpk.`mpkur_semester`
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mpprodid_by_mpid($value['id']);
        }
        

        return $ret;
    }

    function get_count_mapel($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE mpk.`mpkur_kode` LIKE '%$search%' 
                    OR mpk.`mpkur_nama` LIKE '%$search%' 
                    OR mpk.`mpkur_paket_semester` LIKE '%$search%' 
                    OR mpk.`mpkur_semester` LIKE '%$search%'";
        }

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

    function update_mapel($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `mapel_kurikulum`
            SET
                  `mpkur_kode` = ?,
                  `mpkur_nama` = ?,
                  `mpkur_sks` = ?,
                  `mpkur_semester` = ?,
                  `mpkur_sifat` = ?,
                  `mpkur_paket_semester` = ?,
                  `mpkur_jumlah_pert` = ?,
                  `mpkur_is_universal` = ?,
                  mpkur_maks_kelas = ?,
                  mpkur_jns = ?
            WHERE `mpkur_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama,$sks, $smt, $sifat, $paket, $jml_pert, $is_univr, $maks_kelas, $jns_mp, $id));
    }

    function add_mapel($param){
        if (is_array($param))
            extract($param);
        $sql = "
            INSERT INTO `mapel_kurikulum`
            (`mpkur_kode`,`mpkur_nama`,`mpkur_sks`,`mpkur_semester`,`mpkur_sifat`,`mpkur_paket_semester`,`mpkur_jumlah_pert`,`mpkur_is_universal`, mpkur_maks_kelas, mpkur_jns)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        ";
        $return = $this->db->query($sql, array($kode, $nama,$sks, $smt, $sifat, $paket, $jml_pert, $is_univr, $maks_kelas, $jns_mp));
        return $return;
    }

    function del_mapel($param){
        $sql = "DELETE FROM mapel_kurikulum WHERE mpkur_id = $param";
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
                IF(mpkur_jns=0,"Teori","Praktik") AS jns
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

    function cetak_jadwal(){
        $query = 'SELECT kelas.kls_nama AS kelas, kelas.kls_kode_prodi AS kd_prodi,
                  mapel_kurikulum.mpkur_paket_semester AS smt, 
                  mapel_kurikulum.mpkur_nama AS mapel, 
                  ruang.ru_kode AS ruang, 
                  waktu.waktu_hari AS hari, 
                  waktu.waktu_jam_mulai AS jam_mulai,
                  waktu.waktu_jam_selesai AS jam_selesai,
                  guru.guru_nama AS guru 
                  FROM guru JOIN guru_kelas ON guru.guru_id=guru_kelas.grkls_gr_id JOIN kelas ON 
                  kelas.kls_id=guru_kelas.grkls_kls_id JOIN mapel_kurikulum ON 
                  mapel_kurikulum.mpkur_id=kelas.kls_mpkur_id JOIN jadwal_pelajaran ON 
                  jadwal_pelajaran.jp_kls_id=kelas.kls_id JOIN waktu ON 
                  waktu.waktu_id=jadwal_pelajaran.jp_wkt_id JOIN ruang ON 
                  ruang.ru_id=jadwal_pelajaran.jp_ru_id 
                  ORDER BY kelas.kls_kode_prodi,mapel_kurikulum.mpkur_paket_semester, waktu.waktu_hari DESC,
                  waktu.waktu_jam_mulai ASC';
        $ret = $this->db->query($query);
        $ret = $ret->result();
        return $ret;
    }

}