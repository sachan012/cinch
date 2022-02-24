<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model
{
    public $table = 'members';  // cutomer table for login credentials
    public $primary_key = 'id'; // you MUST mention the primary key
    /**
     * Update a user, password will be hashed
     *
     * @param int id
     * @param array user
     * @return int id
     */    

    public function check_access_web_app_token($access_token){       
        $condition = array('access_token'=>$access_token);
        $check_access_token = $this->db->select('*')->where($condition)->get('members')->row_array();
        $user = false;
        if ($check_access_token) {
            $user = $check_access_token['id'];
        }
        //print_r($user);die;
        return $user;
    }

    public function email_exists($where, $value = FALSE){
        if (!$value) {
            $value = $where;
            $where = 'email';
        }
        return $this->db->where($where, $value)->count_all_results("members");
    }

    function add_user($user_data){
       $this->db->insert("members", $user_data);
       $insert_id = $this->db->insert_id();
       return  $insert_id;
    }

    public function get($where, $value = FALSE){
        if (!$value) {
            $value = $where;
            $where = 'id';
        }
        $user = $this->db->where($where, $value)->get("members")->row_array();
        return $user;
    }   

    



    public function exists($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }

        return $this->db->where($where, $value)->count_all_results("users");
    }


    public function phone_exists($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'mobile';
        }

        return $this->db->where($where, $value)->count_all_results("users");
    }

       


  








  
   

   

}

