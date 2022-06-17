<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model
{
   
    public $primary_key = 'id'; // you MUST mention the primary key
    /**
     * Update a user, password will be hashed
     *
     * @param int id
     * @param array user
     * @return int id
     */  


     function _login($email,$password,$usertype){      
        $this->db->select('ci_users.*,ci_admin_roles.admin_role_id,ci_admin_roles.admin_role_title');
        $this->db->from('ci_users');
        $this->db->join('ci_admin_roles','ci_admin_roles.admin_role_id = ci_users.role'); 
        if($usertype=="marshal"){
            $this->db->where('ci_users.username', $email);
        }
        if($usertype=="manager"){
             $this->db->where('ci_users.email', $email);
        }               
        $this->db->where('ci_users.password', $password);
        $this->db->where('ci_admin_roles.admin_role_title', $usertype);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() == 0){
            return false;
        }
        else{
            //Compare the password attempt with the password we have stored.
            $result = $query->row_array();
            //echo "<pre>";print_r($result);die;
            return $result;            
        }        
    }   


   

    public function column_exists($where, $value = FALSE)
    {
        if (!$value) {
            $value = $where;
            $where = 'email';
        }
        return $this->db->where($where, $value)->count_all_results("ci_users");
    }

    function add_user($user_data){               
        $this->db->insert("ci_users", $user_data);
        return $this->db->insert_id();        
    }

    function getStationDetailByUserId($userid){
        $this->db->select('ci_users.id,station_list.*');
        $this->db->from('ci_users');
        $this->db->join('station_list','station_list.station_code = ci_users.assigned_station');   
        $this->db->where('ci_users.id', $userid);       
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return false;
        }
        else{           
            $result = $query->row_array();
            //echo "<pre>";print_r($result);die;
            return $result;          
        }        

    }

    public function fetchStationQueues($userid,$station_code){
        $this->db->select('marshal_station_code');
        $this->db->from('queue_list');
        $this->db->where('marshal_station_code', $station_code);       
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return false;
        }else{
            $result = $query->row_array();
              $this->db->select('queue_list.id,
                    queue_list.queue_name,
                    queue_list.queue_type,
                    queue_list.queue_last_updated_value,
                    queue_list.queue_updated_datetime,
                    queue_list.marshal_userid,   
                    ci_users.username,
                    ci_users.role                
                    ');
                $this->db->from('queue_list');
                $this->db->join('ci_users','ci_users.assigned_station = queue_list.marshal_station_code');  
                //join('ci_admin_roles','')              
                $this->db->where('queue_list.marshal_station_code',$station_code);
                $this->db->where('ci_users.role',3);
                $res = $this->db->get()->result_array();  
                //echo $this->db->last_query();die;
                return $res;
        }

    }


    public function fetchStationQueues_bkp($userid,$station_code){
        $this->db->select('ci_users.role,ci_users.firstname,ci_users.lastname,ci_users.username,ci_users.email,ci_users.assigned_station,ci_admin_roles.admin_role_id,ci_admin_roles.admin_role_title');
        $this->db->from('ci_users');
        $this->db->join('ci_admin_roles','ci_admin_roles.admin_role_id = ci_users.role');
        $this->db->where('ci_users.id', $userid);        
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return false;
        }
        else{           
            $result = $query->row_array();
            $user_role = $result['role'];            
            $user_role_type = $result['admin_role_title'];            
            if($user_role==3){
                $this->db->select('queue_list.id,
                    queue_list.queue_name,
                    queue_list.queue_type,
                    queue_list.queue_last_updated_value,
                    queue_list.queue_updated_datetime,
                    ci_users.username');
                $this->db->from('queue_list');
                $this->db->join('ci_users','ci_users.id = queue_list.marshal_userid');
                $this->db->where('queue_list.marshal_userid',$userid);
                $res = $this->db->get()->result_array();  
                //echo $this->db->last_query();die;
                return $res;             
            }
            elseif($user_role==2){
                $this->db->select('queue_list.id,
                    queue_list.queue_name,
                    queue_list.queue_type,
                    queue_list.queue_last_updated_value,
                    queue_list.queue_updated_datetime,
                    ci_users.username');
                $this->db->from('queue_list');
                $this->db->join('ci_users','ci_users.assigned_station = queue_list.marshal_station_code');
                $this->db->where('queue_list.marshal_station_code',trim($station_code));
                $res = $this->db->get()->result_array();
                //echo $this->db->last_query();die;
                return $res;
            }           
            return false;
        }
    }


    function insertStationQueueData($insertKey){
        $this->db->insert('queue_details',$insertKey);
        return $this->db->insert_id();
    }


    function fetchStationOperationalinfo($station_code){
        $this->db->select();
        $this->db->from('station_info');
        $this->db->where('station_code',$station_code);
        $query = $this->db->get();
        //echo $this->db->last_query($query);die;
        if ($query->num_rows() == 0){
            return false;
        }else{
            $result = $query->row_array();
            return  $result;           
        }
    }

    function checkStationExists($station_code){
        $this->db->select();
        $this->db->from('station_info');
        $this->db->where('station_code',$station_code);
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return 0;
        }else{
            return 1;            
        }
    }



    



  
  

   

}

/* End of file Project_model.php */
/* Location: ./application/models/api_model.php */