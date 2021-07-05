<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . '/libraries/src/JWT.php';
require APPPATH . '/libraries/src/BeforeValidException.php';
require APPPATH . '/libraries/src/ExpiredException.php';
require APPPATH . '/libraries/src/SignatureInvalidException.php';
use \Firebase\JWT\JWT;use Restserver\Libraries\REST_Controller;

class Restdata extends REST_Controller{

  private $secretkey = 'shadowblade';

  public function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
  }


  //method untuk not found 404
  public function notfound($pesan){

    $this->response([
      'status'=>FALSE,
      'message'=>$pesan
    ],REST_Controller::HTTP_NOT_FOUND);

  }

  //method untuk bad request 400
  public function badreq($pesan){
    $this->response([
      'status'=>FALSE,
      'message'=>$pesan
    ],REST_Controller::HTTP_BAD_REQUEST);
  }

  //method untuk melihat token pada user
  public function viewtoken_post(){
    $this->load->model('Login_model');

    $date = new DateTime();

    $username = $this->post('username',TRUE);
    $pass = $this->post('password',TRUE);

    $dataadmin = $this->Login_model->is_valid($username);

    if ($dataadmin) {

      if ($pass == $dataadmin->password) {

        $payload['id'] = $dataadmin->iduser;
        $payload['username'] = $dataadmin->username;
        $payload['iat'] = $date->getTimestamp(); //waktu di buat
        $payload['exp'] = $date->getTimestamp() + 3600; //satu jam

        $output['id_token'] = JWT::encode($payload,$this->secretkey);
        $this->response([
      'status'=>'Success',
      'Message'=>'Token will expired in one hour.',
      'Token'=>$output,
      ],'HTTP_OK');
      }else {
        $this->viewtokenfail($username,$pass);
      }
    }else {
      $this->viewtokenfail($username,$pass);
    }
  }

  //method untuk jika view token diatas fail
  public function viewtokenfail($username,$pass){
    $this->response([
      'status'=>'Hihi Gagal.!!',
      'username'=>$username,
      'password'=>$pass,
      'message'=>'Username dan Password yang anda masukan salah'
      ],'HTTP_BAD_REQUEST');
  }

//method untuk mengecek token setiap melakukan post, put, etc
  public function cektoken(){
    $this->load->model('Login_model');
    $jwt = $this->input->get_request_header('Authorization');

    try {

      $decode = JWT::decode($jwt,$this->secretkey,array('HS256'));
      //melakukan pengecekan database, jika username tersedia di database maka return true
      if ($this->Login_model->is_valid_num($decode->username)>0) {
        return true;
      }

    } catch (Exception $e) {
      exit('Tokennya Expired Kali </3');
    }
  }
}