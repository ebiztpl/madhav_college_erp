<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentfee extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('customlib');
        $this->load->library('media_storage');
        $this->load->model("module_model");
        $this->load->model("transportfee_model");
        $this->search_type        = $this->config->item('search_type');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->account = $this->load->database('account', true);
        $this->config->load('app-config');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function pdf()
    {
        $this->load->helper('pdf_helper');
    }

    public function search()
    {
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        } elseif ($search_type == "keyword_search") {
            $this->form_validation->set_rules('search_text', $this->lang->line('keyword'), 'required|trim|xss_clean');
            $data = array('search_text' => 'dummy');
            $this->form_validation->set_data($data);
        }
        if ($this->form_validation->run() == false) {
            $error = array();
            if ($search_type == "class_search") {
                $error['class_id'] = form_error('class_id');
            } elseif ($search_type == "keyword_search") {
                $error['search_text'] = form_error('search_text');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $search_text = $this->input->post('search_text');
            $class_id    = $this->input->post('class_id');
            $section_id  = $this->input->post('section_id');
            $params      = array('class_id' => $class_id, 'section_id' => $section_id, 'search_type' => $search_type, 'search_text' => $search_text);
            $array       = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    // public function ajaxSearch()
    // {
    //     $class       = $this->input->post('class_id');
    //     $section     = $this->input->post('section_id');
    //     $search_text = $this->input->post('search_text');
    //     $search_type = $this->input->post('search_type');
    //     if ($search_type == "class_search") {
    //         $students = $this->student_model->getDatatableByClassSection($class, $section);
    //     } elseif ($search_type == "keyword_search") {
    //         $students = $this->student_model->getDatatableByFullTextSearch($search_text);
    //     }
    //     $sch_setting = $this->sch_setting_detail;
    //     $students    = json_decode($students);
    //     $dt_data     = array();
    //     if (!empty($students->data)) {
    //         foreach ($students->data as $student_key => $student) {
    //             $row         = array();
    //             $row[]       = $student->class;
    //             $row[]       = $student->section;
    //             $row[]       = $student->admission_no;
    //             $row[]       = "<a href='" . base_url() . "student/view/" . $student->id . "'>" . $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
    //             $sch_setting = $this->sch_setting_detail;
    //             if ($sch_setting->father_name) {
    //                 $row[] = $student->father_name;
    //             }
    //             $row[] = $this->customlib->dateformat($student->dob);
    //             $row[] = $student->mobileno;
    //             $row[] = "<a href=" . site_url('studentfee/addfee/' . $student->student_session_id) . "  class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

    //             $dt_data[] = $row;
    //         }
    //     }
    //     $json_data = array(
    //         "draw"            => intval($students->draw),
    //         "recordsTotal"    => intval($students->recordsTotal),
    //         "recordsFiltered" => intval($students->recordsFiltered),
    //         "data"            => $dt_data,
    //     );
    //     echo json_encode($json_data);
    // }


    public function ajaxSearch()
    {
        $class       = $this->input->post('class_id');
        $section     = $this->input->post('section_id');
        $search_text = $this->input->post('search_text');
        $search_type = $this->input->post('search_type');
        if ($search_type == "class_search") {
            $students = $this->student_model->getDatatableByClassSection($class, $section);
        } elseif ($search_type == "keyword_search") {
            $students = $this->student_model->getDatatableByFullTextSearch($search_text);
        }
        $sch_setting = $this->sch_setting_detail;
        $students    = json_decode($students);
      

      

        $dt_data     = array();
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $studentt) {
                $row         = array();
                $row[]       = $studentt->class;
                $row[]       = $studentt->section;
                $row[]       = $studentt->admission_no;
                $row[]       = "<a href='" . base_url() . "student/view/" . $studentt->id . "'>" . $this->customlib->getFullName($studentt->firstname, $studentt->middlename, $studentt->lastname, $sch_setting->middlename, $sch_setting->lastname) . "</a>";
                $sch_setting = $this->sch_setting_detail;
                if ($sch_setting->father_name) {
                    $row[] = $studentt->father_name;
                }
                $row[] = $this->customlib->dateformat($studentt->dob);
                $row[] = $studentt->mobileno;


           $ab =     $studentt->student_session_id; 
//Custom Code by hritk
$student               = $this->student_model->getByStudentSession($studentt->id);
$data['sch_setting']   = $this->sch_setting_detail;
$data['title']         = 'Student Detail';
$student               = $this->student_model->getByStudentSession($studentt->id);
$route_pickup_point_id = $student['route_pickup_point_id']??'';
$student_session_id    = $student['student_session_id']??'';
$transport_fees = [];

$module = $this->module_model->getPermissionByModulename('transport');
if ($module['is_active']) {

    $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
}
$data['student']       = $student;
// $student_due_fee       = $this->studentfeemaster_model->getStudentFees($studentt->id);
$student_due_fee       = $this->studentfeemaster_model->getStudentFees($studentt->student_session_id);
// echo "<pre>";
// print_r($student_due_fee);die;


$student_discount_fee  = $this->feediscount_model->getStudentFeesDiscount($studentt->id);
$data['transport_fees']         = $transport_fees;
$data['student_discount_fee']   = $student_discount_fee;
$data['student_due_fee']        = $student_due_fee;
$category                       = $this->category_model->get();
$data['categorylist']           = $category;
$class_section                  = $this->student_model->getClassSection($student["class_id"]??'');
$data["class_section"]          = $class_section;
$session                        = $this->setting_model->getCurrentSession();
$studentlistbysection           = $this->student_model->getStudentClassSection($student["class_id"]??'', $session);
$data["studentlistbysection"]   = $studentlistbysection;
$student_processing_fee         = $this->studentfeemaster_model->getStudentProcessingFees($studentt->id);
$data['student_processing_fee'] = false;
foreach ($student_processing_fee as $key => $processing_value) {
    if (!empty($processing_value->fees)) {
        $data['student_processing_fee'] = true;
    }
}
$total_amount           = 0;
$total_deposite_amount  = 0;
$total_fine_amount      = 0;
$total_fees_fine_amount = 0;
$total_discount_amount = 0;
$total_balance_amount  = 0;
$alot_fee_discount     = 0;
foreach ($student_due_fee as $key => $fee) {

    foreach ($fee->fees as $fee_key => $fee_value) {


        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;
        if (!empty($fee_value->amount_detail)) {
            $fee_deposits = json_decode(($fee_value->amount_detail));

            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
            }
        }
        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
            $fees_fine_amount       = $fee_value->fine_amount;
            $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
        }

        $total_amount += $fee_value->amount;
        $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
        $total_fine_amount += $fee_fine;
        $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
        $total_balance_amount += $feetype_balance;
    }}
   
if (!empty($transport_fees)) {
    foreach ($transport_fees as $transport_fee_key => $transport_fee_value) {

        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;

        if (!empty($transport_fee_value->amount_detail)) {
            $fee_deposits = json_decode(($transport_fee_value->amount_detail));
            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
            }
        }

        $feetype_balance = $transport_fee_value->fees - ($fee_paid + $fee_discount);

        if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
            $fees_fine_amount       = is_null($transport_fee_value->fine_percentage) ? $transport_fee_value->fine_amount : percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
            $total_fees_fine_amount = $total_fees_fine_amount + $fees_fine_amount;
        }

        $total_amount += $transport_fee_value->fees;
        $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
        $total_fine_amount += $fee_fine;
        $total_balance_amount += $feetype_balance;
    }}

  $dayta =  $total_balance_amount;
