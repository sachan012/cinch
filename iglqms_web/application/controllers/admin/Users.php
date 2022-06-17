<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {

	public function __construct(){

		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();
		$this->load->model('admin/user_model', 'user_model');		
		$this->load->model('admin/activity_model', 'activity_model');		
	}

	//-----------------------------------------------------------
	public function index(){
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/user_list');
		$this->load->view('admin/includes/_footer');
	}
	
	public function datatable_json(){				   					   
		$records['data'] = $this->user_model->get_all_users();
		$data = array();

		$i=0;
		foreach ($records['data']   as $row) 
		{  
			$status = ($row['is_active'] == 1)? 'checked': '';
			$verify = ($row['is_verify'] == 1)? 'Verified': 'Pending';
			$data[]= array(
				++$i,
				$row['firstname'],
				$row['lastname'],
				$row['username'],
				$row['email'],
				$row['assigned_station'],
				date_time($row['created_at']),
				
				'<input class="tgl_checkbox tgl-ios" 
				data-id="'.$row['id'].'" 
				id="cb_'.$row['id'].'"
				type="checkbox"  
				'.$status.'><label for="cb_'.$row['id'].'"></label>',		

				'<a title="View" class="view btn btn-sm btn-info" href="'.base_url('admin/users/edit/'.$row['id']).'"> <i class="fa fa-eye"></i></a>
				<a title="Edit" class="update btn btn-sm btn-warning" href="'.base_url('admin/users/edit/'.$row['id']).'"> <i class="fa fa-pencil-square-o"></i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" href='.base_url("admin/users/delete/".$row['id']).' title="Delete" onclick="return confirm(\'Do you want to delete ?\')"> <i class="fa fa-trash-o"></i></a>'
			);
		}
		$records['data']=$data;
		echo json_encode($records);						   
	}

	//-----------------------------------------------------------
	function change_status()
	{   
		$this->user_model->change_status();
	}

	public function add(){		
		$this->rbac->check_operation_access(); // check opration permission
		$arr['stations'] = $this->user_model->getStationList();
		if($this->input->post('submit')){			
			$this->form_validation->set_rules('firstname', 'First Name', 'trim|required',
	        array('required'=> 'You have not provided %s.'));
			$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required',array('required'=> 'You have not provided %s.'));
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[ci_users.email]',array('required'=> 'You have not provided %s.','is_unique'=> 'This %s already exists.'));
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[ci_users.username]',array('required'=> 'You have not provided %s.','is_unique'=> 'This %s already exists.'));	
			$this->form_validation->set_rules('password', 'Password', 'required');			
			$this->form_validation->set_rules('assigned_station', 'Assigned Station', 'trim|required',array('required'=> 'You have not provided %s.'));
			if ($this->form_validation->run() == FALSE) {				
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/users/user_add',$arr);
				$this->load->view('admin/includes/_footer');
			}else{
				$formdata = array(
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'assigned_station' => $this->input->post('assigned_station'),
					'password' =>  md5($this->input->post('password')),
					'password_bin' =>  $this->input->post('password'),
					'created_at' => date('Y-m-d : h:m:s'),
					'updated_at' => date('Y-m-d : h:m:s'),
					'role'=>2,
					'is_verify'=>1
				);
				$formdata = $this->security->xss_clean($formdata);
				$result = $this->user_model->add_user($formdata);
				if($result){					// Activity Log 
					$this->activity_model->add_log(1);
					$this->session->set_flashdata('success', 'Manager Account has been created successfully!');
					redirect(base_url('admin/users'));
				}
			}
		}
		else{
			$data['stations'] = 
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/users/user_add',$arr);
			$this->load->view('admin/includes/_footer');
		}
		
	}

	public function edit($id = 0){

		$this->rbac->check_operation_access(); // check opration permission
		$data['stations'] = $this->user_model->getStationList();

		if($this->input->post('submit')){
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Username', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('assigned_station', 'Assigned Station', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('admin/users/user_edit/'.$id),'refresh');
			}
			else{
				$formdata = array(
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'assigned_station' => $this->input->post('assigned_station'),
					'is_active' => $this->input->post('status'),
					'updated_at' => date('Y-m-d : h:m:s'),
				);
				$formdata = $this->security->xss_clean($formdata);
				$result = $this->user_model->edit_user($formdata, $id);
				if($result){
					// Activity Log 
					$this->activity_model->add_log(2);
					$this->session->set_flashdata('success', 'User has been updated successfully!');
					redirect(base_url('admin/users'));
				}
			}
		}
		else{
			$data['user'] = $this->user_model->get_user_by_id($id);			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/users/user_edit', $data);
			$this->load->view('admin/includes/_footer');
		}
	}

	public function delete($id = 0){
		$this->rbac->check_operation_access(); // check opration permission		
		$this->db->delete('ci_users', array('id' => $id));
		// Activity Log 
		$this->activity_model->add_log(3);
		$this->session->set_flashdata('success', 'Use has been deleted successfully!');
		redirect(base_url('admin/users'));
	}

}


?>