<?php

class Schoolhouse extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model("schoolhouse_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/schoolhouse');
        $data['title']       = $this->lang->line('add_school_house');
        $data["house_name"]  = "";
        $data["scheme_fees"] = "";
        $data["description"] = "";
        $houselist           = $this->schoolhouse_model->get();
        $data["houselist"]   = $houselist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/schoolhouse/houselist', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_add')) {
            access_denied();
        }
        $data['title']       = $this->lang->line('add_school_house');
        $houselist           = $this->schoolhouse_model->get();
        $data["houselist"]   = $houselist;
        $data["house_name"]  = "";
        $data["scheme_fees"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules('house_name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/schoolhouse/houselist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'house_name'  => $this->input->post('house_name'),
                'scheme_fees'  => $this->input->post('scheme_fees'),
                'is_active'   => 'yes',
                'description' => $this->input->post('description'),
            );
            $this->schoolhouse_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/schoolhouse/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_edit')) {
            access_denied();
        }
        $data['title']       = $this->lang->line('edit_school_house');
        $houselist           = $this->schoolhouse_model->get();
        $data["houselist"]   = $houselist;
        $data['id']          = $id;
        $house               = $this->schoolhouse_model->get($id);
        $data["house"]       = $house;
        $data["house_name"]  = $house["house_name"];
        $data["scheme_fees"]  = $house["scheme_fees"];
        $data["description"] = $house["description"];
        $this->form_validation->set_rules('house_name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/schoolhouse/houselist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'house_name'  => $this->input->post('house_name'),
                'scheme_fees'  => $this->input->post('scheme_fees'),
                'is_active'   => 'yes',
                'description' => $this->input->post('description'),
            );
            $this->schoolhouse_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/schoolhouse');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_houses', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->schoolhouse_model->delete($id);
            $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        }
        redirect('admin/schoolhouse/');
    }


    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']              = $id;
        $data['title']           = $this->lang->line('student_fees');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        // $feegroup_result         = $this->feesessiongroup_model->getFeesByGroup($id);
        // $data['feegroupList']    = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;
        $RTEstatusList         = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $category             = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender']      = $this->input->post('gender');
            $data['rte_status']  = $this->input->post('rte');
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');

            $resultlist         = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
    
        $this->load->view('admin/schoolhouse/assign', $data);
        $this->load->view('layout/footer', $data);
    }
    
    
    public function multipleassign()
    {


        $student_session_id     = $this->input->post('student_session_id');
        $scheme     = $this->input->post('scheme');
        $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
        $student_ids            = $this->input->post('student_ids');
        $delete_student         = array_diff($student_ids, $student_sesssion_array);
// echo $scheme; 
// echo "<pre>";
// print_r($student_sesssion_array);
// echo "<pre>";
// print_r($delete_student);die;
        $preserve_record = array();
        if (!empty($student_sesssion_array)) {
            foreach ($student_sesssion_array as $key => $value) {
                
                $appointment = array('school_house_id' => $scheme);    
                $this->db->where('id', $value);
                $this->db->update('students', $appointment); 
            }
        }
        if (!empty($delete_student)) {


            foreach ($delete_student as $key => $valu) {
                
                $appointment = array('school_house_id' => 0);    
                $this->db->where('id', $valu);
                $this->db->update('students', $appointment); 
            }



        }

        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    
}




}