// Custom Code by hritk
if(($dayta > 0) && ($dayta != 0) && ($dayta == $total_amount)){
    $row[] = "<a href=" . site_url('studentfee/addfee/' . $ab) . "  class='btn btn-info btn-xs'>" .  $this->lang->line('collect_fees') . "</a>";

}else{
    
    if ($dayta == 0 && ($total_amount != 0)) {
     
        
        $row[] =  "<a href=" . site_url('studentfee/addfee/' . $ab) . "  class='btn btn-success btn-xs'>".$this->lang->line('paid') .'</span>';
        
       
}elseif(!empty($fee_value->amount_detail) || ($total_amount??0 != 0)) {
    
       $row[] =    "<a href=" . site_url('studentfee/addfee/' . $ab) . "  class='btn btn-warning btn-xs'>". $this->lang->line('partial') .'</span>';
        
      
} else {
     
        $row[] =    "<a href=" . site_url('studentfee/addfee/' . $ab) . "  class='btn btn-danger btn-xs'>Unapplied</span>";
      
}

}

                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($students->draw),
            "recordsTotal"    => intval($students->recordsTotal),
            "recordsFiltered" => intval($students->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    
    public function feesearch()
    {
        if (!$this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/feesearch');
        $data['title']       = $this->lang->line('student_fees');
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $feesessiongroup     = $this->feesessiongroup_model->getFeesByGroup();
        $module = $this->module_model->getPermissionByModulename('transport');

        $currentsessiontransportfee = $this->transportfee_model->getSessionFees($this->current_session);
        if (!empty($currentsessiontransportfee)) {
            $transportfesstype = [];
            if ($module['is_active']) {
                $month_list = $this->customlib->getMonthDropdown($this->sch_setting_detail->start_month);
                foreach ($month_list as $key => $value) {

                    $transportfesstype[] = $this->transportfee_model->transportfesstype($this->current_session, $key);
                }

                if (!empty($transportfesstype)) {

                    foreach ($transportfesstype as $trs_key => $trs_value) {
                        $transportfesstype[$trs_key]->type = $this->lang->line(strtolower($trs_value->type));
                        $transportfesstype[$trs_key]->code = $this->lang->line(strtolower($trs_value->code));
                    }
                }

                $feesessiongroup[count($feesessiongroup)] = (object)array('id' => 'Transport', 'group_name' => 'Transport Fees', 'is_system' => 0, 'feetypes' => $transportfesstype);
            }
        }


        $data['feesessiongrouplist'] = $feesessiongroup;
        $data['fees_group']          = "";
        if (isset($_POST['feegroup_id']) && $_POST['feegroup_id'] != '') {
            $data['fees_group'] = $_POST['feegroup_id'];
        }

        if (isset($_POST['select_all']) && $_POST['select_all'] != '') {
            $data['select_all'] = $_POST['select_all'];
        }
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feegroups = $this->input->post('feegroup');
         
            $fee_group_array          = array();
            $fee_groups_feetype_array = array();
            $transport_groups_feetype_array = array();
            // foreach ($feegroups as $fee_grp_key => $fee_grp_value) {
            //     $feegroup                   = explode("-", $fee_grp_value);

            //     if ($feegroup[0] == "Transport") {
            //         $transport_groups_feetype_array[] = $feegroup[1];
            //     } else {
            //         $fee_group_array[]          = $feegroup[0];
            //         $fee_groups_feetype_array[] = $feegroup[1];
            //     }
            // }

            // $fee_group_comma = implode(', ', array_map(function ($val) {
            //     return sprintf("'%s'", $val);
            // }, array_unique($fee_group_array)));
            // $fee_groups_feetype_comma = implode(', ', array_map(function ($val) {
            //     return sprintf("'%s'", $val);
            // }, array_unique($fee_groups_feetype_array)));

            $data['student_due_fee'] = array();
           


            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            // $student_due_fee = $this->studentfee_model->getMultipleDueFees($fee_group_comma, $fee_groups_feetype_comma, $transport_groups_feetype_array, $class_id, $section_id);
            $student_due_fee = $this->student_model->searchByClassSection($class_id, $section_id);
            $students = array();

            
    
    //         if (!empty($student_due_fee)) {
    //             foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {

    //                 $amt_due = ($student_due_fee_value['is_system']) ? $student_due_fee_value['fee_master_amount'] : $student_due_fee_value['amount'];

    //                 $a = json_decode($student_due_fee_value['amount_detail']);
              
    //                 if (!empty($a)) {
    //                     $amount          = 0;
    //                     $amount_discount = 0;
    //                     $amount_fine     = 0;

    //                     foreach ($a as $a_key => $a_value) {
    //                         $amount          = $amount + $a_value->amount;
    //                         $amount_discount = $amount_discount + $a_value->amount_discount;
    //                         $amount_fine     = $amount_fine + $a_value->amount_fine;
    //                     }
    //                     if ($amt_due <= ($amount + $amount_discount)) {
    //                         unset($student_due_fee[$student_due_fee_key]);
    //                     } else {

    //                         if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {

    //                             $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
    //                         }

    //                         $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
    //                             'is_system' => $student_due_fee_value['is_system'],
    //                             'amount'          => $amt_due,
    //                             'amount_deposite' => $amount,
    //                             'amount_discount' => $amount_discount,
    //                             'amount_fine'     => $amount_fine,
    //                             'fee_group'       => $student_due_fee_value['fee_group'],
    //                             'fee_type'        => $student_due_fee_value['fee_type'],
    //                             'fee_code'        => $student_due_fee_value['fee_code'],
    //                         );
    //                     }
    //                 } else {

    //                     if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
    //                         $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
    //                     }

             



    // //   $this->db->select()->from('student_fees_master_head');
    // //     $this->db->where('student_fees_master_head.fee_master_id', $student_due_fee_value['fee_master_id']);
    // //     $this->db->where('student_fees_master_head.head_id', $student_due_fee_value['filterheadid']);
    // //     $queryy = $this->db->get();
    // //     if ($queryy->num_rows() > 0) {

            


    //                     $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
    //                         'is_system' => $student_due_fee_value['is_system'],
    //                         'amount'          => $student_due_fee_value['amount'],
    //                         'amount_deposite' => 0,
    //                         'amount_discount' => 0,
    //                         'amount_fine'     => 0,
    //                         'fee_group'       => $student_due_fee_value['fee_group'],
    //                         'fee_type'        => $student_due_fee_value['fee_type'],
    //                         'fee_code'        => $student_due_fee_value['fee_code'],
    //                     );
    //                 // }


    //                 }
    //             }
    //         }

            $data['student_remain_fees'] = $student_due_fee;

            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function reportbyclass()
    {
        $data['title']     = 'student fees';
        $data['title']     = 'student fees';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_fees_array      = array();
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $student_result          = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['student_due_fee'] = array();
            if (!empty($student_result)) {
                foreach ($student_result as $key => $student) {
                    $student_array                      = array();
                    $student_array['student_detail']    = $student;
                    $student_session_id                 = $student['student_session_id'];
                    $student_id                         = $student['id'];
                    $student_due_fee                    = $this->studentfee_model->getDueFeeBystudentSection($class_id, $section_id, $student_session_id);
                    $student_array['fee_detail']        = $student_due_fee;
                    $student_fees_array[$student['id']] = $student_array;
                }
            }
            $data['class_id']           = $class_id;
            $data['section_id']         = $section_id;
            $data['student_fees_array'] = $student_fees_array;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title']      = 'studentfee List';
        $studentfee         = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function deleteFee()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $invoice_id  = $this->input->post('main_invoice');
        $sub_invoice = $this->input->post('sub_invoice');
        if (!empty($invoice_id)) {
            $this->studentfee_model->remove($invoice_id, $sub_invoice);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function deleteStudentDiscount()
    {
        $discount_id = $this->input->post('discount_id');
        if (!empty($discount_id)) {
            $data = array('id' => $discount_id, 'status' => 'assigned', 'payment_id' => "");
            $this->feediscount_model->updateStudentDiscount($data);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function getcollectfee()
    {
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);

        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $fee_category          = $value->fee_category;
            $trans_fee_id          = $value->trans_fee_id;

            if ($fee_category == "transport") {
                $feeList               = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
                $feeList->fee_category = $fee_category;
            } else {
                $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
                $feeList->fee_category = $fee_category;
            }

            $fees_array[] = $feeList;
        }

        $data['feearray'] = $fees_array;
        $result           = array(
            'view' => $this->load->view('studentfee/getcollectfee', $data, true),
        );

        $this->output->set_output(json_encode($result));
    }

    public function addfee($id)
    {

        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $data['sch_setting']   = $this->sch_setting_detail;
        $data['title']         = 'Student Detail';
        $student               = $this->student_model->getByStudentSession($id);
        $route_pickup_point_id = $student['route_pickup_point_id'];
        $student_session_id    = $student['student_session_id'];
        $transport_fees = [];

        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active']) {

            $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
        }

        $data['student']       = $student;
        $student_due_fee       = $this->studentfeemaster_model->getStudentFees2($id);
        
        $data['bank_cash'] = $this->accountant_model->bank_cash();


        $student_discount_fee  = $this->feediscount_model->getStudentFeesDiscount($id);

        $data['transport_fees']         = $transport_fees;
        $data['student_discount_fee']   = $student_discount_fee;
        $data['student_due_fee']        = $student_due_fee;
        $category                       = $this->category_model->get();
        $data['categorylist']           = $category;
        $class_section                  = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"]          = $class_section;
        $session                        = $this->setting_model->getCurrentSession();
        $studentlistbysection           = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"]   = $studentlistbysection;
        $student_processing_fee         = $this->studentfeemaster_model->getStudentProcessingFees($id);
        $data['student_processing_fee'] = false;

        foreach ($student_processing_fee as $key => $processing_value) {
            if (!empty($processing_value->fees)) {
                $data['student_processing_fee'] = true;
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getProcessingfees($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }

        $student               = $this->student_model->getByStudentSession($id);
        $route_pickup_point_id = $student['route_pickup_point_id'];
        $student_session_id    = $student['student_session_id'];

        $transport_fees        = $this->studentfeemaster_model->getProcessingTransportFees($student_session_id, $route_pickup_point_id);
        $data['student']       = $student;
        $student_due_fee       = $this->studentfeemaster_model->getStudentProcessingFees($id);
        $data['transport_fees']  = $transport_fees;
        $data['student_due_fee'] = $student_due_fee;
     
        $result = array(
            'view' => $this->load->view('user/student/getProcessingfees', $data, true),
        );
        $this->output->set_output(json_encode($result));
    }

    public function deleteTransportFee()
    {
        $id = $this->input->post('feeid');
        $this->studenttransportfee_model->remove($id);
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'studentfee List';
        $this->studentfee_model->remove($id);
        redirect('studentfee/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Add studentfee';
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('studentfee/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit studentfees';
        $data['id']         = $id;
        $studentfee         = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('update_message') . '</div>');
            redirect('studentfee/index');
        }
    }

    public function addstudentfee()
    {
        $this->form_validation->set_rules('student_fees_master_id', $this->lang->line('fee_master'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('student'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|trim|xss_clean|numeric|callback_check_deposit');
        $this->form_validation->set_rules('amount_discount', $this->lang->line('discount'), 'required|trim|numeric|xss_clean');
        $this->form_validation->set_rules('amount_fine', $this->lang->line('fine'), 'required|trim|numeric|xss_clean');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('bank_cash_error', "Bank Cash Account", 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount'                 => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'fee_groups_feetype_id'  => form_error('fee_groups_feetype_id'),
                'amount_discount'        => form_error('amount_discount'),
                'amount_fine'            => form_error('amount_fine'),
                'payment_mode'           => form_error('payment_mode'),
                'date'           => form_error('date'),
                'bank_cash_errorr'           => form_error('bank_cash_error'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $staff_record = $this->staff_model->get($this->customlib->getStaffID());

            $collected_by             = $this->customlib->getAdminSessionUserName() . "(" . $staff_record['employee_id'] . ")";
            $student_fees_discount_id = $this->input->post('student_fees_discount_id');
            $json_array               = array(
                'amount'          => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'amount_discount' => convertCurrencyFormatToBaseAmount($this->input->post('amount_discount')),
                'amount_fine'     => convertCurrencyFormatToBaseAmount($this->input->post('amount_fine')),
                'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'description'     => $this->input->post('description'),
                'collected_by'    => $collected_by,
                'payment_mode'    => $this->input->post('payment_mode'),
                'received_by'     => $staff_record['id'],
            );

            $student_fees_master_id = $this->input->post('student_fees_master_id');
            $fee_groups_feetype_id  = $this->input->post('fee_groups_feetype_id');
            $transport_fees_id      = $this->input->post('transport_fees_id');
            $fee_category           = $this->input->post('fee_category');

            $data = array(
                'fee_category'           => $fee_category,
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'fee_groups_feetype_id'  => $this->input->post('fee_groups_feetype_id'),
                'amount_detail'          => $json_array,
            );

            if ($transport_fees_id != 0 && $fee_category == "transport") {
                $mailsms_array                    = new stdClass();
                $data['student_fees_master_id']   = null;
                $data['fee_groups_feetype_id']    = null;
                $data['student_transport_fee_id'] = $transport_fees_id;

                $mailsms_array                 = $this->studenttransportfee_model->getTransportFeeMasterByStudentTransportID($transport_fees_id);
                $mailsms_array->fee_group_name = $this->lang->line("transport_fees");
                $mailsms_array->type           = $mailsms_array->month;
                $mailsms_array->code           = "";
            } else {

                $mailsms_array = $this->feegrouptype_model->getFeeGroupByIDAndStudentSessionID($this->input->post('fee_groups_feetype_id'), $this->input->post('student_session_id'));

                if ($mailsms_array->is_system) {
                    $mailsms_array->amount = $mailsms_array->balance_fee_master_amount;
                }
            }

            $action             = $this->input->post('action');
            $send_to            = $this->input->post('guardian_phone');
            $email              = $this->input->post('guardian_email');
            $parent_app_key     = $this->input->post('parent_app_key');
            $student_session_id = $this->input->post('student_session_id');
            $inserted_id        = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id);
            date_default_timezone_set('Asia/Kolkata'); 
$check = json_decode($inserted_id);
$vocuher =  $check->invoice_id.'/'.$check->sub_invoice_id;
    //start  accounts voucher

        $rolll = $this->customlib->getLoggedInUserData();
        $tobeinserted = array(
          'branch_id' =>$this->config->item('branch_id'),
          'bank_cash_id' =>$this->input->post('bank_cash_error'),
          'income_expense_head_id' => $this->config->item('student_fees_submit'),
          'cr' =>convertCurrencyFormatToBaseAmount($this->input->post('amount')),
          'voucher_type' => 'CV',
          'voucher_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
          'particulars' => $this->input->post('description'),
          'voucher_no' => $vocuher,
          'created_by' =>  $rolll['email'],
          'created_at' =>date('Y-m-d H:i:s'),
        );
        $this->account->insert('transactions', $tobeinserted);
        $record_id = $this->account->insert_id();

     //end  accounts voucher


            $print_record = array();
            if ($action == "print") {
                $receipt_data           = json_decode($inserted_id);
                $data['sch_setting']    = $this->sch_setting_detail;

                $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $data['student']        = $student;
                $data['sub_invoice_id'] = $receipt_data->sub_invoice_id;

                $setting_result         = $this->setting_model->get();
                $data['settinglist']    = $setting_result;

                if ($transport_fees_id != 0 && $fee_category == "transport") {

                    $fee_record = $this->studentfeemaster_model->getTransportFeeByInvoice($receipt_data->invoice_id, $receipt_data->sub_invoice_id);
                    $data['feeList']        = $fee_record;
                    $print_record = $this->load->view('print/printTransportFeesByName', $data, true);
                } else {

                    $fee_record             = $this->studentfeemaster_model->getFeeByInvoice($receipt_data->invoice_id, $receipt_data->sub_invoice_id);
                    $data['feeList']        = $fee_record;
                    $print_record = $this->load->view('print/printFeesByName', $data, true);
                }
            }

            $mailsms_array->invoice            = $inserted_id;
            $mailsms_array->student_session_id = $student_session_id;
            $mailsms_array->contact_no         = $send_to;
            $mailsms_array->email              = $email;
            $mailsms_array->parent_app_key     = $parent_app_key;
            $mailsms_array->fee_category       = $fee_category;

            $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);

            $array = array('status' => 'success', 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
    }

    public function printFeesByName()
    {
        $data                   = array('payment' => "0");
        $record                 = $this->input->post('data');
        $fee_category           = $this->input->post('fee_category');
        $invoice_id             = $this->input->post('main_invoice');
        $sub_invoice_id         = $this->input->post('sub_invoice');
        $student_session_id     = $this->input->post('student_session_id');
        $setting_result         = $this->setting_model->get();
        $data['settinglist']    = $setting_result;
        $student                = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $data['student']        = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['sch_setting']    = $this->sch_setting_detail;

        $data['superadmin_rest'] = $this->customlib->superadmin_visible();

        if ($fee_category == "transport") {
            $fee_record      = $this->studentfeemaster_model->getTransportFeeByInvoice($invoice_id, $sub_invoice_id);
            $data['feeList'] = $fee_record;
            $page            = $this->load->view('print/printTransportFeesByName', $data, true);
        } else {
            $fee_record      = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
            $data['feeList'] = $fee_record;
            $page = $this->load->view('print/printFeesByName', $data, true);
        }

        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function printFeesByGroup()
    {
        $fee_category        = $this->input->post('fee_category');
        $trans_fee_id        = $this->input->post('trans_fee_id');
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($fee_category == "transport") {
            $data['feeList'] = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
            $page = $this->load->view('print/printTransportFeesByGroup', $data, true);
        } else {

            $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
            $fee_master_id         = $this->input->post('fee_master_id');
            $fee_session_group_id  = $this->input->post('fee_session_group_id');
            $data['feeList']       = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $page                  = $this->load->view('print/printFeesByGroup', $data, true);
        }

        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function printFeesByGroupArray()
    {
        $data['sch_setting'] = $this->sch_setting_detail;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);
        $fees_array          = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $fee_category          = $value->fee_category;
            $trans_fee_id          = $value->trans_fee_id;

            if ($fee_category == "transport") {
                $feeList               = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
                $feeList->fee_category = $fee_category;
            } else {
                $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
                $feeList->fee_category = $fee_category;
            }

            $fees_array[] = $feeList;
        }

        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArray', $data);
    }


    
    public function printsingleFeesByGroupArray()
    {
        $data['sch_setting'] = $this->sch_setting_detail;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);
        $fees_array          = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $fee_category          = $value->fee_category;
            $trans_fee_id          = $value->trans_fee_id;

            if ($fee_category == "transport") {
                $feeList               = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
                $feeList->fee_category = $fee_category;
            } else {
                $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
                $feeList->fee_category = $fee_category;
            }

            $fees_array[] = $feeList;
        }

        $data['feearray'] = $fees_array;
        $this->load->view('print/printsingleFeesByGroupArray', $data);
    }

    public function searchpayment()
    {
        if (!$this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/searchpayment');
        $data['title'] = $this->lang->line('fees_collection');

        $this->form_validation->set_rules('paymentid', $this->lang->line('payment_id'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $paymentid = $this->input->post('paymentid');
            $invoice   = explode("/", $paymentid);

            if (array_key_exists(0, $invoice) && array_key_exists(1, $invoice)) {
                $invoice_id             = $invoice[0];
                $sub_invoice_id         = $invoice[1];
                $feeList                = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
               $current_session= $this->customlib->getCurrentSession();
                $data['current_session']        = $current_session;
                $data['feeList']        = $feeList;
                $data['sub_invoice_id'] = $sub_invoice_id;
            } else {
                $data['feeList'] = array();
            }
        }
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/searchpayment', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addfeegroup()
    {

        
// custom code by Hritik
        $category_head_id     = $this->input->post('category_head_id');
        $category_head_array = isset($category_head_id) ? $category_head_id : array();
     
//end custom code by Hritik
        if(count($category_head_array) == 0){
            $this->form_validation->set_rules('category_head_id','Fees Code', 'required|trim|xss_clean');

        }
       

        $this->form_validation->set_rules('fee_session_groups', $this->lang->line('fee_group'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_session_groups' => form_error('fee_session_groups'),
                'category_head_id' => form_error('category_head_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_session_id     = $this->input->post('student_session_id');
            $fee_session_groups     = $this->input->post('fee_session_groups');
            $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
            $student_ids            = $this->input->post('student_ids');
            $delete_student         = array_diff($student_ids, $student_sesssion_array);

            $preserve_record = array();
            // print_r($student_sesssion_array);die;
            if (!empty($student_sesssion_array)) {
                foreach ($student_sesssion_array as $key => $value) {
                    $insert_array = array(
                        'student_session_id'   => $value,
                        'fee_session_group_id' => $fee_session_groups,
                        // 'amount' => ,
                    );
                    $this->studentfeemaster_model->addd($insert_array,$category_head_array);
                }
            }



            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }


    }

    public function geBalanceFee()
    {
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('fee_groups_feetype_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_master_id', $this->lang->line('student_fees_master_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_session_id', $this->lang->line('student_session_id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_groups_feetype_id'  => form_error('fee_groups_feetype_id'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'student_session_id'     => form_error('student_session_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data                 = array();
            $student_session_id   = $this->input->post('student_session_id');
            $discount_not_applied = $this->getNotAppliedDiscount($student_session_id);

            $fee_category = $this->input->post('fee_category');

            if ($fee_category == "transport") {
                $trans_fee_id         = $this->input->post('trans_fee_id');
                $remain_amount_object = $this->getStudentTransportFeetypeBalance($trans_fee_id);
                $remain_amount        = (float) json_decode($remain_amount_object)->balance;
                $remain_amount_fine   = json_decode($remain_amount_object)->fine_amount;
            } else {
                $fee_groups_feetype_id  = $this->input->post('fee_groups_feetype_id');
                $student_fees_master_id = $this->input->post('student_fees_master_id');
                $remain_amount_object   = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
                $remain_amount          = json_decode($remain_amount_object)->balance;
                $remain_amount_fine     = json_decode($remain_amount_object)->fine_amount;
            }

            $remain_amount = number_format($remain_amount, 2, ".", "");

            $array = array('status' => 'success', 'error' => '', 'balance' => convertBaseAmountCurrencyFormat($remain_amount), 'discount_not_applied' => $discount_not_applied, 'remain_amount_fine' => convertBaseAmountCurrencyFormat($remain_amount_fine), 'student_fees' => convertBaseAmountCurrencyFormat(json_decode($remain_amount_object)->student_fees));
            echo json_encode($array);
        }
    }

    public function getStudentTransportFeetypeBalance($trans_fee_id)
    {
        $data = array();

        $result          = $this->studentfeemaster_model->studentTransportDeposit($trans_fee_id);
        $amount_balance  = 0;
        $amount          = 0;
        $amount_fine     = 0;
        $amount_discount = 0;
        $fine_amount     = 0;
        $fee_fine_amount = 0;

        $due_amt = $result->fees;
        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
            $fee_fine_amount = is_null($result->fine_percentage) ? $result->fine_amount : percentageAmount($result->fees, $result->fine_percentage);
        }

        $amount_detail = json_decode($result->amount_detail);
        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount          = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
            }
        }

        $amount_balance = $due_amt - ($amount + $amount_discount);
        $fine_amount    = abs($amount_fine - $fee_fine_amount);
        $array          = array('status' => 'success', 'error' => '', 'student_fees' => $due_amt, 'balance' => $amount_balance, 'fine_amount' => $fine_amount);
        return json_encode($array);
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        $data                           = array();
        $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
        $data['student_fees_master_id'] = $student_fees_master_id;
        $result                         = $this->studentfeemaster_model->studentDeposit($data);

        $amount_balance  = 0;
        $amount          = 0;
        $amount_fine     = 0;
        $amount_discount = 0;
        $fine_amount     = 0;
        $fee_fine_amount = 0;
        $due_amt         = $result->amount;
        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
            $fee_fine_amount = $result->fine_amount;
        }

        if ($result->is_system) {
            $due_amt = $result->student_fees_master_amount;
        }

        $amount_detail = json_decode($result->amount_detail);
        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount          = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
            }
        }

        $amount_balance = $due_amt - ($amount + $amount_discount);
        $fine_amount    = ($fee_fine_amount > 0) ? ($fee_fine_amount - $amount_fine) : 0;

        $array          = array('status' => 'success', 'error' => '', 'student_fees' => $due_amt, 'balance' => $amount_balance, 'fine_amount' => $fine_amount);
        return json_encode($array);
    }

    public function check_deposit($amount)
    {
        if (is_numeric($this->input->post('amount')) && is_numeric($this->input->post('amount_discount'))) {
            if ($this->input->post('amount') != "" && $this->input->post('amount_discount') != "") {
                if ($this->input->post('amount') < 0) {
                    $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_less_than_zero'));
                    return false;
                } else {
                    $transport_fees_id      = $this->input->post('transport_fees_id');
                    $student_fees_master_id = $this->input->post('student_fees_master_id');
                    $fee_groups_feetype_id  = $this->input->post('fee_groups_feetype_id');
                    $deposit_amount         = $this->input->post('amount') + $this->input->post('amount_discount');
                    if ($transport_fees_id != 0) {
                        $remain_amount = $this->getStudentTransportFeetypeBalance($transport_fees_id);
                    } else {
                        $remain_amount = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
                    }
                    $remain_amount = json_decode($remain_amount)->balance;
                    if (convertBaseAmountCurrencyFormat($remain_amount) < $deposit_amount) {
                        $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_greater_than_remaining'));
                        return false;
                    } else {
                        return true;
                    }
                }
                return true;
            }
        } elseif (!is_numeric($this->input->post('amount'))) {
            $this->form_validation->set_message('check_deposit', $this->lang->line('amount_field_must_contain_only_numbers'));
            return false;
        } elseif (!is_numeric($this->input->post('amount_discount'))) {
            return true;
        }

        return true;
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        $discounts_array = $this->feediscount_model->getDiscountNotApplied($student_session_id);
        foreach ($discounts_array as $discount_key => $discount_value) {
            $discounts_array[$discount_key]->{"amount"} = convertBaseAmountCurrencyFormat($discount_value->amount);
        }
        return $discounts_array;
    }

    public function addfeegrp()
    {
        $staff_record = $this->staff_model->get($this->customlib->getStaffID());
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', $this->lang->line('fees_list'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', $this->lang->line('date'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter'    => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $collected_array = array();
            $staff_record    = $this->staff_model->get($this->customlib->getStaffID());
            $collected_by    = $this->customlib->getAdminSessionUserName() . "(" . $staff_record['employee_id'] . ")";

            $send_to            = $this->input->post('guardian_phone');
            $email              = $this->input->post('guardian_email');
            $parent_app_key     = $this->input->post('parent_app_key');
            $student_session_id = $this->input->post('student_session_id');
            $student = $this->student_model->getByStudentSession($student_session_id);
            $total_row          = $this->input->post('row_counter');
            foreach ($total_row as $total_row_key => $total_row_value) {

                $fee_category             = $this->input->post('fee_category_' . $total_row_value);
                $student_transport_fee_id = $this->input->post('trans_fee_id_' . $total_row_value);

                $json_array = array(
                    'amount'          => $this->input->post('fee_amount_' . $total_row_value),
                    'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                    'description'     => $this->input->post('fee_gupcollected_note'),
                    'receipt_no'     => $this->input->post('receipt_no'),
                    'amount_discount' => 0,
                    'collected_by'    => $collected_by,
                    'amount_fine'     => $this->input->post('fee_groups_feetype_fine_amount_' . $total_row_value),
                    'payment_mode'    => $this->input->post('payment_mode_fee'),
                    'received_by'     => $staff_record['id'],
                );

                $collected_array[] = array(
                    'fee_category'             => $fee_category,
                    'student_transport_fee_id' => $student_transport_fee_id,
                    'student_fees_master_id'   => $this->input->post('student_fees_master_id_' . $total_row_value),
                    'fee_groups_feetype_id'    => $this->input->post('fee_groups_feetype_id_' . $total_row_value),
                    'amount_detail'            => $json_array,
                );
            }

            $deposited_fees = $this->studentfeemaster_model->fee_deposit_collections($collected_array);

   //start  discount save


   if ($deposited_fees && is_array($deposited_fees) && (intval( $this->input->post('discount_amount')) > 0)) {
    $newinsertarray = array(
        'student_session_id' =>$this->input->post('student_session_id'),
        'collected_date' =>date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
        'discount_amount' =>$this->input->post('discount_amount'),
        'note' =>$this->input->post('fee_gupcollected_note'),
        'created_at' =>date('Y-m-d H:i:s'),
      );

      $this->db->insert('student_bulk_discount', $newinsertarray);

   }
 //end  discount save


$savedep = $deposited_fees ;


if ($savedep && is_array($savedep)) {
    $subamt  = 0;
    $vocuher = '';
    foreach ($savedep as $deposited_fees_key => $deposited_fees_value) {
$vocuher .=  '('.$deposited_fees_value['invoice_id'].'/'.$deposited_fees_value['sub_invoice_id'].') ,';
  $subamt +=   convertCurrencyFormatToBaseAmount($deposited_fees_value['amount_s']);
    }

      //start  accounts voucher
      date_default_timezone_set('Asia/Kolkata'); 
      $rolll = $this->customlib->getLoggedInUserData();
      $tobeinserted = array(
        'branch_id' =>$this->config->item('branch_id'),
        'bank_cash_id' =>$this->input->post('ledger'),
        'income_expense_head_id' => $this->config->item('student_fees_submit'),
        'cr' =>$subamt,
        'voucher_type' => 'CV',
        'voucher_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
        'particulars' =>$this->input->post('fee_gupcollected_note'),
        'voucher_no' => $vocuher,
        'created_by' =>  $rolll['email'],
        'created_at' =>date('Y-m-d H:i:s'),
      );

// print_r($tobeinserted) ;die;

      $this->account->insert('transactions', $tobeinserted);
      $record_id = $this->account->insert_id();
    //   die;
      

   //end  accounts voucher
    }

            if ($deposited_fees && is_array($deposited_fees)) {
                foreach ($deposited_fees as $deposited_fees_key => $deposited_fees_value) {
                    $fee_category = $deposited_fees_value['fee_category'];
                    $invoice[]   = array(
                        'invoice_id'     => $deposited_fees_value['invoice_id'],
                        'sub_invoice_id' => $deposited_fees_value['sub_invoice_id'],
                        'fee_category' => $fee_category,
                    );


                    if ($deposited_fees_value['student_transport_fee_id'] != 0 && $deposited_fees_value['fee_category'] == "transport") {

                        $data['student_fees_master_id']   = null;
                        $data['fee_groups_feetype_id']    = null;
                        $data['student_transport_fee_id'] = $deposited_fees_value['student_transport_fee_id'];

                        $mailsms_array     = $this->studenttransportfee_model->getTransportFeeMasterByStudentTransportID($deposited_fees_value['student_transport_fee_id']);
                        $fee_group_name[]  = $this->lang->line("transport_fees");
                        $type[]            = $mailsms_array->month;
                        $code[]            = "-";
                        $fine_type[]       = $mailsms_array->fine_type;
                        $due_date[]        = $mailsms_array->due_date;
                        $fine_percentage[] = $mailsms_array->fine_percentage;
                        $fine_amount[]     = amountFormat($mailsms_array->fine_amount);
                        $amount[]          = amountFormat($mailsms_array->amount);
                    } else {

                        $mailsms_array = $this->feegrouptype_model->getFeeGroupByIDAndStudentSessionID($deposited_fees_value['fee_groups_feetype_id'], $student_session_id);

                        $fee_group_name[]  = $mailsms_array->fee_group_name;
                        $type[]            = $mailsms_array->type;
                        $code[]            = $mailsms_array->code;
                        $fine_type[]       = $mailsms_array->fine_type;
                        $due_date[]        = $mailsms_array->due_date;
                        $fine_percentage[] = $mailsms_array->fine_percentage;
                        $fine_amount[]     = amountFormat($mailsms_array->fine_amount);

                        if ($mailsms_array->is_system) {
                            $amount[] = amountFormat($mailsms_array->balance_fee_master_amount);
                        } else {
                            $amount[] = amountFormat($mailsms_array->amount);
                        }
                    }
                }
                $obj_mail                     = [];
                $obj_mail['student_id']  = $student['id'];
                $obj_mail['student_session_id'] = $student_session_id;

                $obj_mail['invoice']         = $invoice;
                $obj_mail['contact_no']      = $student['guardian_phone'];
                $obj_mail['email']           = $student['email'];
                $obj_mail['parent_app_key']  = $student['parent_app_key'];
                $obj_mail['amount']          = "(" . implode(',', $amount) . ")";
                $obj_mail['fine_type']       = "(" . implode(',', $fine_type) . ")";
                $obj_mail['due_date']        = "(" . implode(',', $due_date) . ")";
                $obj_mail['fine_percentage'] = "(" . implode(',', $fine_percentage) . ")";
                $obj_mail['fine_amount']     = "(" . implode(',', $fine_amount) . ")";
                $obj_mail['fee_group_name']  = "(" . implode(',', $fee_group_name) . ")";
                $obj_mail['type']            = "(" . implode(',', $type) . ")";
                $obj_mail['code']            = "(" . implode(',', $code) . ")";
                $obj_mail['fee_category']    = $fee_category;
                $obj_mail['send_type']    = 'group';


                $this->mailsmsconf->mailsms('fee_submission', $obj_mail);
            }


            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
        }
    }

    public function add_new_student($student)
    {
        $new_student = array(
            'id'                 => $student['id'],
            'student_session_id' => $student['student_session_id'],
            'class'              => $student['class'],
            'section_id'         => $student['section_id'],
            'section'            => $student['section'],
            'admission_no'       => $student['admission_no'],
            'roll_no'            => $student['roll_no'],
            'admission_date'     => $student['admission_date'],
            'firstname'          => $student['firstname'],
            'middlename'         => $student['middlename'],
            'lastname'           => $student['lastname'],
            'image'              => $student['image'],
            'mobileno'           => $student['mobileno'],
            'email'              => $student['email'],
            'state'              => $student['state'],
            'city'               => $student['city'],
            'pincode'            => $student['pincode'],
            'religion'           => $student['religion'],
            'dob'                => $student['dob'],
            'current_address'    => $student['current_address'],
            'permanent_address'  => $student['permanent_address'],
            'category_id'        => $student['category_id'],
            'category'           => $student['category'],
            'adhar_no'           => $student['adhar_no'],
            'samagra_id'         => $student['samagra_id'],
            'bank_account_no'    => $student['bank_account_no'],
            'bank_name'          => $student['bank_name'],
            'ifsc_code'          => $student['ifsc_code'],
            'guardian_name'      => $student['guardian_name'],
            'guardian_relation'  => $student['guardian_relation'],
            'guardian_phone'     => $student['guardian_phone'],
            'guardian_address'   => $student['guardian_address'],
            'is_active'          => $student['is_active'],
            'father_name'        => $student['father_name'],
            'rte'                => $student['rte'],
            'gender'             => $student['gender'],

        );
        return $new_student;
    }



    
    public function getautomatedcollectfee()
    {
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);

if(isset($record_array[0]->newamount)){
    $newamount = $record_array[0]->newamount;
}else{
    $newamount = 0;
}



        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $fee_category          = $value->fee_category;
            $trans_fee_id          = $value->trans_fee_id;

            if ($fee_category == "transport") {
                $feeList               = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
                $feeList->fee_category = $fee_category;
            } else {
                $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
                $feeList->fee_category = $fee_category;
            }

            $fees_array[] = $feeList;
        }



        // echo "<pre>";
        // print_r($fees_array);die;
         $data['newamount'] = $newamount;
        $data['feearray'] = $fees_array;
        $result           = array(
            'view' => $this->load->view('studentfee/getautomatedcollectfee', $data, true),
        );

        $this->output->set_output(json_encode($result));
    }


    
    public function getordercollectfee()
    {
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record              = $this->input->post('data');
        $record_array        = json_decode($record);

if(isset($record_array[0]->newamount)){
    $newamount = $record_array[0]->newamount;
}else{
    $newamount = 0;
}


if(isset($record_array[0]->newdate)){
    $newdate = $record_array[0]->newdate;
}else{
    $newdate = '';
}

        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id         = $value->fee_master_id;
            $fee_session_group_id  = $value->fee_session_group_id;
            $fee_category          = $value->fee_category;
            $trans_fee_id          = $value->trans_fee_id;

            if ($fee_category == "transport") {
                $feeList               = $this->studentfeemaster_model->getTransportFeeByID($trans_fee_id);
                $feeList->fee_category = $fee_category;
            } else {
                $feeList               = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
                $feeList->fee_category = $fee_category;
            }

            $fees_array[] = $feeList;
        }

        $data['bank_cash'] = $this->accountant_model->bank_cash();

        // echo "<pre>";
        // print_r($fees_array);die;
         $data['newamount'] = $newamount;
         $data['newdate'] = $newdate;
        $data['feearray'] = $fees_array;
        $result           = array(
            'view' => $this->load->view('studentfee/getinordercollectfee', $data, true),
        );

        $this->output->set_output(json_encode($result));
    }


    public function getappliedhead(){

        $fee_master_id = $this->input->post('fee_master_id');
        //   echo $fee_master_id;

   
           
          $this->db->select()->from('student_fees_master_head');
          $this->db->where('student_fees_master_head.fee_master_id', $fee_master_id);
          $this->db->order_by('student_fees_master_head.id', 'Asc');
          $query = $this->db->get();
          $head_data = $query->result();
          $result = "";
          $addarray = array();
         if($query->num_rows() > 0) {

            
     $result .=   "<div class='row'><div class='col-md-6'>";
                 foreach($head_data as $head){

                    // $addarray[] = $head->head_id; 


                    $this->db->select()->from('fee_groups_feetype');
                    $this->db->where('fee_groups_feetype.id', $head->head_id);
                    $queryy = $this->db->get();
                    $head_namee = $queryy->row();


                    $this->db->select()->from('feetype');
                    $this->db->where('feetype.id', $head_namee->feetype_id);
                    $queryyy = $this->db->get();
                    $head_name = $queryyy->row();


                    $addarray[] = $head_name->id; 

     $result .=  "<div class='row' >
                <div class='col-md-10'>";
                $result .=          $head_name->code;
     $result .=  "</div>
                 <div class='col-md-2'>";
     $result .=  "<a id='".$head->id."' class='btn btn-default btn-xs deleteappliedhead' data-toggle='tooltip' title='' data-original-title='Delete'><i class='fa fa-trash'></i></a>";

     $result .=   "</div><hr></div>";
                  }
     $result .=  "</div>";

     $session =  $this->setting_model->getCurrentSession();

     $groupid = $this->input->post('groupid');
     


     $this->db->select()->from('fee_groups');
     $this->db->where('fee_groups.name', $groupid);
     $quer = $this->db->get();
     $group = $quer->row();

                   $this->db->select()->from('student_fees_master');
                    $this->db->where('student_fees_master.id', $fee_master_id);
                    $que = $this->db->get();
                    $add = $que->row();
// print_r( $addarray) ;die;
           
                    $this->db->select()->from('fee_groups_feetype');
                    $this->db->where('fee_groups_feetype.fee_session_group_id', $add->fee_session_group_id);
                    $this->db->where('fee_groups_feetype.session_id', $session);
                    $this->db->where('fee_groups_feetype.fee_groups_id', $group->id);
                    $this->db->where_not_in('fee_groups_feetype.feetype_id', $addarray);
                    $ques = $this->db->get();
                    $dat = $ques->result();


// print_r($dat);die;

// if($ques->num_rows() > 0) {



    $result .=   "<div class='col-md-6'>";
    foreach($dat as $aad_data){

     
        // $this->db->select()->from('fee_groups_feetype');
        // $this->db->where('fee_groups_feetype.id', $aad_data->head_id);
        // $queryy = $this->db->get();
        // $head_namee = $queryy->row();

   


       $this->db->select()->from('feetype');
       $this->db->where('feetype.id', $aad_data->feetype_id);
       $queryyy = $this->db->get();
       $head_name = $queryyy->row();


$result .=  "<div class='row' >
   <div class='col-md-10'>";
   $result .=          $head_name->code;
$result .=  "</div>
    <div class='col-md-2'>";
$result .=  "<a id='".$fee_master_id."' data-id='".$aad_data->id."' class='btn btn-default btn-xs addappliedhead' data-toggle='tooltip' title='' data-original-title='Add fee Code'><i class='fa fa-plus'></i></a>";

$result .=   "</div><hr></div>";
     }
$result .=  "</div></div>";




// }



         }else{
             $result .= "<div class='col-md-12 text-center text-danger'>No Head Is Applied On This Student</div>";


             

         }
    
           
// print_r($addarray);die;

        echo $result;


    }


    public function deleteappliedhead(){

        $result = $this->input->post('id');



        $this->db->select('*')->from('student_fees_master');
        $this->db->join('`student_fees_master_head`', '`student_fees_master_head`.`fee_master_id` = `student_fees_master`.`id`');
        $this->db->join('`fee_groups_feetype`', '`fee_groups_feetype`.`id` = `student_fees_master_head`.`head_id`');
        $this->db->where('`student_fees_master_head`.`id`', $result);
        $query1        = $this->db->get();
        $result_value1 = $query1->row();


$this->db->select()->from('student_fees_deposite');
$this->db->where('student_fees_deposite.student_fees_master_id', $result_value1->fee_master_id);
$this->db->where('student_fees_deposite.fee_groups_feetype_id', $result_value1->id);
$queryy = $this->db->get();
$count = $queryy->num_rows();
if ($count > 0) {

    echo 'This Fee Code Can`t Deleted as Fees Is Already Submitted By Student';

}else{

        $this->db->where('id', $result);
        $this->db->delete('student_fees_master_head');
        echo 'Fee Code Deleted Successfully';

}

    }

    
    public function addappliedhead(){

        $result = $this->input->post('id');
        $headid = $this->input->post('headid');


        $insert_arrayy = array(
            'head_id'   => $headid,
            'fee_master_id' => $result,
        );


            $this->db->insert('student_fees_master_head', $insert_arrayy);


        echo 1;




    }

    public function searchpaymentreceipt()
    {
        if (!$this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/searchpayment');
        $data['title'] = $this->lang->line('fees_collection');

        $this->form_validation->set_rules('reciptnumber','Recipt Number', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $reciptnumber = $this->input->post('reciptnumber');
           

            if ($reciptnumber) {
               
                $feeList                = $this->studentfeemaster_model->getFeeByrecipt($reciptnumber);
               $current_session= $this->customlib->getCurrentSession();
                $data['current_session']        = $current_session;
                $data['feeListt']        = $feeList;
                // $data['sub_invoice_id'] = $sub_invoice_id;
            } else {
                $data['feeListt'] = array();
            }
        }
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/searchpayment', $data);
        $this->load->view('layout/footer', $data);
    }









public function deposits_save_in_account()
{

/*
$this->db->select('*')->from('student_fees_deposite');
$query   = $this->db->get();
$result = $query->result();
$tobeinserted = array();
if (count($result) > 0) {
  foreach($result as $res){
    $vocuher = '';   $subamt =0;
    $primary = $res->id;
$second = json_decode($res->amount_detail, true);
foreach ($second as $deposited_fees_key => $deposited_fees_value) {
    $vocuher .=  '('.$primary.'/'.$deposited_fees_key.') ,';
      $subamt +=   convertCurrencyFormatToBaseAmount($deposited_fees_value['amount']);
      $date =         $deposited_fees_value['date'] ;
        }


        date_default_timezone_set('Asia/Kolkata'); 
        $rolll = $this->customlib->getLoggedInUserData();
        $tobeinserted[] = array(
          'branch_id' =>$this->config->item('branch_id'),
          'bank_cash_id' =>$this->input->post('ledger'),
          'income_expense_head_id' => $this->config->item('student_fees_submit'),
          'cr' =>$subamt,
          'voucher_type' => 'CV',
          'voucher_date' =>  date('Y-m-d', strtotime($date)),
          'particulars' =>$this->input->post('fee_gupcollected_note'),
          'voucher_no' => $vocuher,
          'created_by' =>  $rolll['email'],
          'created_at' =>date('Y-m-d H:i:s'),
        );
  


  }

		$this->account->insert_batch('transactions', $tobeinserted);
}*/
    
    

}

public function allfeescalcaulation()
{
    $class       = $this->input->post('class_id');
    $section     = $this->input->post('section_id');
    $studentscal = $this->student_model->calculateByClassSection($class, $section);
    $total_amount = 0;
    $total_discount_amount = 0;
    $total_deposite_amount =  0;
    $total_fine_amount = 0;
    $feetype_balance = 0;
    $total_balance_amount = 0;
    $total_bulk_discount = $this->studentfeemaster_model->getStudent_bulkdiscounts_Sum($studentscal);
    if (!empty($studentscal)) {
     //   foreach ($studentscal as $student_key => $studentt) {
            $student_due_fee       = $this->studentfeemaster_model->getStudentFees3($studentscal);
////
foreach ($student_due_fee as $key => $fee) {
 

    
    foreach ($fee->fees as $fee_key => $fee_value) {



        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;

        // echo "<pre>";
        // print_r($fee);die;
        
            // $this->db->select()->from('student_fees_master_head');
            // $this->db->where('student_fees_master_head.fee_master_id', $fee_value->id);
            // $this->db->where('student_fees_master_head.head_id', $fee_value->fee_groups_feetype_id);
            // $queryy = $this->db->get();
            // if ($queryy->num_rows() > 0) {


                if (!empty($fee_value->amount_detail)) {
                    $fee_deposits = json_decode(($fee_value->amount_detail));
        
                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                        $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                        $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                        $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                    }
                }
                if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                    $fees_fine_amount       = $fee_value->fine_amount;
                    $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                }
     

        $total_amount += $fee_value->amount;
     //   $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
 //       $total_fine_amount += $fee_fine;
        $feetype_balance = $fee_value->amount - ($fee_paid);
        $total_balance_amount += $feetype_balance;
    // }

}

}



/////

      //  }
    }
    // echo json_encode($total_bulk_discount);die;
$a ='<div class="col-md-3" style="font-size:22px">Total Amount:<span style="color:green" id="total_amount">'.$total_amount.'</span></div>
        <div class="col-md-3" style="font-size:22px">Total Discount:<span style="color:green" id="">';
        
         if($total_bulk_discount->discount){
            $a .=  "".$total_bulk_discount->discount."";
         }else{
            $a .=  "0";
         }
     
        
        
        $a .=        '</span></div>
    <div class="col-md-3" style="font-size:22px">Total Paid:<span style="color:green" id="total_paid">'.$total_deposite_amount.'</span></div>
    <div class="col-md-3" style="font-size:22px">Total Balance:<span style="color:green" id="total_balance">'.$total_balance_amount.'</span></div>';
    echo json_encode($a);



}



public function getStudent_bulkdiscounts($aray)
{  
    $student_due_fee       = $this->studentfeemaster_model->getStudent_bulkdiscounts([$aray]);

$a ="<table class='table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer'>
<thead>
<tr>
<th>SR</th>
<th>Discount Amount</th>
<th>Note</th>
<th>Assigned Date</th>
<th>Created At</th>
</tr>
</thead>
<tbody>";
if($student_due_fee){
    $i = 0;
foreach($student_due_fee  as $discount){ $i++;
$a .= "<tr> 
<td>".$i."</td>
<td>".amountFormat(($discount->discount_amount))."</td>
<td>".$discount->note."</td>
<td>".date("d-m-Y", strtotime($discount->collected_date))."</td>
<td>".date("d-m-Y h:i A", strtotime($discount->created_at))."</td>
</tr>";
}
}else{
    $a .= "<tr>
    <td colspan='5' class='text-center'>NO Data Found</td>
  
    </tr>";

}


$a .="</tbody>
</table>";


echo $a;
}




}