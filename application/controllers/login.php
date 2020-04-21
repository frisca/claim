<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('all_model');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function register(){
		$this->load->view('register');
	}

	public function processRegister(){
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() == false){
			$this->session->set_flashdata('error', 'Maaf..data yang Anda isi belum lengkap');
			redirect(base_url() . 'login/register');
		}else{
			$data = array(
				'nama' => $this->input->post('nama'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'email' => $this->input->post('email'),
				'role' => 2
			);
			$res = $this->all_model->insertData('user', $data);
			if($res == false){
				$this->session->set_flashdata('error', 'Data Anda gagal terdaftar');
				redirect(base_url() . 'login/register');
			}

			$this->session->set_flashdata('success', 'Data Anda berhasil terdaftar');
			redirect(base_url() . 'login/index');
		}
	}

	public function processLogin(){
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		$condition = array(
			'username'  => $this->input->post('username'),
			'password'  => md5($this->input->post('password'))
		);

		if($this->form_validation->run() == false){
			$this->load->view('login');
		}else{
			$cek = $this->all_model->getDataByCondition("user", $condition)->num_rows();
			if($cek  <= 0){
				$this->session->set_flashdata('error', 'Username dan password invalid');
				redirect(base_url() . 'login/index');
			}

			$res = $this->all_model->getDataByCondition("user", $condition)->row();
			$data_session = array(
				'username'  => $res->username,
				'id'		=> $res->id_user,
				'logged_in' => 1,
				'role'		=> (int)$res->role,
				'nama'		=> $res->nama,
				'email'		=> $res->email
			);

			$this->session->set_userdata($data_session);
			redirect(base_url() . 'home/index');
		}
	}


	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */