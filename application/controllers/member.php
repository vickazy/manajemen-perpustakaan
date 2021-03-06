<?php


class Member extends MY_Controller {

	function __construct()
	{
		parent::__construct('anggota');
		$this->load->model('member_model');
	}
	
	function index() 
	{
		$this->data['list_members'] = $this->member_model->get_records();
		$this->data['main'] = 'member/index';
		$this->load->view($this->template, $this->data);
	}
	
	function new_form() 
	{
		$this->data['main'] = 'member/form';
		$this->load->view($this->template, $this->data);
	}
	
	function form_edit($member_id) 
	{
		$this->data['member'] = $this->member_model->find_one($member_id);
		$this->data['main'] = 'member/form';
		$this->load->view($this->template, $this->data);
	}
	
	function form_view($member_id)
	{
		$member_id = $this->input->get('member_id');
		$this->data['feedback_msg'] = $this->session->flashdata('feedback_msg');
		$this->data['member'] = $this->member_model->get_record($member_id);
		$this->data['main'] = 'anggota/form';
		$this->load->view($this->template, $this->data);
	}
	
	function save()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run() == TRUE) {
			
		}
		
		$this->data['main'] = 'member/form';
		$this->load->view($this->template, $this->data);
	}

	
	function save_ajax()
	{
		$data = array(
						'NO_ANGGOTA' => $this->input->post('no_anggota'),
						'TYPE_ANGGOTA' => 1,
						'NAMA_ANGGOTA' => $this->input->post('nama_anggota'),
						'ALAMAT_RUMAH' => $this->input->post('alamat_rumah'),
						'NO_TLP' => $this->input->post('no_tlp'),
						'KELAS' => $this->input->post('kelas'),
						'JENIS_KELAMIN' => $this->input->post('jenis_kelamin'),
						'STATUS_PINJAMAN' => 0,
						'STATUS_ANGGOTA' => 1
					);

		$this->db->insert("mst_anggota", $data);
	}
	
	function update_member()
	{
		$user_id = $this->input->post('no_anggota');
		$data = array(
			'NAMA_ANGGOTA' => $this->input->post('nama_anggota'),
			'ALAMAT_RUMAH' => $this->input->post('alamat'),
			'NO_TLP' => $this->input->post('no_tlp'),
			'KELAS' => $this->input->post('kelas'),
		);
		$this->db->where('NO_ANGGOTA', $user_id);
		$this->db->update("mst_anggota", $data);
	}
	
	function edit_ajax($anggota_id)
	{
		$anggota = $this->member_model->get_record($anggota_id);
		
		$data_anggota['no_anggota'] = $anggota->NO_ANGGOTA;
		$data_anggota['nama_anggota'] = $anggota->NAMA_ANGGOTA;
		$data_anggota['alamat'] = $anggota->ALAMAT_RUMAH;
		$data_anggota['no_tlp'] = $anggota->NO_TLP;
		$data_anggota['kelas'] = $anggota->KELAS;
		
		echo json_encode($data_anggota);
	}
	
	function find()
	{
		$this->form_validation->set_rules('keyword','Kata Pencarian','required');
		
		if ($this->form_validation->run())
		{
			$keyword = $this->input->post('keyword');
			$criteria = array('NO_ANGGOTA' => $keyword);
			parent::find($criteria);
		}
		else
			parent::index();
	}
	
	function remove($id)
	{
		$member = $this->member_model->get_by(array('ID' => $id))->row();
		
		if ($member->STATUS_PINJAMAN == TRUE)
		{
			$this->session->set_flashdata('notice', 'Data tidak dapat dihapus, anggota '.$member->NO_ANGGOTA.' masih mempunyai pinjaman');
			redirect('anggota');
		}
		else
		{
			parent::delete($id);
		}
	}
	
	function newMember()
	{
		$this->load->view('anggota/form');
	}
}