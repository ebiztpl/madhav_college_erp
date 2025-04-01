<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class FeeGroup extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feegroup');

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array(
                'required',
                array('check_exists', array($this->feegroup_model, 'check_exists')),
            )
        );

// custom code by Hritik
$class_id     = $this->input->post('class_id');
$class_id_array = isset($class_id) ? $class_id : array();

//end custom code by Hritik
if(count($class_id_array) == 0){
    $this->form_validation->set_rules('class_id','Course', 'required|trim|xss_clean');

}


        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'courses' => json_encode($this->input->post('class_id')),
            );
            // print_R($data);die;
            $this->feegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feegroup/index');
        }
        $feegroup_result      = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup_result;

        $class             = $this->class_model->get();
        $data['classlist'] = $class;


        $this->load->view('layout/header', $data);
        $this->load->view('admin/feegroup/feegroupList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_delete')) {
            access_denied();
        }
        $this->feegroup_model->remove($id);

        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fees Group Deleted Successfully</div>');

        redirect('admin/feegroup/index');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feegroup');
        $data['id']           = $id;
        $feegroup             = $this->feegroup_model->get($id);
        $data['feegroup']     = $feegroup;
        $feegroup_result      = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup_result;



        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array(
                'required',
                array('check_exists', array($this->feegroup_model, 'check_exists')),
            )
        );
// custom code by Hritik
$class_id     = $this->input->post('class_id');
$class_id_array = isset($class_id) ? $class_id : array();

//end custom code by Hritik
if(count($class_id_array) == 0){
    $this->form_validation->set_rules('class_id','Course', 'required|trim|xss_clean');

}
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feegroup/feegroupEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'courses' => json_encode($this->input->post('class_id')),
            );
            $this->feegroup_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feegroup/index');
        }
    }

}
