<?php
	
require APPPATH . '/libraries/REST_Controller.php';  //load the rest controller library.
use Restserver\Libraries\REST_Controller;  // without this line it will give the error.

class Test extends REST_Controller {
    
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct(){
        
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) 
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
        
		parent::__construct();      
		   // Load these helper to create JWT tokens
		   $this->load->helper(['jwt', 'authorization']); 
		   $this->load->helper(array('form', 'url'));
		   $this->load->library('form_validation');
		   $this->load->model("Api_model");		   
		   $this->load->helper("basic_helper");
	}


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
	
	public function generate_access_token(){
        //Generate a random string.
        $token = openssl_random_pseudo_bytes(64);
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);
        //Print it out for example purposes.
        return $token;
    }



    function email_lookup_post(){
        try{
         $config = [
                        [
                            'field' => 'first_name',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'First Name is Required'],
                        ],

                        [
                            'field' => 'last_name',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'Last Name is Required'],
                        ],

                         [
                            'field' => 'email',
                            'rules' => 'required|trim|valid_email|is_unique[members.email]',
                            'errors' => [
                                            'required' => 'Email Id is required.',
                                            'is_unique' => 'This %s already exists.'
                                        ],
                        ],
                        [
                            'field' => 'phone',
                            'rules' => 'required|trim|integer',
                            'errors' => ['required' => 'Phone number is required.'],
                        ],

                        [
                            'field' => 'password',
                            'rules' => 'required|trim|min_length[6]',
                            'errors' => ['required' => 'Enter Secure Password.'],
                        ],
                        [
                            'field' => 'confirm_password',
                            'rules' => 'required|trim|matches[password]',
                            'errors' => ['required' => 'Password and ConfirmPassword does not match'],
                        ]                      
                       
                    ];

                $params = $this->post();
                $this->form_validation->set_data($params);
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==FALSE){
                    $first_name_error = $this->form_validation->error('first_name');  
                    // first_name validation
                    if (!empty($first_name_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($first_name_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $last_name_error = $this->form_validation->error('last_name');  
                    // last_name validation
                    if (!empty($last_name_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($last_name_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }
               
                    $email_error = $this->form_validation->error('email');  
                    // email validation
                    if (!empty($email_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($email_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $phone_error = $this->form_validation->error('phone');  
                    // phone validation
                    if (!empty($phone_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($phone_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }               

                    $password_error = $this->form_validation->error('password');  
                    // password validation
                    if (!empty($password_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($password_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $confirm_password_error = $this->form_validation->error('confirm_password');  
                    // confirm_password validation
                    if (!empty($confirm_password_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($confirm_password_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }
                }else
                    {

                        $userarray["first_name"]   =  ucwords($params["first_name"]);
                        $userarray["last_name"]   =  ucwords($params["last_name"]);
                        $userarray["email"]   =  strtolower($params["email"]);
                        $userarray["phone"]   =  trim($params["phone"]); 
                        $userarray["password"]   = md5(trim($params["password"]));
                        $checkEmailExistence = $this->Api_model->email_exists("email", trim($params["email"]));
                        if($checkEmailExistence > 0){
                            $status = parent::HTTP_OK;
                            $this->return["status"]=0;
                            $this->return["message"]="This email id exist already.";
                            $this->response($this->return, $status);
                        }else{ 
                            $status = parent::HTTP_OK;
                            $this->return["status"]=1;
                            $this->return["data"]=$userarray;
                            $this->return["message"]="Email is available";
                            $this->response($this->return, $status);                            
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


    function create_post(){
        try{
         $config = [
                        [
                            'field' => 'first_name',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'First Name is Required'],
                        ],

                        [
                            'field' => 'last_name',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'Last Name is Required'],
                        ],

                        [
                            'field' => 'email',
                            'rules' => 'required|trim|valid_email|is_unique[members.email]',
                            'errors' => [
                                            'required' => 'Email Id is required.',
                                            'is_unique' => 'This %s already exists.'
                                        ],
                        ],

                        [
                            'field' => 'phone',
                            'rules' => 'required|trim|integer',
                            'errors' => ['required' => 'Phone number is required.'],
                        ],

                        [
                            'field' => 'password',
                            'rules' => 'required|trim|min_length[6]',
                            'errors' => ['required' => 'Enter Secure Password.'],
                        ],

                        [
                            'field' => 'confirm_password',
                            'rules' => 'required|trim|matches[password]',
                            'errors' => ['required' => 'Password and ConfirmPassword does not match'],
                        ],

                        [
                            'field' => 'address1',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'Address is required.'],
                        ],

                        [
                            'field' => 'city',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'City Name is required.'],
                        ],

                        [
                            'field' => 'state',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'State Name is required.'],
                        ],

                        [
                            'field' => 'postal_code',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'Postal Code is required.'],
                        ],

                        [
                            'field' => 'gender',
                            'rules' => 'required|trim',
                            'errors' => ['required' => 'Gender is required.'],
                        ]                                             
                       
                    ];

                $params = $this->post();
                $this->form_validation->set_data($params);
                $this->form_validation->set_rules($config);

                if($this->form_validation->run()==FALSE)
                {
                    $first_name_error = $this->form_validation->error('first_name');  
                    // first_name validation
                    if (!empty($first_name_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($first_name_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $last_name_error = $this->form_validation->error('last_name');  
                    // last_name validation
                    if (!empty($last_name_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($last_name_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }
               
                    $email_error = $this->form_validation->error('email');  
                    // email validation
                    if (!empty($email_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($email_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $phone_error = $this->form_validation->error('phone');  
                    // phone validation
                    if (!empty($phone_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($phone_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }               

                    $password_error = $this->form_validation->error('password');  
                    // password validation
                    if (!empty($password_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($password_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $confirm_password_error = $this->form_validation->error('confirm_password');  
                    // confirm_password validation
                    if (!empty($confirm_password_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($confirm_password_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $address1_error = $this->form_validation->error('address1'); 
                    if (!empty($address1_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($address1_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $city_error = $this->form_validation->error('city'); 
                    if (!empty($city_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($city_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $state_error = $this->form_validation->error('state'); 
                    if (!empty($state_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($state_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $postal_code_error = $this->form_validation->error('postal_code'); 
                    if (!empty($postal_code_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($postal_code_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }

                    $gender_error = $this->form_validation->error('gender'); 
                    if (!empty($gender_error)){
                        $status = parent::HTTP_OK;
                        $this->return["msg"]=strip_tags($gender_error);
                        $this->return["status"]=0;
                        $this->response($this->return, $status);
                    }
                }
                else{
                        //echo "hello";die;
                        $userarray["first_name"]   =  ucwords($params["first_name"]);
                        $userarray["last_name"]   =  ucwords($params["last_name"]);
                        $userarray["email"]   =  strtolower($params["email"]);
                        $userarray["phone"]   =  trim($params["phone"]); 
                        $userarray["password"]   = md5(trim($params["password"]));
                        $userarray["address1"]   =  ucwords($params["address1"]);
                        $userarray["address2"]   =  ucwords($params["address2"]);
                        $userarray["city"]   =  ucwords($params["city"]);
                        $userarray["state"]   =  strtolower($params["state"]);
                        $userarray["postal_code"]   =  trim($params["postal_code"]); 
                        $userarray["country"]   =  trim($params["country"]);
                        $userarray["birthday"]   =  trim($params["birthday"]);  
                        $userarray["gender"]   =    trim($params["gender"]);
                        $unencrypted_password = $userarray["email"]; 
                        $hash = password_hash($unencrypted_password, PASSWORD_DEFAULT);                       
                        $userarray["api_key"]   = $hash;  
                        //echo "<pre>";print_r($userarray);die;                     
                        $lastuserid = $this->Api_model->add_user($userarray);
                        if($lastuserid){
                            $getUserDetails['getUserDetails'] = $this->Api_model->get("id", $lastuserid);
                            $to = $getUserDetails['getUserDetails']['email'];                        
                            $subject = 'Registration Confirmation Mail';
                            $msg = $this->load->view('email/registration_mailer',$getUserDetails,true);
                            $this->send_mail($to,$subject,$msg);
                            unset($userarray["status"]);  
                            $status = parent::HTTP_OK;
                            $this->return["status"]=1;
                            $this->return["data"]=$userarray;
                            $this->return["message"]="Thank you for signing up, please check your email to confirm your account";
                            $this->response($this->return, $status);
                        }
                    }
        }catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_OK;
            $this->return["status"]=0;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }  
    }





    public function send_mail($to,$subject,$msg){
        $this->load->library('email');	
		$config = Array(
            'protocol' => 'tls',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 587,
            'smtp_user' => EMAIL,
            'smtp_pass' => PASSWORD,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
         );
		$this->email->initialize($config);		
		$this->email->set_newline("\r\n");
        $this->email->from(EMAIL, 'Oil Changers');		
		$this->email->to($to);		
		$this->email->set_mailtype('html');
		$this->email->subject($subject);
        $this->email->message($msg);
		if(!$this->email->send()){
		    echo "<pre>";print_r($this->email->print_debugger());die;
            return true;
		}else{
			return true;
		}

    }
    






 
	
	


	

    
  

    
	
	
	








    





    
}
