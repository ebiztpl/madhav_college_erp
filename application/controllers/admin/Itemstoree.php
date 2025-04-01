<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemstoree extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');

        $this->load->helper('url');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('store', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'itemstoree/index');
        $data['title']         = 'Item Store List';
        $itemstore_result      = $this->itemstoree_model->get();
        $data['itemstorelist'] = $itemstore_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/itemstoree/itemstoreList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('store', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Item Store List';
        $this->itemstoree_model->remove($id);
        redirect('admin/itemstoree/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('store', 'can_add')) {
            access_denied();
        }
        $data['title']         = 'Add Item store';
        $itemstore_result      = $this->itemstoree_model->get();
        $data['itemstorelist'] = $itemstore_result;

        $this->form_validation->set_rules('name', $this->lang->line('item_store_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/itemstoree/itemstoreList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'item_store'  => $this->input->post('name'),
                'code'        => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->itemstoree_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/itemstoree/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('store', 'can_edit')) {
            access_denied();
        }

        $data['title']         = 'Edit Item Store';
        $itemstore_result      = $this->itemstoree_model->get();
        $data['itemstorelist'] = $itemstore_result;
        $data['id']            = $id;
        $store                 = $this->itemstoree_model->get($id);
        $data['itemstore']     = $store;

        $this->form_validation->set_rules('name', $this->lang->line('item_store_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/itemstoree/itemstoreEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'item_store'  => $this->input->post('name'),
                'code'        => $this->input->post('code'),
                'description' => $this->input->post('description'),
            );
            $this->itemstoree_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/itemstoree/index');
        }
    }

}
