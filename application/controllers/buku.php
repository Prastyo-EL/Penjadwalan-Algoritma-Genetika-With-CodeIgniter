<?php
class Buku extends CI_Controller{
 Public  function __Construct()
    {
  parent ::__construct();
  $this->load->helper('form');
  $this->load->helper('url');
 }
 public  function index()
 {
  redirect('buku/list_buku');
 } 
 
 public  function list_buku()
     {
        $this->load->model('buku_model');
        $data['judul'] = '::DATA BUKU::';
       $data['databuku'] = $this->buku_model->get_buku_all();
        $this->load->view('buku_view', $data);
     }
}