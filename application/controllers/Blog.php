<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->model('m_data_artikel');
	}

	public function index()
	{
		$data['data'] = $this->m_data_artikel->Get_artikel();
		$this->load->view('home_view',$data);
	}

	public function detail()
	{
		$data['data'] = $this->m_data_artikel->Get_single($this->uri->segment(3));
		$this->load->view('home_detail',$data);
	}

	public function add()
	{
		$data['tipe'] = "Add";
		$this->load->model('m_data_artikel');

		$this->load->helper('form');
	    $this->load->library('form_validation');

	    // Kita validasi input sederhana, sila cek http://localhost/ci3/user_guide/libraries/form_validation.html
	    $this->form_validation->set_rules('judul_atk', 'Judul', 'required|is_unique[artikel.judul_atk]',
			array(
				'required' 		=> 'Tolong isi %s ya, pliss.',
				'is_unique' 	=> 'Judul ' .$this->input->post('judul_atk'). ' sudah ada loo.'
			));

		$this->form_validation->set_rules('isi_atk', 'Konten', 'required|min_length[8]',
			array(
				'required' 		=> 'Isi %s dulu gan.',
				'min_length' 	=> 'Isi %s kurang panjang lo gan.',
			));

		$this->form_validation->set_rules('tggl_atk', 'Tanggal', 'required',
			array(
				'required' 		=> 'Isi %s sek to.',
			));

		$this->form_validation->set_rules('sumber_atk', 'Sumbere', 'required',
			array(
				'required' 		=> 'Isi %s dulu looo.',
			));

		if ($this->form_validation->run() === FALSE)
	    {
	        $this->load->view('home_view_form');

	    } else {

			if ($this->input->post('simpan')) {
				$upload = $this->m_data_artikel->upload();

				if ($upload['result'] == 'success') {
					$this->m_data_artikel->save($upload);
					redirect('blog');
				}else{
					$data['message'] = $upload['error'];
				}
			}

			$this->load->view('home_view_form', $data);
		}
	}

	public function edit($id){
		$this->load->model("m_data_artikel");

		$this->load->helper('form');
	    $this->load->library('form_validation');


		$data['default'] = $this->m_data_artikel->get_single($id);

		if(isset($_POST['simpan'])){
			$this->m_data_artikel->update($_POST, $id);
			redirect("blog");
		}

		$this->load->view("home_view_form_edit",$data);
	}


	public function delete($id){
		$this->load->model("m_data_artikel");
		$this->m_data_artikel->hapus($id);
		redirect("blog");
	}

}


  

     