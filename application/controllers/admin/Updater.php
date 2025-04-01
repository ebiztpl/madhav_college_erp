<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Updater extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('string');
    }

    public function index($chk = null)
    {
        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/updater');
        if ($chk == "") {
            $fn_response     = $this->checkup();
            $res_json        = json_decode($fn_response);
            $data['version'] = $res_json->version;
        } else {
            if (!$this->session->flashdata('message') && !$this->session->flashdata('error')) {

                $fn_response     = $this->checkup();
                $res_json        = json_decode($fn_response);
                $data['version'] = $res_json->version;

            } else {

                if ($this->session->has_userdata('version')) {
                    $fn_response     = $this->checkup();
                    $res_json        = json_decode($fn_response);
                    $data['version'] = $res_json->version;
                } else {

                }

            }
        }
        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $this->auth->clear_messages();
            $this->auth->clear_error();
            $this->auth->autoupdate();
            $this->session->set_flashdata('message', $this->auth->messages());
            $this->session->set_flashdata('error', $this->auth->error());
            redirect('admin/updater/index/' . random_string('alpha', 16), 'refresh');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/updater/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function checkup()
    {
        $version  = "";
        $response = $this->auth->checkupdate();
        if ($response) {
            $result = json_decode($response);
            if ($this->session->has_userdata('version')) {
                $version = $this->session->userdata('version');
                $version = $version['version'];
            }
            $this->session->set_flashdata('message', $this->auth->messages());
        } else {
            $this->session->set_flashdata('error', $this->auth->error());
        }
        return json_encode(array('version' => $version));
    }



    public function budget()
    { 

        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/updater');
  


        $staff_list      = $this->expense_model->staffget();
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $data['staff_list'] = $staff_list;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $income_result       = $this->income_model->get();
        $data['incomelist']  = $income_result;
        $incomeHead          = $this->incomehead_model->get();
        $data['incheadlistt'] = $incomeHead;
  

        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;
        $category_resultt      = $this->expensehead_model->getcategory();
        $data['categorylistt'] = $category_resultt;




      $sess=     $this->setting_model->getCurrentSession();
        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session',$sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat = $query->result();
        
        $data['tabdat']  =$tabdat;

        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'expense');
        $this->db->where('session', $sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat1 = $query->result();
        $data['tabdat1']  =$tabdat1;



        $this->db->select('head')->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session',$sess);
        $this->db->order_by('session_budget.id', 'desc');
        $check = $this->db->get();
        $check = $check->result_array();

        $arr = array_map (function($value){
            return $value['head'];
        } , $check);
     

         $this->db->select('head')->from('session_budget');
         $this->db->where('expense_income`', 'expense');
         $this->db->where('session',$sess);
         $this->db->order_by('session_budget.id', 'desc');
         $check2 = $this->db->get();
         $check2 = $check2->result_array();
 
         $arr2 = array_map (function($value){
             return $value['head'];
         } , $check2);
        
         $data['arr']  = $arr ;
         $data['arr2']  =$arr2;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/budget/budget', $data);
        $this->load->view('layout/footer', $data);


    }


    public function budgeticomesave()
    { 

        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->form_validation->set_rules('inc_head_idd', $this->lang->line('income_head').' category', 'trim|required|xss_clean');
        $this->form_validation->set_rules('inc_head_id', $this->lang->line('income_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
    
        if ($this->form_validation->run() == true) {

          

            $data = array(
                'session' => $this->input->post('popup_session'),
                'category_id' => $this->input->post('inc_head_idd'),
                'expense_income'	 => $this->input->post('budget_type'),
                'head' => $this->input->post('inc_head_id'),
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'description'        => $this->input->post('description'),
                
            );
            $this->load->model('MY_Model');
            $this->db->trans_start(); # Starting Transaction
            $this->db->insert('session_budget', $data);
            $id        = $this->db->insert_id();
            $message   = 'INSERT_session_budget' . " On id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->MY_Model->log($message, $record_id, $action);
            $this->db->trans_complete(); # Completing transaction



            $insert_id = $this->income_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/updater/budget');
        }

        $staff_list      = $this->expense_model->staffget();
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $data['staff_list'] = $staff_list;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $income_result       = $this->income_model->get();
        $data['incomelist']  = $income_result;
        $incomeHead          = $this->incomehead_model->get();
        $data['incheadlistt'] = $incomeHead;

        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;
        $category_resultt      = $this->expensehead_model->getcategory();
        $data['categorylistt'] = $category_resultt;
 
   


        $sess=     $this->setting_model->getCurrentSession();

        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session', $sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat = $query->result();
        $data['tabdat']  =$tabdat;


        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'expense');
        $this->db->where('session', $sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat1 = $query->result();
        $data['tabdat1']  =$tabdat1;
    


    


        $this->load->view('layout/header', $data);
        $this->load->view('admin/budget/budget', $data);
        $this->load->view('layout/footer', $data);



    }



    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('income', 'can_delete')) {
            access_denied();
        }
      
       

        $this -> db -> where('id', $id);
        $this -> db -> delete('session_budget');
        $this->load->model('MY_Model');
        $message   = 'Delete_session_budget' . " On id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->load->model('MY_Model');
        $this->MY_Model->log($message, $record_id, $action);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        redirect('admin/updater/budget');

    }



    public function edit($id)
    {




        if (!$this->rbac->hasPrivilege('income', 'can_edit')) {
            access_denied();
        }
       
       

        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/updater');
  


        $staff_list      = $this->expense_model->staffget();
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $data['staff_list'] = $staff_list;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $income_result       = $this->income_model->get();
        $data['incomelist']  = $income_result;
        $incomeHead          = $this->incomehead_model->get();
        $data['incheadlistt'] = $incomeHead;
        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;
        $category_resultt      = $this->expensehead_model->getcategory();
        $data['categorylistt'] = $category_resultt;


     
        $this->db->select()->from('session_budget');
        $this->db->where('id`', $id);
        $query = $this->db->get();
        $fetch = $query->row();
        $data['fetch']  =$fetch;
      
//   if($fetch->expense_income == "income"){
    $this->db->select()->from('session_budget');
    $this->db->where('expense_income`', 'income');
    
    $this->db->order_by('session_budget.id', 'desc');
    $query = $this->db->get();
    $tabdat = $query->result();


    $sess=     $this->setting_model->getCurrentSession();

    $this->db->select()->from('session_budget');
    $this->db->where('expense_income`', 'income');
    $this->db->where('session', $sess);
    $this->db->order_by('session_budget.id', 'desc');
    $query = $this->db->get();
    $tabdat = $query->result();
    
    $data['tabdat']  =$tabdat;

    $this->db->select()->from('session_budget');
    $this->db->where('expense_income`', 'expense');
    $this->db->where('session', $sess);
    $this->db->order_by('session_budget.id', 'desc');
    $query = $this->db->get();
    $tabdat1 = $query->result();
    $data['tabdat1']  =$tabdat1;



    $this->db->select('head')->from('session_budget');
    $this->db->where('expense_income`', 'income');
    $this->db->where('session',$sess);
    $this->db->order_by('session_budget.id', 'desc');
    $check = $this->db->get();
    $check = $check->result_array();

    $arr = array_map (function($value){
        return $value['head'];
    } , $check);
 

     $this->db->select('head')->from('session_budget');
     $this->db->where('expense_income`', 'expense');
     $this->db->where('session',$sess);
     $this->db->order_by('session_budget.id', 'desc');
     $check2 = $this->db->get();
     $check2 = $check2->result_array();

     $arr2 = array_map (function($value){
         return $value['head'];
     } , $check2);
    
     $data['arr']  = $arr ;
     $data['arr2']  =$arr2;



    $this->load->view('layout/header', $data);
    $this->load->view('admin/budget/editincomebudget', $data);
    $this->load->view('layout/footer', $data);
  }

      
    


    public function budgeticomeupdate()
    { 

        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->form_validation->set_rules('inc_head_idd', $this->lang->line('income_head').' category', 'trim|required|xss_clean');
        $this->form_validation->set_rules('inc_head_id', $this->lang->line('income_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
    
        if ($this->form_validation->run() == true) {

          

            $data = array(
                'session' => $this->input->post('popup_session'),
                'category_id' => $this->input->post('inc_head_idd'),
                'expense_income'	 => $this->input->post('budget_type'),
                'head' => $this->input->post('inc_head_id'),
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'description'        => $this->input->post('description'),
                
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('session_budget', $data);
            $this->load->model('MY_Model');
            $message   = 'Update_session_budget' . " On id " . $this->input->post('id');
            $action    = "Update";
            $record_id = $this->input->post('id');
            $this->MY_Model->log($message, $record_id, $action);

          

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/updater/budget');
        }

        $staff_list      = $this->expense_model->staffget();
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $data['staff_list'] = $staff_list;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $income_result       = $this->income_model->get();
        $data['incomelist']  = $income_result;
        $incomeHead          = $this->incomehead_model->get();
        $data['incheadlistt'] = $incomeHead;
        $sess=     $this->setting_model->getCurrentSession();

        $category_resultt      = $this->incomehead_model->getcategory();
        $data['incheadlist'] = $category_resultt;
        $category_resultt      = $this->expensehead_model->getcategory();
        $data['categorylistt'] = $category_resultt;

        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session', $sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat = $query->result();
        $data['tabdat']  =$tabdat;


        $this->db->select()->from('session_budget');
        $this->db->where('expense_income`', 'expense');
        $this->db->where('session', $sess);
        $this->db->order_by('session_budget.id', 'desc');
        $query = $this->db->get();
        $tabdat1 = $query->result();
        $data['tabdat1']  =$tabdat1;
    



        $this->db->select('head')->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session',$sess);
        $this->db->order_by('session_budget.id', 'desc');
        $check = $this->db->get();
        $check = $check->result_array();
    
        $arr = array_map (function($value){
            return $value['head'];
        } , $check);
     
    
         $this->db->select('head')->from('session_budget');
         $this->db->where('expense_income`', 'expense');
         $this->db->where('session',$sess);
         $this->db->order_by('session_budget.id', 'desc');
         $check2 = $this->db->get();
         $check2 = $check2->result_array();
    
         $arr2 = array_map (function($value){
             return $value['head'];
         } , $check2);
        
         $data['arr']  = $arr ;
         $data['arr2']  =$arr2;



        $this->load->view('layout/header', $data);
        $this->load->view('admin/budget/budget', $data);
        $this->load->view('layout/footer', $data);



    }

    public function budgetreport()
    {  
        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
   


//Data of Income HEad Categopry 
$income_category      = $this->incomehead_model->getcategory();
$income= array();
foreach($income_category as $category){
    $income[$category['title']] = array();

  
      $this->db->select()->from('income_head');
        $this->db->where('head_category', $category['id']);
        $this->db->order_by('id');
        $query = $this->db->get();
        $incomeHead = $query->result();
    

        $income[$category['title']] = $incomeHead;
     
}

$data['income']  = $income;
//Data of Expense HEad Categopry 
$expense_category      = $this->expensehead_model->getcategory();
$expense= array();
foreach($expense_category as $category){
    $expense[$category['title']] = array();

  
      $this->db->select()->from('expense_head');
        $this->db->where('head_category', $category['id']);
        $this->db->order_by('id');
        $query = $this->db->get();
        $incomeHead = $query->result();
    

        $expense[$category['title']]= $incomeHead;
     
}
// echo "<pre>"
// ;print_r($expense);die;
$data['expense']  = $expense;


$data['session_id']  = $this->setting_model->getCurrentSession();
$data['session_name']  = $this->setting_model->getCurrentSessionName();


$filter = explode("-",$this->setting_model->getCurrentSessionName());

$data['filter1'] = intval($filter[0]); 
$data['filter2'] = intval($filter[0])+ 1; 


if(isset($_POST['quarter'])){
    $dates = explode("-",$this->input->post('quarter'));   


$data['quater'] =  $dates[2];
$data['startDate'] = str_replace("/","-",$dates[0]);
$data['endDate'] =  str_replace("/","-",$dates[1]);
   
   
}else{
    $data['quater'] = '1st Quater';
    $data['startDate'] = str_replace("/","-",'1/4/2024');
    $data['endDate'] = str_replace("/","-",'30/6/2024');

    

}

$data['reselect'] = $this->input->post('quarter');



        $this->load->view('layout/header', $data);
        $this->load->view('admin/budget/budgetreport', $data);
        $this->load->view('layout/footer', $data);
    }


    public function incdis()
    {  
    
        $sess = $this->input->post('incdis');
 
        $this->db->select('head')->from('session_budget');
        $this->db->where('expense_income`', 'income');
        $this->db->where('session',$sess);
        $this->db->order_by('session_budget.id', 'desc');
        $check = $this->db->get();
        $check = $check->result_array();
    
        $arr = array_map (function($value){
            return $value['head'];
        } , $check);
     
        print_r($arr);
      

    }


    public function expdis()
    {  
        $sess = $this->input->post('incdis');

        $this->db->select('head')->from('session_budget');
        $this->db->where('expense_income`', 'expense');
        $this->db->where('session',$sess);
        $this->db->order_by('session_budget.id', 'desc');
        $check2 = $this->db->get();
        $check2 = $check2->result_array();
   
        $arr2 = array_map (function($value){
            return $value['head'];
        } , $check2);
        print_r($arr2);
}

}
