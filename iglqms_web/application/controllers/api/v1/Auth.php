<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Auth extends REST_Controller {    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();         
       $this->load->model("Api_model");     
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */

    private function validate_token(){		
        $access_token = $this->input->get_request_header('authorisation');            
        $this->load->model("Api_model");
        if ($access_token == false) {
            $this->return['error'] = 1;
            $this->return['message'] = ERROR_UNAUTHORIZED_ACCESS;
            $this->response($this->return, REST_Controller::HTTP_UNAUTHORIZED);
        }
        $user = $this->Api_model->check_access_web_app_token($access_token);
        //print_r($user);die;
        if (!$user) {
            $this->return['error'] = ERROR_INVALID_ACCESS_TOKEN;
            $this->return['message'] = ERROR_INVALID_ACCESS_TOKEN_MSG;
            $this->response($this->return, REST_Controller::HTTP_OK);
        }
        $this->user_id = $user;        
        return $this->user_id;
    }
	
	public function generate_access_token()
    {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes(64);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);

        //Print it out for example purposes.
        return $token;
    }


	public function index_get($id = 0)
	{
        if(!empty($id)){
            $data = $this->db->get_where("ci_users", ['id' => $id])->row_array();
        }else{
            $data = $this->db->get("ci_users")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
	}


    /*
    ===========================================================================
    * 01- Api for login with usertype 
    *============================================================================
    */

	public function login_post(){
        try{
            $config =   [
                             [
                                'field' => 'usertype',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Select User Type'
                                            ],
                            ],

                            [
                                'field' => 'email',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Email Or Username is required'
                                            ],
                            ],
                            [
                                'field' => 'password',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'We need valid password'
                                            ],
                            ],
                            
                        ];
            $params = $this->post();
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run()==FALSE)
            {  

                $usertype_error = $this->form_validation->error('usertype');  // username 
                if (!empty($usertype_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($usertype_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status);                    
                } 


                $email_error = $this->form_validation->error('email');  // username validation
                if (!empty($email_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($email_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status); 
                }    
              
                $password_error = $this->form_validation->error('password');  // username validation
                if (!empty($password_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['msg']=strip_tags($password_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status); 
                }                
            }else{
                $usertype = strip_tags($params["usertype"]);  // username for login
                $email = strip_tags($params["email"]);  // email for login
                $password = strip_tags(md5($params["password"]));  // password for login
                $token = $params["device_token"];  // device_token for notification
                $loginResult = $this->Api_model->_login($email,$password,$usertype);
                if(!$loginResult){
                    $status = parent::HTTP_BAD_REQUEST;
                    $this->return["success"] = false;    
                    $this->return["msg"]="Invalid username or password OR usertype selected.";
                    $this->return["status"]=0;
                    $this->response($this->return, $status);
                }else{
                    $status = $loginResult["is_active"];
                    if($status == 0){
                        $status = parent::HTTP_OK;
                        $this->return["success"] = true; 
                        $this->return["msg"]="Your Account is inactive. Plese conatct to adminstrator.";
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }else{
                        $access_token = $this->generate_access_token();
                        $device_token = $params['device_token'];
                        $setDataArr = array(
                            "token"=>$device_token,
                            "access_token"=>$access_token
                        );
                        $this->db->set($setDataArr);
                        $this->db->where("email", $email);
                        $this->db->update("ci_users");                        

                        if($token)
                        {
                            $array = array("token"=>$token);
                            $this->db->where("email", $email);
                            $this->db->update("ci_users", $array);
                        }
                      
                        $loginResponse = $this->Api_model->_login($email,$password,$usertype);
                        unset($loginResponse["device_token"]);
                        $loginResponse["token"]=$token;
                        $status = parent::HTTP_OK;
                        $this->return["msg"]="Login successfully.";
                        $this->return["status"]=1;
                        $this->return["data"]=$loginResponse;
                        $this->response($this->return, $status);
                    }
                }
            }
        }
        catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_OK;
            $this->return["status"]=0;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }           
    }

    /*
    ===========================================================================
    * 02- Api for marshal registration
    *============================================================================
    */

    public function marshalRegistration_post(){
         try {
            $config =   [                           

                            [
                                'field' => 'username',
                                'rules' => 'required|trim|is_unique[ci_users.username]',
                                'errors' => [
                                                'required' => 'username Id is required.',
                                                'is_unique'=> 'This %s already exists.'
                                            ],
                            ],

                            [
                                'field' => 'password',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Password field is required.'
                                            ],
                            ],
                            
                        ];

            $params = $this->post();           
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run()==FALSE){
                $username_error = $this->form_validation->error('username');  // device validation
                if (!empty($username_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;   
                    $this->return["msg"]=strip_tags($username_error);
                    $this->return["status"]=0;
                    $this->response($this->return, $status);
                }

                $password_error = $this->form_validation->error('password');  // username validation
                if (!empty($password_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['msg']=strip_tags($password_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status); 
                }                
            }
            else{               
                $userarray["username"]   =  strtolower($params["username"]);               
                $userarray["password"]   =  md5(trim($params["password"]));                
                $userarray["assigned_station"]   =  trim($params["station_code"]);                
                $userarray["role"]   = 3;
                $response_data =   array(
                    'username'=>strtolower($params["username"]),
                    'password'=>trim($params["password"])
                );   

                $checkEmailExistence = $this->Api_model->column_exists("username", trim($params["username"]));
                if($checkEmailExistence > 0){
                    $status = parent::HTTP_BAD_REQUEST;
                    $this->return["success"] = false;    
                    $this->return["status"]=0;
                    $this->return["msg"]="This username already exist.";
                    $this->response($this->return, $status);
                }else{
                    if($user_id = $this->Api_model->add_user($userarray)){
                        $queue_array = $params["queue"];
                        $i=1;
                        foreach($queue_array as $dt){
                            $insdata['marshal_userid'] = $user_id;
                            $insdata['marshal_station_code'] = $params['station_code'];
                            $insdata['queue_name'] = 'Queue'.$i;
                            $insdata['queue_type'] = $dt['queuetype'];

                            //Station Code No of Queue with Quetype genereated Here
                            $this->db->insert('queue_list',$insdata);
                            $qInsertedId = $this->db->insert_id();

                            //Queue details with default value 0 is created in queue details table for each queue in station
                            $queueDetInsdata['user_id'] =$user_id;
                            $queueDetInsdata['queue_id'] =$qInsertedId;
                            $queueDetInsdata['queue_value'] = 0;
                            $this->db->insert('queue_details',$queueDetInsdata);

                            $i++;
                        }                        
                        $status = parent::HTTP_OK;
                        $this->return["success"] = true;  
                        $this->return["status"]=1;
                        $this->return["data"]=$response_data;
                        $this->return["msg"]="You have successfully registered the Marshal.";
                        $this->response($this->return, $status);
                    }else{
                        $status = parent::HTTP_BAD_REQUEST;                 
                        $this->return["success"] = false;                   
                        $this->return['msg']="Something went wrong"; 
                        $this->return["status"]=0;                  
                        $this->response($this->return, $status);
                    }
                }
            }
        } //try
        catch(Exception $e) 
        { 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_OK;
            $this->return["status"]=0;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }

    } 


}?>