<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Restdata.php';

class Getapi extends Restdata{

  public function __construct(){
    parent::__construct();
    $this->cektoken();
    }

    function index_get()
    {
      echo 'GET Request NOT Acceptable <br>'.current_url();
    }

    function index_post()
    {
      echo 'POST Request NOT Acceptable<br>'.current_url();
    }

    function index_put()
    {
      echo 'PUT Request Not Acceptable <br>'.current_url();
    }
    function index_delete()
    {
      echo 'DELETE Request Not Acceptable <br>'.current_url();
    }
      function siswa_get(){
        //$id = $this->uri->segment(3);
        $id = $this->get('idsiswa');
      if ($id == '') {
        $pro = $this->db->get('siswa')->result();
      } else {
        $this->db->where('idsiswa', $id);
        $pro = $this->db->get('siswa')->result();
      }
      $this->response($pro, 200);
    }
    
    function tambahsiswa_post(){
      $data = ['idsiswa'=>$this->post('idsiswa'),
               'nama'=>$this->post('nama'),
               'alamat'=>$this->post('alamat') ? $this->post('alamat') : NULL];
      $simpan = $this->db->insert('siswa', $data);
      if ($simpan) {
        $this->response([
          'status'=>'Success',
          'Inserted'=>$data],'HTTP_OK');
      }
    }

    function ubahsiswa_put(){

      $idsiswa = $this->put('idsiswa');

      $data = ['nama'=>$this->put('nama'),
               'alamat'=>$this->put('alamat') ? $this->put('alamat') : NULL];

      $this->db->where('idsiswa', $idsiswa);
      $update = $this->db->update('siswa', $data);

      if ($update) {
        $this->response([
          'status'=>'Success',
          'Inserted'=>$data],'HTTP_OK');
      }
    }


      function hapussiswa_delete() {
        $idsiswa = $this->delete('idsiswa');
        $this->db->where('idsiswa', $idsiswa);
        $delete = $this->db->delete('siswa');
        if ($delete) {
             $this->response([
            'status'=>'Success'],'HTTP_OK');
        } 
    }

    }