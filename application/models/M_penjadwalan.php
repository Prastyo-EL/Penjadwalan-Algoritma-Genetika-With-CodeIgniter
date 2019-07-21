<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_penjadwalan extends CI_Model {


    function __construct(){
        parent::__construct();
    }

    public function get_base_mpprodid_by_mpid($mpid){
        $query = '
            SELECT 
                ps.prodi_nama,
                ps.prodi_kode,
                ps.prodi_smt,
                ps.prodi_jml_siswa,
                mp.`mpkprod_id`,
                mp.`mpkprod_porsi_kelas`,
                mpk.`mpkur_prod_jml_peminat`,
                (
                SELECT SUM(mpv.mpkprod_porsi_kelas)
                FROM mapel_kur_prodi mpv
                WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                AND mpv.`mpkprod_related_id` = 0
                GROUP BY mpkprod_mpkur_id
                ) AS t,
                mpkprod_porsi_kelas * (mpk.`mpkur_prod_jml_peminat` DIV
                (
                    SELECT SUM(mpv.mpkprod_porsi_kelas)
                    FROM mapel_kur_prodi mpv
                    WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                    AND mpv.`mpkprod_related_id` = 0
                    GROUP BY mpkprod_mpkur_id
                )) AS jml_porsi,    
                mpk.`mpkur_prod_jml_peminat` MOD
                (
                    SELECT SUM(mpv.mpkprod_porsi_kelas)
                    FROM mapel_kur_prodi mpv
                    WHERE mpv.mpkprod_mpkur_id = mp.`mpkprod_mpkur_id`
                    AND mpv.`mpkprod_related_id` = 0
                    GROUP BY mpkprod_mpkur_id
                ) AS sisa
            FROM program_studi ps
            LEFT JOIN mapel_kur_prodi mp ON ps.prodi_id = mp.mpkprod_prodi_id
            LEFT JOIN mapel_kurikulum mpk ON mp.`mpkprod_mpkur_id` = mpk.`mpkur_id`
            WHERE mp.mpkprod_mpkur_id = "'.$mpid.'"
            AND mp.`mpkprod_related_id` = 0
            AND mp.`mpkprod_porsi_kelas` <> 0
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

        // echo '<pre>'; print_r($strret); 
        return $strret;
    }
    
    function get_count_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mpkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT count(mpkur_id) as total
            FROM mapel_kurikulum mpk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

    function get_matakuliah($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mpkur_semester = '$semester_aktif'";   
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
                IF(mpkur_prod_jml_peminat IS NULL,"Belum diketahui",mpkur_prod_jml_peminat) AS prod_jml_peminat,
                mpkur_maks_kelas as maks_kelas
            FROM mapel_kurikulum mpk
            -- LEFT JOIN program_studi ps ON mpk.`mapel_kur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
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

    function cek_kelas_ada(){
        $query = '
            SELECT 
                COUNT(k.`kls_id`) AS jml_kelas
            FROM kelas k
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['jml_kelas'];
    }

    function get_data_mapel($semester_aktif){
        $str = '';
        if (!empty($semester_aktif)) {
            $str .= "AND mpkur_semester = '$semester_aktif'";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                mpk.`mpkur_id` AS id,
                mpk.`mpkur_kode` AS kode,
                mpk.`mpkur_nama` AS nama,
                mpk.`mpkur_paket_semester` AS pkt_smt,
                mpk.`mpkur_semester` AS smt,
                mpk.`mpkur_jumlah_pert` AS jml_pert,                
                IF(mpkur_prod_jml_peminat IS NULL,0,mpkur_prod_jml_peminat) AS jml_peminat,
                mpk.`mpkur_is_universal` AS is_universal,
                -- ps.`prodi_kode` AS kode_prodi,
                mpk.mpkur_sifat AS sifat,
                mpkur_maks_kelas as maks_kelas
            FROM mapel_kurikulum mpk
            -- LEFT JOIN program_studi ps ON mpk.`mapel_kur_prodi_id` = ps.`prodi_id`
            WHERE 1=1
            --search--
        ';
        
        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $prodi = array();
        foreach ($ret as $key => $value) {
            $ret[$key]['nama_prodi'] = $this->get_mpprodid_by_mpid($value['id']);
        }

        return $ret;
    }

    function del_grkelas_ref_kelas(){
        $query = '
            DELETE FROM guru_kelas WHERE
            `grkls_kls_id` IN ( 
            SELECT
                k.`kls_id`
            FROM kelas k)
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function del_record_kelas(){
        $query = 'DELETE FROM kelas';
        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_kelas($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `kelas`
            (`kls_kode_prodi`,`kls_mpkur_id`,`kls_nama`,`kls_kode_paralel`,kls_jml_peserta_prediksi, kls_jadwal_merata)
            VALUES (?,?,?,?,?,?);
        ";
        
        return $this->db->query($sql, array($prodi, $id_mapel, $nama_kelas, $kelas, $jumlah_per_kelas, $kls_jadwal_merata)); 
    }

    function cek_guru_kelas_lengkap(){
        $query = '
            SELECT
                IF(COUNT(k.`kls_id`)>0,FALSE,TRUE) AS kelas_guru_lengkap
            FROM kelas k
            WHERE (
            SELECT COUNT(`grkls_id`)
            FROM guru_kelas dk
            WHERE dk.`grkls_kls_id` = k.`kls_id`
            ) = 0
        ';

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        $jml_kelas = $this->cek_kelas_ada();
        $sts_kelas = $jml_kelas>0?true:false;
        
        return ($sts_kelas && $ret[0]['kelas_guru_lengkap']);
    }

    function get_kelas($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        if (!empty($search)) {
            $str = "WHERE k.`kls_kode_prodi` LIKE '%$search%' 
                    OR k.`kls_nama` LIKE '%$search%' 
                    OR mpk.`mpkur_nama` LIKE '%$search%' 
                    OR mpk.`mpkur_paket_semester` LIKE '%$search%' 
                    OR mpk.`mpkur_sifat` LIKE '%$search%'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                k.`kls_id` AS id,
                k.`kls_kode_prodi` AS kd_prodi,
                k.`kls_nama` AS nama_kelas,
                mpk.mpkur_sifat AS sifat,
                mpk.mpkur_paket_semester AS paket_smt,
                mpk.`mpkur_nama` AS nama_mapel,
                mpk.`mpkur_sks` AS sks,
                k.`kls_jml_peserta_prediksi` AS jml_peserta_kls,
                IF((
                SELECT COUNT(`grkls_id`)
                FROM guru_kelas dk
                WHERE dk.`grkls_kls_id` = k.`kls_id`
                )>0,                
                (
                SELECT GROUP_CONCAT(guru_nama SEPARATOR "<br>")
                FROM guru_kelas dk
                LEFT JOIN guru d ON dk.`grkls_gr_id` = d.`guru_id`
                WHERE dk.`grkls_kls_id` = k.`kls_id`
                )
                ,"<span class=\"badge alert-danger\">belum ditentukan</span>") AS guru_kelas
            FROM kelas k
            LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
            --search--
            ORDER BY k.`kls_kode_prodi`, k.`kls_nama`
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_kelas($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT 
                count(k.`kls_id`) AS total
            FROM kelas k
            LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    function get_guru($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT
                d.`guru_id`,
                d.`guru_nip`,
                d.`guru_nama`,
                IF(guru_id IN
                (
                SELECT grkls_gr_id
                FROM guru_kelas
                WHERE grkls_kls_id = '.$idkls.'
                ),"checked","") AS checked
            FROM guru d
            --search--
            ORDER BY guru_nama ASC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);
        
        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_count_guru($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT
                count(d.`guru_id`) as total
            FROM guru d
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();

        return $ret[0]['total'];
    }

    function del_grkelas_by_idkelas($id_kelas){
        $query = '
            DELETE FROM guru_kelas where grkls_kls_id = "'.$id_kelas.'"
        ';

        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_gurukelas($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO guru_kelas
            (`grkls_gr_id`, `grkls_kls_id`)
            VALUES (?,?);
        ";
        
        return $this->db->query($sql, array($id_guru, $id_kelas)); 
    }

    function get_all_kelas(){
        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                k.`kls_id` AS id,
                mpkur_is_universal AS is_universal,
                mpkur_jns AS praktikum,
                k.`kls_kode_prodi` AS kd_prodi,
                k.`kls_kode_paralel` AS label_kelas,
                k.`kls_nama` AS nama_kelas,
                mpkur_id,
                mpk.`mpkur_nama` AS nama_mapel,
                mpk.`mpkur_jumlah_pert` AS jml_pert,
                
                (
                SELECT
                    GROUP_CONCAT(mpkprod_prodi_id SEPARATOR "|")
                FROM mapel_kur_prodi 
                WHERE mpkprod_mpkur_id = mpk.`mpkur_id`
                ) AS kelas_prodi,

                k.`kls_jml_peserta_prediksi` AS jml_peserta_kls,
                IF((
                SELECT COUNT(`grkls_id`)
                FROM guru_kelas dk
                WHERE dk.`grkls_kls_id` = k.`kls_id`
                )>0,                
                (
                SELECT GROUP_CONCAT(guru_nama SEPARATOR "<br>")
                FROM guru_kelas dk
                LEFT JOIN guru d ON dk.`grkls_gr_id` = d.`guru_id`
                WHERE dk.`grkls_kls_id` = k.`kls_id`
                )
                ,"<span class=\"label label-important\">belum ditentukan</span>") AS guru_kelas,
                mpk.mpkur_sks AS sks,
                mpkur_paket_semester AS paket_smt,
                mpkur_semester AS smt_mapel,
                mpkur_sifat AS sifat_mapel,                
                (
                SELECT
                GROUP_CONCAT(grwkt_wkt_id SEPARATOR "|")
                FROM guru_waktu
                WHERE grwkt_gr_id IN (
                    SELECT guru_id
                    FROM guru_kelas dk
                    LEFT JOIN guru d ON dk.`grkls_gr_id` = d.`guru_id`
                    WHERE dk.`grkls_kls_id` = k.`kls_id`
                )
                ) AS alternatif_waktu_ajar,
                (
                SELECT COUNT(*) AS cnt 
                FROM kelas k2 
                WHERE k2.kls_mpkur_id = mpk.`mpkur_id`
                ) AS order_col,
				kls_jadwal_merata
            FROM kelas k
            LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
            ORDER BY kls_jml_peserta_prediksi DESC, order_col DESC
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_ruang(){
        $query = '
            SELECT
                r.`ru_id`,
                r.`ru_kode`,
                r.`ru_nama`,
                r.`ru_kapasitas`,
                r.`ru_jenis`
            FROM ruang r ORDER BY r.`ru_id` DESC
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_ruang(){
        $query = '
            SELECT
                r.`ru_id`,
                r.`ru_kode`,
                r.`ru_nama`,
                r.`ru_kapasitas`,
                r.`ru_jenis`
            FROM ruang r ORDER BY r.`ru_jenis`,r.`ru_kode`
        ';
        $ret = $this->db->query($query);
        return $ret->result_array();
    }

    function get_all_waktu(){
        $query = '
            SELECT
                w.`waktu_id`,
                w.`waktu_hari`,
                w.`waktu_jam_mulai`,
                w.`waktu_jam_selesai`,
                w.`waktu_is_belajar`
            FROM waktu w
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function get_all_prodi(){
        $query = "
                SELECT ps.`prodi_id`, ps.`prodi_kode`, ps.`prodi_nama`, ps.`prodi_prefix_mp`
                    FROM program_studi ps
                GROUP BY ps.prodi_id
        ";
        $ret = $this->db->query($query);
        return $ret->result_array();
    }

    function get_all_jadwal_pelajaran(){
        $query = '
            SELECT
                jp_id,
                jp_kls_id,
                jp_wkt_id,
                jp_ru_id,
                jp_period,
                jp_label,
				k.kls_nama,
                k.kls_kode_prodi AS kd_prodi,
                k.kls_kode_paralel AS label_kls,
                mpk.`mpkur_nama` AS nama_mapel,
                mpk.`mpkur_paket_semester` AS smt
            FROM jadwal_pelajaran jp
			LEFT JOIN kelas k ON jp.jp_kls_id = k.kls_id
			LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }
    
    function get_idguru_by_idkelas($id_kelas){
        $str = 'AND dk.`grkls_kls_id` = "'.$id_kelas.'"';

        $query = '
            SELECT 
                dk.`grkls_gr_id` AS id_guru
            FROM guru_kelas dk
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val;
    }
    
    function getJamMaksSabtu(){

        $query = '
            SELECT 
            MAX(w.`waktu_jam_selesai`) AS maks_jam
            FROM waktu w
            WHERE w.`waktu_hari` = "sabtu"
        ';

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['maks_jam'];
    }

    function del_jadwal(){
        $query = 'DELETE FROM jadwal_pelajaran';
        $ret = $this->db->query($query);
        return $ret;
    }

    function ins_jadwal($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO jadwal_pelajaran
            (jp_kls_id, jp_wkt_id, jp_ru_id, jp_period, jp_label)
            VALUES (?, ?, ?, ?, ?);
        ";
        
        return $this->db->query($sql, array($id_kelas, $id_waktu, $id_ruang, $period, $label)); 
    }

    function get_jadwal_to_export(){
        $query = "
            SELECT 
                mpk.`mpkur_kode` AS mapel_kode,
                mpk.`mpkur_nama` AS mapel_nama,
                mpk.`mpkur_paket_semester` AS paket_sem,
                k.`kls_nama`,
                mpk.`mpkur_sks` AS sks,
                (
                    SELECT GROUP_CONCAT(guru_nama SEPARATOR '; ')
                    FROM guru_kelas dk
                    LEFT JOIN guru d ON dk.`grkls_gr_id` = d.`guru_id`
                    WHERE dk.`grkls_kls_id` = k.`kls_id`                
                    ) AS guru_kelas,
                jp.`jp_ru_id` AS ru_id,
                r.`ru_nama` AS ru_nama,
                k.`kls_jml_peserta_prediksi` AS jml_peserta,
                w.waktu_jam_mulai AS jam_mulai,
                jp.`jp_period`*50 AS durasi_menit,
                IF(w.`waktu_hari` = 'senin',
                    1,
                    ''
                ) AS senin,
                IF(w.`waktu_hari` = 'selasa',
                    1,
                    ''
                ) AS selasa,
                IF(w.`waktu_hari` = 'rabu',
                    1,
                    ''
                ) AS rabu,
                IF(w.`waktu_hari` = 'kamis',
                    1,
                    ''
                ) AS kamis,
                IF(w.`waktu_hari` = 'jumat',
                    1,
                    ''
                ) AS jumat,
                jp.`jp_label`
            FROM jadwal_pelajaran jp
            LEFT JOIN ruang r ON jp.`jp_ru_id` = r.`ru_id`
            LEFT JOIN waktu w ON jp.`jp_wkt_id` = w.`waktu_id`
            LEFT JOIN kelas k ON jp.`jp_kls_id` = k.`kls_id`
            LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
            ORDER BY mpk.`mpkur_id` ASC
            
        ";

        $ret = $this->db->query($query);

        return $ret->result_array();
    }

    function getDataSessions(){
		$this->db->select('t1.*');
		$this->db->from('ci_sessions AS t1');
		$query = $this->db->get();
		$ret = $query->result();

		return $ret;
	} 

    // Custom function
    function get_prodi_smt_by_mp_smt($param){
        $query = "SELECT ps.prodi_kode, ps.prodi_smt, ps.prodi_jml_siswa FROM program_studi ps 
                  WHERE ps.prodi_smt LIKE '%$param%'";
        $ret = $this->db->query($query);
        return $ret->result_array();
    }

    function get_jadwal_by_id($id){
        $query = 'SELECT jp_id, jp_kls_id, jp_wkt_id, jp_ru_id, jp_period, jp_label,
                    k.kls_nama, mpk.`mpkur_nama` AS nama_mapel, mpk.`mpkur_jns` AS 
                    jns_mapel, mpk.`mpkur_paket_semester` AS smt,
                    g.guru_id, g.guru_nama, w.waktu_jam_mulai,w.waktu_hari, w.waktu_jam_selesai, r.ru_nama
                    FROM jadwal_pelajaran jp          
                    LEFT JOIN kelas k ON jp.jp_kls_id = k.kls_id
                    LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id` 
                    LEFT JOIN guru_kelas gk ON k.kls_id=gk.grkls_kls_id
                    LEFT JOIN guru g ON g.guru_id=gk.grkls_gr_id
                    LEFT JOIN waktu w ON w.waktu_id=jp.jp_wkt_id
                    LEFT JOIN ruang r ON r.ru_id=jp.jp_ru_id
                    WHERE jp_id="'.$id.'"
        ';
        $ret = $this->db->query($query);
        return $ret->result_array();
    }

    function get_all_jadwal_edit(){
        $query = '
            SELECT
                jp_id,
                jp_kls_id,
                jp_wkt_id,
                jp_ru_id,
                jp_period,
                jp_label,
                k.kls_nama,             
                mpk.`mpkur_nama` AS nama_mapel,
                mpk.`mpkur_jns` AS jns_mapel,
                g.guru_id
            FROM jadwal_pelajaran jp          
            LEFT JOIN kelas k ON jp.jp_kls_id = k.kls_id
            LEFT JOIN mapel_kurikulum mpk ON k.`kls_mpkur_id` = mpk.`mpkur_id`
            LEFT JOIN guru_kelas gk ON k.kls_id=gk.grkls_kls_id
            LEFT JOIN guru g ON g.guru_id=gk.grkls_gr_id
        ';

        $ret = $this->db->query($query);

        return $ret->result_array();
    }
    function update_jadwal($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `jadwal_pelajaran`
            SET
                  `jp_wkt_id` = ?,
                  `jp_ru_id` = ?,
                  `jp_jam_selesai` = ?,
                  `jp_label` = ?
            WHERE `jp_id` = ?
        ";

        return $this->db->query($sql, array($id_wk, $id_ru, $js, $rn.','.$wh.' '.$jm.' '.$js, $id));
    }

    function list_jadwal($filter){
        if (is_array($filter))
            extract($filter);

        $str = '';
        if (!empty($search)) {
            $str = "WHERE kelas.kls_kode_prodi LIKE '%$search%' 
                    OR ruang.ru_nama LIKE '%$search%' 
                    OR mapel_kurikulum.mpkur_nama LIKE '%$search%' 
                    OR waktu.waktu_hari LIKE '%$search%'
                    OR waktu.waktu_jam_mulai LIKE '%$search%'
                    OR guru.guru_nama LIKE '%$search%'";
        }
        if (!empty($pd)) {
            $str = "WHERE kelas.kls_kode_prodi='$pd'";
        }

        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";
        }

        $query = '
            SELECT jadwal_pelajaran.jp_id AS id_jp,
                jadwal_pelajaran.jp_label AS label,
                kelas.kls_kode_prodi AS kd_prodi,
                kelas.kls_nama AS kelas, 
                mapel_kurikulum.mpkur_paket_semester AS smt, 
                mapel_kurikulum.mpkur_nama AS mapel, 
                guru.guru_nama AS guru 
                FROM guru JOIN guru_kelas ON guru.guru_id=guru_kelas.grkls_gr_id JOIN kelas ON 
                kelas.kls_id=guru_kelas.grkls_kls_id JOIN mapel_kurikulum ON 
                mapel_kurikulum.mpkur_id=kelas.kls_mpkur_id JOIN jadwal_pelajaran ON 
                jadwal_pelajaran.jp_kls_id=kelas.kls_id JOIN waktu ON 
                waktu.waktu_id=jadwal_pelajaran.jp_wkt_id JOIN ruang ON 
                ruang.ru_id=jadwal_pelajaran.jp_ru_id
            --search--
            ORDER BY kelas.kls_kode_prodi, mapel_kurikulum.mpkur_paket_semester ASC,
            waktu.waktu_hari DESC, waktu.waktu_jam_mulai ASC
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

    function get_count_jadwal($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        $str1 = '';
        if (!empty($semester_aktif)) {
            $str .='JOIN kelas ON kelas.kls_id=jadwal_pelajaran.jp_kls_id 
                JOIN mapel_kurikulum ON mapel_kurikulum.mpkur_id=kelas.kls_mpkur_id';
            $str1 .= "AND mpkur_semester = '$semester_aktif'";
        }

        $query = '
            SELECT count(jp_id) as total
            FROM jadwal_pelajaran jp
            --join--
            WHERE 1=1
            --search--
        ';
        $query = str_replace('--join--', $str, $query);
        $query = str_replace('--search--', $str1, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }

}