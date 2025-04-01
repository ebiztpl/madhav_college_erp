<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Source extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("source_model");
        $this->config->load('app-config');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('source', $this->lang->line('source'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['source_list'] = $this->source_model->source_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/sourceview', $data);
            $this->load->view('layout/footer');
        } else {

            $source = array(
                'source'      => $this->input->post('source'),
                'description' => $this->input->post('description'),
            );
            $this->source_model->add($source);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/source');
        }
    }

    public function edit($source_id)
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('source', $this->lang->line('source'), 'required');

        if ($this->form_validation->run() == false) {
            $data['source_list'] = $this->source_model->source_list();
            $data['source_data'] = $this->source_model->source_list($source_id);
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/sourceeditview', $data);
            $this->load->view('layout/footer');
        } else {
            $source = array(
                'source'      => $this->input->post('source'),
                'description' => $this->input->post('description'),
            );
            $this->source_model->update($source_id, $source);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/source');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_delete')) {
            access_denied();
        }
        $this->source_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/source');
    }

    
    public function accounts_head()
    {
        
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'Admin/source/accounts_head');
        $data['title']       = 'Add Accounts Head';

      
        $this->db->select()->from('account_type');
        $this->db->order_by('account_type.id', 'desc');
        $query = $this->db->get();
        $data['accounts_type'] = $query->result();


        $this->db->select()->from('accounts_head');
        $this->db->order_by('accounts_head.id', 'desc');
        $query = $this->db->get();
        $data['head_list'] = $query->result();


        $this->form_validation->set_rules('accounts_type', 'Account Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('head_name', 'Head Name', 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $data = array(
                'account_type' => $this->input->post('accounts_type'),
                'head_name' => $this->input->post('head_name'),
                'description'	 => $this->input->post('description'),
                'status' => 1,
            );
            $this->db->insert('accounts_head', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/source/accounts_head');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/accounts_head', $data);
        $this->load->view('layout/footer', $data);
    }


    public function laser()
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'Admin/source/Account_Laser');
        $data['title']       = 'Add Account/Ledger';



        $this->db->select()->from('accounts_head');
        $this->db->order_by('accounts_head.id', 'desc');
        $query = $this->db->get();
        $data['accounts_head'] = $query->result();


        $this->db->select()->from('laser');
        $this->db->order_by('laser.id', 'desc');
        $query = $this->db->get();
        $data['laser'] = $query->result();
        $this->form_validation->set_rules('dr_cr', 'Dr./Cr.', 'trim|required|xss_clean');
        $this->form_validation->set_rules('head_name', 'Head Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('laser_name', 'Ledger Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Opening Balance', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $data = array(
                'account_head' => $this->input->post('head_name'),
                'laser' => $this->input->post('laser_name'),
                'opening_balance'	 => $this->input->post('amount'),
                'balance_at'	 => $this->input->post('date'),
                'description'	 => $this->input->post('description'),
                'dr_cr'	 => $this->input->post('dr_cr'),
            );
            $this->db->insert('laser', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/source/laser');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/laser', $data);
        $this->load->view('layout/footer', $data);

    }

    public function new_module()
    {
    
        // $result = $this->customlib->getLoggedInUserData();
        // $id    = $result["id"];
   
        $role = $this->customlib->getStaffRole();
        $barcnh_i = $this->config->item('branch_id');
        // $role  = json_decode($role)->name;
      
        $id = json_decode($role)->id;

            // do stuff here
            $url =  'https://enrichapp.co.in/eaccount/public/login?gt='.$id.'&br='.$barcnh_i; // this can be set based on whatever
            
            // no redirect
            header( "Location: $url" );

    }



}



