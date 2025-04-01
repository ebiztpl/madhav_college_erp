<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Incomehead extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Income');
        $this->session->set_userdata('sub_menu', 'incomeshead/index');
        $data['title']        = 'Income Head List';
        $category_result      = $this->incomehead_model->get();
        $data['categorylist'] = $category_result;

        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;

        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/incomehead/incomeheadList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('income_head_list');
        $category         = $this->incomehead_model->get($id);
        $data['category'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/incomehead/incomeheadShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_delete')) {
            access_denied();
        }
        $this->incomehead_model->remove($id);
        redirect('admin/incomehead/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_add')) {
            access_denied();
        }
        $data['title']        = 'Add Income Head';
        $category_result      = $this->incomehead_model->get();
        $data['categorylist'] = $category_result;


        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;



        $this->form_validation->set_rules('inc_head_id', 'Income Head Category', 'trim|required|xss_clean');

        $this->form_validation->set_rules('incomehead', $this->lang->line('income_head'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'head_category' => $this->input->post('inc_head_id'),
                'income_category' => $this->input->post('incomehead'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/incomehead/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit Income Head';
        $category_result      = $this->incomehead_model->get();
        $data['categorylist'] = $category_result;
        $data['id']           = $id;
        $category             = $this->incomehead_model->get($id);



        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;



        $data['incomehead']   = $category;
        $this->form_validation->set_rules('inc_head_id', 'Income Head Category', 'trim|required|xss_clean');

        $this->form_validation->set_rules('incomehead', $this->lang->line('income_head'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'              => $id,
                'head_category' => $this->input->post('inc_head_id'),
                'income_category' => $this->input->post('incomehead'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/incomehead/index');
        }
    }

    
   public function incomeheadcategory()
    {

        if (!$this->rbac->hasPrivilege('income_head', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Income');
        $this->session->set_userdata('sub_menu', 'incomeshead/incomeheadcategory');
        $data['title']        = 'Income Head Category List';
        $category_result      = $this->incomehead_model->getcategory();
        $data['categorylist'] = $category_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/incomehead/incomeheadcategoryList', $data);
        $this->load->view('layout/footer', $data);

    }

    
    public function headcategorycreate()
    {
      
        if (!$this->rbac->hasPrivilege('income_head', 'can_add')) {
            access_denied();
        }
        $data['title']        = 'Add Income Head category';
        $category_result      = $this->incomehead_model->getcategory();
        $data['categorylist'] = $category_result;
        $this->form_validation->set_rules('incomehead', 'Income Head Category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadcategoryList', $data);
            $this->load->view('layout/footer', $data);
        } else {
       
            $data = array(
                'title' => $this->input->post('incomehead'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->addcategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/incomehead/incomeheadcategory');
        }


    }

    public function deletecategory($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_delete')) {
            access_denied();
        }
        $this->incomehead_model->removecategory($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');

        redirect('admin/incomehead/incomeheadcategory');
    }


    public function editcategory($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit Income Head';
        $category_result      = $this->incomehead_model->getcategory();
        $data['categorylist'] = $category_result;
        $data['id']           = $id;
        $category             = $this->incomehead_model->getcategory($id);
        $data['incomehead']   = $category;
        $this->form_validation->set_rules('incomehead', 'Income Head Category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadcategoryEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'              => $id,
                'title' => $this->input->post('incomehead'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->addcategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/incomehead/incomeheadcategory');
        }
    }


}
