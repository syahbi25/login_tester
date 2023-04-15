<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Login';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			//cek aktifasi
			if ($user['is_active'] == 1) {
				//cek password
				if (password_verify($password, $user['password'])) {
					$data = [
						'email' => $user['email'],
						'role_id' => $user['role_id'],
					];
					$this->session->set_userdata($data);
					if ($user['role_id'] == 1) {
						redirect('admin');
					} else {
						redirect('user');
					}
				} else {
					$this->session->set_flashdata('message', '
				<div class="alert alert-danger" role="alert">Wrong password!</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '
			<div class="alert alert-warning" role="alert">Email has not activated!</div>');
				redirect('auth');
			}
		} else {

			$this->session->set_flashdata('message', '
			<div class="alert alert-danger" role="alert">Email is not registered!</div>');
			redirect('auth');
		}
	}

	public function registration()
	{

		if ($this->session->userdata('email')) {
			redirect('user');
		}

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'This email already registered!'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
			'matches' => 'Password dont match!',
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/register');
			$this->load->view('templates/auth_footer');
		} else {
			$email = $this->input->post('email', true);
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($email),
				'image' => 'default.svg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 0,
				'date_created' => time()
			];
			$random = $this->security->get_random_bytes(64);
			$token = base64_encode($random);
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_created' => time()
			];

			$this->db->insert('user', $data);
			$this->db->insert('user_token', $user_token);

			$this->_sendEmail($token, 'verify');
			$this->session->set_flashdata('message', '
			<div class="alert alert-success" role="alert">Congratulation! Your account has been created. Please activated your account</div>');
			redirect('auth');
		}
	}

	private function _sendEmail($token, $type)
	{
		$this->load->library('email');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.gmass.co.';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'gmass';
		$config['smtp_pass'] = 'c7b63c80-cb6d-4258-846b-2975e5ecaf66';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('webtester922@yahoo.com', 'Web Tester Login');
		$this->email->to($this->input->post('email'));

		if ($type == 'verify') {
			$this->email->subject('Account Verification');
			$this->email->message('Click this link to verify your account : <a href = "' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
		} elseif ($type == 'forgat') {
			$this->email->subject('Reset Password');
			$this->email->message('Click this link to Reset Password your account : <a href = "' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset password</a>');
		}

		if ($this->email->send()) {
			return true;
		} else {
			echo $this->email->print_debugger();
			die;
		}
	}

	public function verify()
	{
		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');

					$this->db->delete('user_token', ['email' => $email]);


					$this->session->set_flashdata('message', '
				<div class="alert alert-success" role="alert">' . $email . ' has been activated</div>');
					redirect('auth');
				} else {
					$this->db->delete('user', ['email' => $email]);
					$this->db->delete('user_token', ['email' => $email]);

					$this->session->set_flashdata('message', '
				<div class="alert alert-danger" role="alert">Token Expired</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '
			<div class="alert alert-danger" role="alert">Token invalid</div>');
				redirect('auth');
			}
		} else {

			$this->session->set_flashdata('message', '
		<div class="alert alert-danger" role="alert">Account activation failed!, wrong email.</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '
		<div class="alert alert-success" role="alert">Congratulation! Your account has been logged out</div>');
		redirect('auth');
	}

	public function blocked()
	{
		$data['title'] = 'Access blocked';
		$this->load->view('templates/header', $data);
		$this->load->view('auth/blocked');
		$this->load->view('templates/footer');
	}

	public function forgatpassword()
	{
		if ($this->session->userdata('email')) {
			redirect('user');
		}

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Forgat Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/forgatpassword');
			$this->load->view('templates/auth_footer');
		} else {


			$email = $this->input->post('email');
			$user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

			if ($user) {

				$random = $this->security->get_random_bytes(64);
				$token = base64_encode($random);
				$user_token = [
					'email' => $email,
					'token' => $token,
					'date_created' => time()
				];

				$this->db->insert('user_token', $user_token);
				$this->_sendEmail($token, 'forgat');

				$this->session->set_flashdata('message', '
				<div class="alert alert-success" role="alert">Please check your email to reset your password</div>');
				redirect('auth/forgatpassword');
			} else {
				$this->session->set_flashdata('message', '
				<div class="alert alert-danger" role="alert">Email is not registered or activated</div>');
				redirect('auth/forgatpassword');
			}
		}
	}

	public function resetpassword()
	{

		$email = $this->input->get('email');
		$token = $this->input->get('token');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();

		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				$this->session->set_userdata('reset_email', $email);
				if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					$this->changePassword();
				} else {
					$this->session->set_flashdata('message', '
				<div class="alert alert-danger" role="alert">Token Expired</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '
			<div class="alert alert-danger" role="alert">Reset password failed</div>');
				redirect('auth');
			}
		} else {

			$this->session->set_flashdata('message', '
		<div class="alert alert-danger" role="alert">Account activation failed!, wrong email.</div>');
			redirect('auth');
		}
	}

	public function changePassword()
	{
		if (!$this->session->userdata('reset_email')) {
			redirect('auth');
		}

		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
			'matches' => 'Password dont match!',
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'Change Password';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/changepassword');
			$this->load->view('templates/auth_footer');
		} else {
			$password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
			$email = $this->session->userdata('reset_email');

			$this->db->set('password', $password);
			$this->db->where('email', $email);
			$this->db->update('user');

			$this->session->unset_userdata('reset_email');


			$this->session->set_flashdata('message', '
		<div class="alert alert-success" role="alert">Password has been change, please login!</div>');
			redirect('auth');
		}
	}
}
