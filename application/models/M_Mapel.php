<?php

class M_Mapel extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	/*
	function get_by_semester_type($semester_type){
		$rs = $this->db->query(
								"SELECT * ".
								"FROM mata_pelajaran ".
								"WHERE semester%2=$semester_type ".
								"ORDER BY nama");
		return $rs;
	}
	*/
	
	
	
	function get(){
		$rs = $this->db->query(
							   "SELECT kode,".
								"       kode_mp,".
								"       nama,".
								"       sks,".
								"       semester,".								
								"       jenis ".
								"FROM mata_pelajaran ".
								"ORDER BY $this->sort $this->order ".
								"LIMIT $this->offset,$this->limit");
		return $rs;		
	}
	
	function get_all(){
		$rs = $this->db->query(
							   "SELECT kode,".
								"       kode_mp,".
								"       nama,".
								"       sks,".
								"       semester,".								
								"       jenis ".
								"FROM mata_pelajaran ");
					
		return $rs;		
	}
	
	function get_by_semester($semester){
		$rs = $this->db->query(
							   "SELECT kode,".								
								"       nama ".								
								"FROM mata_pelajaran ".
								"WHERE semester%2=$semester ORDER BY nama");
		return $rs;		
	}
	
	function get_by_kode($kode){
		$rs = $this->db->query(
							   "SELECT kode,".
								"       kode_mp,".
								"       nama,".
								"       sks,".
								"       semester,".								
								"       jenis ".
								"FROM mata_pelajaran ".
								"WHERE kode= $kode");
		return $rs;		
	}
	
	function get_search($search){
		$rs = $this->db->query(	"SELECT kode,".
								"       kode_mp,".
								"       nama,".
								"       sks,".
								"       semester,".								
								"       jenis ".
								"FROM mata_pelajaran ".								
								"WHERE nama LIKE '%$search%'");
		return $rs;		
	}
	
	function num_page(){
    	
    	$result = $this->db->from('mata_pelajaran')
                           ->count_all_results();
        return $result;
    }
	
	function cek_for_update($kode_mp,$nama,$kode){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM mata_pelajaran ".
							   "WHERE (kode_mp=$kode_mp OR nama='$nama') AND kode <> $kode");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($kode_mp,$nama){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM mata_pelajaran ".
							   "WHERE kode_mp=$kode_mp OR nama='$nama'");
		return $rs->row()->cnt;
	}
	
	function update($kode,$data){
        $this->db->where('kode',$kode);
        $this->db->update('mata_pelajaran',$data);
    }
	
	function insert($data){
        $this->db->insert('mata_pelajaran',$data);		
    }
	
	function delete($kode){
		$this->db->query("DELETE FROM mata_pelajaran WHERE kode = '$kode'");
	}
	
}