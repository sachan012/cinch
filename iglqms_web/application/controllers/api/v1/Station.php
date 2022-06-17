<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Station extends REST_Controller {    
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


    /*===========================================================================
    *  03-Api for get station detail from user id 
    *============================================================================     
    */

	public function detail_post()
	{
         try {
             $config =   [
                             [
                                'field' => 'userid',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Please Provide userid'
                                            ],
                            ]
                        ];
            $params = $this->post();
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run()==FALSE){
                $userid_error = $this->form_validation->error('userid');  // username 
                if (!empty($userid_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($userid_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status);                    
                }

            }else{
                $userid = strip_tags($params["userid"]);  // userod for get station details
                $station_details = $this->Api_model->getStationDetailByUserId($userid);
                if(!$station_details)
                {
                    $status = parent::HTTP_OK;
                    $this->return["success"] = true;    
                    $this->return["msg"]="No data Found to display.";
                    $this->return["data"]=[];
                    $this->return["status"]=0;
                    $this->response($this->return, $status);
                }else{
                    $status = parent::HTTP_OK;                   
                    $this->return["success"] = true;  
                    $this->return["msg"]="Data found to display.";
                    $this->return["data"]=$station_details;
                    $this->response($this->return, $status);
                }
            }

         } 
         catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_BAD_REQUEST;
            $this->return["status"]=0;
            $this->return["syccess"]=false;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }
	}

    /*
    *===========================================================================
    * 04 - Api for Display Live Data based on userid for marshal and station code for manager 
    *============================================================================     
    */

    function showStationLiveData_post(){
        try{

            $config =  [
                            [
                                'field' => 'userid',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'userid is required'
                                            ],
                            ],

                            [
                                'field' => 'station_code',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Station Code is required'
                                            ],
                            ],


                        ];
            $params = $this->post();
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run()==FALSE){
                $userid_error = $this->form_validation->error('userid');  // username 
                if (!empty($userid_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($userid_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status);                    
                }

                $stationcode_error = $this->form_validation->error('station_code');  // username 
                if (!empty($stationcode_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($stationcode_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status);                    
                }
            }else{
                // userid for get user details
                $userid = strip_tags($params["userid"]);
                // station code for get station details
                $station_code = strip_tags($params["station_code"]); 
                //get no queues based on userid and stationcode
                $livedata = $this->Api_model->fetchStationQueues($userid,$station_code);
                //echo "<pre>";print_r($livedata);die;
                if(!$livedata){
                    $status = parent::HTTP_OK;
                    $this->return["success"] = true;
                    $this->return["status"]=1;    
                    $this->return["msg"]="No data Found to display.";
                    $this->return["data"]=[];                   
                    $this->response($this->return, $status);
                }else{
                    $status = parent::HTTP_OK;                   
                    $this->return["success"] = true;  
                    $this->return["status"]=1;                        
                    $this->return["msg"]="Data found to display.";
                    $this->return["data"]=$livedata;
                    $this->response($this->return, $status);
                }
            }
        }
        catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_BAD_REQUEST;
            $this->return["status"]=0;
            $this->return["syccess"]=false;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }
    }   

    /*
    ===========================================================================
    * 05- Api for update Live Data based on userid for marshal 
    * update queue length or live data
    * API to insert queue data by Marshal
    *============================================================================
    */

    public function stationQueueDataSubmit_post(){
       try {
            $config =  [
                            [
                                'field' => 'userid',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'userid is required'
                                            ],
                            ]
                        ];
            $params = $this->post();
            $form_data = $params['form_data'];
            //echo "<pre>";print_r($form_data);die;
            $this->form_validation->set_data($params);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run()==FALSE){
                $userid_error = $this->form_validation->error('userid');  // username 
                if (!empty($userid_error)) 
                {
                    $status = parent::HTTP_BAD_REQUEST;                 
                    $this->return["success"] = false;                   
                    $this->return['error']=strip_tags($userid_error); 
                    $this->return["status"]=0;                  
                    $this->response($this->return, $status);                    
                }
            }else{

                // userid for get user details
                $userid = strip_tags($params["userid"]);
                $insertIdArr = array();
                foreach($form_data as $form){
                    $insertKey['user_id'] = $userid;
                    $insertKey['queue_id'] = $form['queue_id'];
                    $insertKey['queue_value'] = $form['queue_value'];                   
                    $insertedId = $this->Api_model->insertStationQueueData($insertKey);
                    if(!$insertedId){
                        $status = parent::HTTP_BAD_REQUEST;                 
                        $this->return["success"] = false;                   
                        $this->return['error']="unable to submit data."; 
                        $this->return["status"]=0;                  
                        $this->response($this->return, $status);
                    }
                    $insertIdArr[] = $insertedId;

                    //update last_update_value in queue list table
                    $set_table_data = array(
                        'queue_last_updated_value'=>$form['queue_value'],
                        'queue_updated_datetime'=>date('Y-m-d H:i:s')
                    ); 
                    $wherecondition = array(
                        'id'=>$form['queue_id'],
                        'marshal_userid'=>$userid
                    );

                    $this->db->set($set_table_data);
                    $this->db->where($wherecondition);
                    $this->db->update('queue_list');
                }

                $status = parent::HTTP_OK;                   
                $this->return["success"] = true;
                $this->return["status"] = 1;                  
                $this->return["msg"]="Data inserted Successfully.";
                $this->return["data"]=json_encode($insertIdArr);
                $this->response($this->return, $status);                
            }

        } 
        catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_BAD_REQUEST;
            $this->return["status"]=0;
            $this->return["syccess"]=false;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }

    } 

    /*
    ===========================================================================
    * 06- Api for get station information like arms and queues
    *============================================================================
    */ 

    function getStationOperationalInformation_post(){
        try{
            $config =  [

                        [
                            'field' => 'station_code',
                            'rules' => 'required|trim',
                            'errors' => [
                                            'required' => 'Station Code is required'
                                        ],
                        ],


                    ];

            $params = $this->post();
            //echo "<pre>";print_r($params);die;
            $station_code = $params['station_code'];
            $station_data = $this->Api_model->fetchStationOperationalinfo($station_code);
            if(!$station_data){
                $status = parent::HTTP_OK;
                $this->return["success"] = true;
                $this->return["status"]=1;    
                $this->return["msg"]="No data Found to display.";
                $this->return["data"]=[];                   
                $this->response($this->return, $status);
            }else{
                $status = parent::HTTP_OK;                   
                $this->return["success"] = true;  
                $this->return["status"]=1;                        
                $this->return["msg"]="Data found to display.";
                $this->return["data"]=$station_data;
                $this->response($this->return, $status);
            }

        }catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_BAD_REQUEST;
            $this->return["status"]=0;
            $this->return["syccess"]=false;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }
    }


    /*
    ===========================================================================
    * 07 - Api for submit station information like arms and queues
    *============================================================================
    */ 

    function submitStationOperationalInformation_post(){
        try{
            $config =  [

                            [
                                'field' => 'station_code',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'Station Code is required'
                                            ],
                            ],
                            [
                                'field' => 'userid',
                                'rules' => 'required|trim',
                                'errors' => [
                                                'required' => 'userid is required'
                                            ],
                            ]


                    ];

            $params = $this->post();
            //echo "<pre>";print_r($params);die;
            $station_code = $params['station_code'];
            $postdata['auto_arms'] = $params['form_data']['auto_arms'];
            $postdata['auto_queues'] = $params['form_data']['auto_queues'];
            $postdata['bus_arms'] = $params['form_data']['bus_arms'];
            $postdata['bus_queues'] = $params['form_data']['bus_queues'];
            $postdata['non_bus_arms'] = $params['form_data']['non_bus_arms'];
            $postdata['non_bus_queues'] = $params['form_data']['non_bus_queues'];
            $postdata['bus_filling_time'] = $params['form_data']['bus_filling_time'];
            $postdata['non_bus_filling_time'] = $params['form_data']['non_bus_filling_time'];
            $postdata['num_of_marshals'] = $params['form_data']['num_of_marshals'];           
            $postdata['updated_on'] = date('Y-m-d H:i:s');           
           
            $isSationDataAvailable = $this->Api_model->checkStationExists($station_code);
            //echo $isSationDataAvailable;die;
            //echo $station_code ;die;
            if($isSationDataAvailable==0){
                $postdata['station_code'] = $station_code;
                $this->db->insert('station_info',$postdata);               
            }else{
                $this->db->set($postdata)->where('station_code',$station_code)->update('station_info');                
            }
            //$station_data = $this->Api_model->fetchStationOperationalinfo($station_code);
            $status = parent::HTTP_OK;                   
            $this->return["success"] = true;  
            $this->return["status"]=1;                        
            $this->return["msg"]="Data updated Successfully.";
            //$this->return["data"]=$station_data;
            $this->response($this->return, $status);            

        }catch(Exception $e){ 
            log_message('error', "\n Exception Caught", $e->getMessage());
            $status = parent::HTTP_BAD_REQUEST;
            $this->return["status"]=0;
            $this->return["syccess"]=false;
            $this->return["msg"]= $e->getMessage();
            $this->response($this->return, $status);
        }
    }





   


    
    
    	
}?>