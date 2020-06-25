<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
    
    function __construct() {
        $this->user   = 'users';
    }
    
    public function InserUser($data){
        $insert = $this->db->insert($this->user,$data);
        return $insert?true:false;
    }



    public function GetUser($id = ''){
        $this->db->select('*');
        $this->db->from($this->user);
       
        if($id){
            $array = array('id' => $id, 'status' => '0');
            $this->db->where($array);
            $query  = $this->db->get();
            $result = $query->row_array();
        }else{
            $query  = $this->db->get();
            $result = $query->result_array();
        }
        
        // return fetched data
        return !empty($result)?$result:false;
    }

     public function VerifyUser($id = ''){
        $this->db->select('*');
        $this->db->from($this->user);
       
        if($id){
            $array = array('email' => $id);
            $this->db->where($array);
            $query  = $this->db->get();
            $result = $query->num_rows();
        }
        
        // return fetched data
        return !empty($result)?$result:false;
    }


    public function GetUserEmail($id = ''){
        $this->db->select('*');
        $this->db->from($this->user);
       
        if($id){
            $array = array('email' => $id, 'status' => '0');
            $this->db->where($array);
            $query  = $this->db->get();
            $result = $query->row_array();
        }
        // return fetched data
        return !empty($result)?$result:false;
    }

}?>