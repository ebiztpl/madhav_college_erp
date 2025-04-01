<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        $this->current_session      = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month          = $this->setting_model->getStartMonth();
        $this->db_default = $this->load->database('default', true);
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('admin');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    // public function getlist()
    // {

    //     $default_db = $this->db_default->database;
    //     $sql        = "SELECT table0.* FROM `$default_db`.`multi_branch` table0";
    //     $this->datatables->query($sql)
    //         ->searchable('branch_name,hostname,username,`database_name`')
    //         ->orderable('branch_name,hostname,username,`database_name`')
    //         ->query_where_enable(false);
    //     return $this->datatables->generate('json');
    // }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('admin');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('admin', $data);
        } else {
            $this->db->insert('admin', $data);
        }
    }

    public function checkLogin($data)
    {
        $this->db->select('id, username, password');
        $this->db->from('admin');
        $this->db->where('email', $data['username']);
        $this->db->where('password', MD5($data['password']));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function read_user_information($email)
    {
        $condition = "email =" . "'" . $email . "'";
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function readByEmail($email)
    {
        $condition = "email =" . "'" . $email . "'";
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function updateVerCode($data)
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('admin', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdminByCode($ver_code)
    {
        $condition = "verification_code =" . "'" . $ver_code . "'";
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function change_password($data)
    {
        $condition = "id =" . "'" . $data['id'] . "'";
        $this->db->select('password');
        $this->db->from('admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkOldPass($data)
    {
        $this->db->where('id', $data['user_id']);
        $this->db->where('email', $data['user_email']);
        $query = $this->db->get('staff');

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveNewPass($data)
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('staff', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function saveForgotPass($data)
    {
        $this->db->where('email', $data['email']);
        $query = $this->db->update('admin', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function addReceipt($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('fee_receipt_no', $data);
        } else {
            $this->db->insert('fee_receipt_no', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
    }

    public function getMonthlyCollection()
    {
        $data        = explode("-", $this->current_session_name);
        $data_first  = $data[0];
        $data_second = substr($data_first, 0, 2) . $data[1];
        $this->start_month;
        $sql   = "SELECT SUM(amount+amount_fine-amount_discount) as amount,MONTH(date) as month ,YEAR(date) as year FROM student_fees where YEAR(date) BETWEEN " . $this->db->escape($data_first) . " and " . $this->db->escape($data_second) . " GROUP BY MONTH(date)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getMonthlyExpense()
    {
        $data        = explode("-", $this->current_session_name);
        $data_first  = $data[0];
        $data_second = substr($data_first, 0, 2) . $data[1];
        $this->start_month;
        $sql   = "SELECT SUM(amount) as amount,MONTH(date) as month ,YEAR(date) as year FROM expenses where YEAR(date) BETWEEN " . $this->db->escape($data_first) . " and " . $this->db->escape($data_second) . " GROUP BY MONTH(date)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCollectionbyDay($date)
    {
        $sql   = 'SELECT SUM(amount+amount_fine-amount_discount) as amount FROM student_fees where date=' . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getExpensebyDay($date)
    {
        $sql = 'SELECT SUM(amount) as amount FROM expenses where date=' . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAllEnquiryCount($start_date, $end_date)
    {
        $condition = " date_format(date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        return $this->db->select("SUM(CASE WHEN status = 'won' THEN 1  ELSE 0 END) AS 'complete',SUM(CASE WHEN status = 'active' THEN 1  ELSE 0 END) AS 'active',SUM(CASE WHEN status = 'passive' THEN 1  ELSE 0 END) AS 'passive',SUM(CASE WHEN status = 'dead' THEN 1  ELSE 0 END) AS 'dead',SUM(CASE WHEN status = 'lost' THEN 1  ELSE 0 END) AS 'lost',count(*) as total")->from('enquiry')->where($condition)->get()->row_array();
    }




//  ----------------------------------------------- Multibranch_model--------------------------------------------------------


public function getSchoolCurrentSessions()
{
    $db_array=[];
    $default_db = $this->db_default->database;
    $this->db_default->select('sch_settings.start_month,sch_settings.name,sch_settings.id,sch_settings.session_id,sessions.session');
    $this->db_default->from('sch_settings');
    $this->db_default->join('sessions', 'sessions.id = sch_settings.session_id');
    $this->db_default->order_by('sch_settings.id');
    $query = $this->db_default->get();

    $res = $query->row();
    $res->name = $this->lang->line('home_branch');
  
    $db_array[$default_db]=$res;

    // =============================
    $branches = $this->gett();
    $is_branch_available=false;
    if (!empty($branches)) {
    $is_branch_available=true;

        foreach ($branches as $branch_key => $branch_value) {
        // echo  $branch_value->id;die;
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);
            $db_dynamic_name   = $db_dynamic->database;
           
            //=============================

            $db_dynamic->select('sch_settings.start_month,sch_settings.name,sch_settings.id,sch_settings.session_id,sessions.session');
            $db_dynamic->from('sch_settings');
            $db_dynamic->join('sessions', 'sessions.id = sch_settings.session_id');
            $db_dynamic->order_by('sch_settings.id');
            $query = $db_dynamic->get();
            $res = $query->row();

            $db_array[$db_dynamic_name]=$res;
            //=============================

        }
      
    }
    return $db_array;

}

/*
This function is used to get branch based on id
*/
public function gett($id = null, $verified = false)
{
    $this->db_default->select()->from('multi_branch');
    if ($verified) {
        $this->db_default->where('is_verified', $verified);
    }
    if ($id != null) {
        $this->db_default->where('id', $id);
    } else {
        $this->db_default->order_by('id');
    }
    $query = $this->db_default->get();
    if ($id != null) {
        return $query->row();
    } else {
        return $query->result();
    }
}

/*
This function is used to get branch list
*/
public function getlist()
{

    $default_db = $this->db_default->database;
    $sql        = "SELECT table0.* FROM `$default_db`.`multi_branch` table0";
    $this->datatables->query($sql)
        ->searchable('branch_name,hostname,username,`database_name`')
        ->orderable('branch_name,hostname,username,`database_name`')
        ->query_where_enable(false);
    return $this->datatables->generate('json');
}

/*
This function is used to verify branch
*/
public function verify_branch($database)
{
    $config['hostname'] = $database['hostname'];
    $config['username'] = $database['username'];
    $config['password'] = $database['password'];
    $config['database'] = $database['database_name'];
    $config['dbdriver'] = 'mysqli';
    $config['dbprefix'] = "";
    $config['pconnect'] = false;
    $config['cache_on'] = false;
    $config['cachedir'] = "";
    $config['char_set'] = "utf8";
    $config['autoinit'] = false;
    $config['db_debug'] = false;
    $config['dbcollat'] = "utf8_general_ci";

   

    try {
        $db_verify = $this->load->database($config, true);
        $error     = $db_verify->error();

        if ($error['code']) {
          
            return ['status'=>false,'message'=>$error['message']];

            
        }

        $db_verify->select('sch_settings.base_url');
        $db_verify->from('sch_settings');
        $query = $db_verify->get();
    

          return ['status'=>true,'message'=>'','result'=>$query->row()];

    } catch (Exception $e) {
         return ['status'=>false,'message'=> $this->lang->line('something_went_wrong')];
    }

}

/*
This function is used to get brancl list
*/
public function getDisprove()
{
    $this->db_default->select()->from('multi_branch');
    $this->db_default->where(array('branch_name' => null));
    $query = $this->db_default->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

public function getName($database)
{

    $config['hostname'] = $database['hostname'];
    $config['username'] = $database['username'];
    $config['password'] = $database['password'];
    $config['database'] = $database['database_name'];
    $config['dbdriver'] = 'mysqli';
    $config['dbprefix'] = "";
    $config['pconnect'] = false;
    $config['cache_on'] = false;
    $config['cachedir'] = "";
    $config['char_set'] = "utf8";
    $config['autoinit'] = false;
    $config['db_debug'] = false;
    $config['dbcollat'] = "utf8_general_ci";

    try {
        $db_verify = $this->load->database($config, true);
        $error     = $db_verify->error();
        if ($error['code']) {
            return false;
        }

        $db_verify->select('sch_settings.name');
        $db_verify->from('sch_settings');
        $query = $db_verify->get();
        return $query->row();

    } catch (Exception $e) {
        return false;
    }

}

/*
This function is used to add or update branch
*/
public function addd($data, $setting, $purchase_code, $update_data = false)
{
    if ($update_data) {
        $this->db_default->where('id', $data['id']);
        $this->db_default->update('multi_branch', $data);
    } else {
    //    $response = $this->auth->multiupdate($setting->base_url, $purchase_code);
$response = json_encode(array('status'=>1));
        if ($response) {
            $response = json_decode($response);
            if (!$response->status) {

                $response = json_encode($response);
                return $response;
            } else {
//=====
// echo 'hritk';
                $this->db_default->trans_start();
                $this->db_default->trans_strict(false);
                $data['branch_url']=$setting->base_url;
                if (isset($data['id'])) {
                    $this->db_default->where('id', $data['id']);
                    $this->db_default->update('multi_branch', $data);
                    $insert_id = $data['id'];
                } else {
                    $this->db_default->insert('multi_branch', $data);
                    $insert_id = $this->db_default->insert_id();

                }
                

                $this->db_default->trans_complete();

                if ($this->db_default->trans_status() === false) {

                    $this->db_default->trans_rollback();
                    return false;
                } else {

                    $this->db_default->trans_commit();
                    $response->{"insert_id"} = $insert_id;
                    $response                = json_encode($response);
                    return $response;

                }
                //=======
            }
        }
    }

}

/*
This function is used to update branch
*/
public function updateSchoolBranch($update_array)
{
    $this->db_default->update_batch('multi_branch', $update_array, 'id');
}

/*
This function is used to remove branch
*/




// -------------------------Multibranch Common Modal----------------------------


    



// public function getStudentCount($school_array = [])
// {
//     $results = [];
//     //===================

//     $default_db = $this->db_default->database;
//     $current_db = $school_array[$default_db];
//     $school         = [];
//     $school['name'] = $current_db->name;
//     $this->db_default->join('student_session', 'student_session.student_id = students.id');
//     $this->db_default->join('classes', 'student_session.class_id = classes.id');
//     $this->db_default->join('sections', 'sections.id = student_session.section_id');
//     $this->db_default->join('categories', 'students.category_id = categories.id', 'left');
//     $this->db_default->join('users', 'users.user_id = students.id', 'left');
//     $this->db_default->where('student_session.session_id', $current_db->session_id);
//     $this->db_default->where('users.role', 'student');
//     $this->db_default->where('students.is_active', 'yes');
//     $school['total_student'] = $this->db_default->count_all_results('students');
//     $school['db_name']       = $default_db;
//     $school['session']       = $current_db->session;
//     //====================

//     $results[$default_db] = $school;

//     $condition = array();
//     // $this->load->model("multibranch_model");
//     //=============================
//     $branches            = $this->gett();
//     $is_branch_available = false;
//     if (!empty($branches)) {
//         $is_branch_available = true;
//         foreach ($branches as $branch_key => $branch_value) {

//             $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

//             //===================
//             $db_dynamic_name = $db_dynamic->database;

//             $current_db     = $school_array[$db_dynamic_name];
//             $school         = [];
//             $school['name'] = $current_db->name;

//             $db_dynamic->join('student_session', 'student_session.student_id = students.id');
//             $db_dynamic->join('classes', 'student_session.class_id = classes.id');
//             $db_dynamic->join('sections', 'sections.id = student_session.section_id');
//             $db_dynamic->join('categories', 'students.category_id = categories.id', 'left');
//             $db_dynamic->join('users', 'users.user_id = students.id', 'left');
//             $db_dynamic->where('student_session.session_id', $current_db->session_id);
//             $db_dynamic->where('users.role', 'student');
//             $db_dynamic->where('students.is_active', 'yes');
//             $school['total_student']   = $db_dynamic->count_all_results('students');
//             $school['db_name']         = $db_dynamic_name;
//             $school['session']         = $current_db->session;
//             $results[$db_dynamic_name] = $school;
//             //====================

//         }

//     }
//     //=========================================
//     return $results;
// }

public function getStudentCount($school_array = [])
{
    $results = [];
    //===================
   
  $feesdat =  $this->getCurrentSessionStudentFees($school_array);



    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];


  

    $school         = [];
    $school['name'] = $current_db->name;

  

    $this->db_default->join('student_session', 'student_session.student_id = students.id');
    $this->db_default->join('classes', 'student_session.class_id = classes.id');
    $this->db_default->join('sections', 'sections.id = student_session.section_id');
    $this->db_default->join('categories', 'students.category_id = categories.id', 'left');
    $this->db_default->join('users', 'users.user_id = students.id', 'left');
    $this->db_default->where('student_session.session_id', $current_db->session_id);
    $this->db_default->where('users.role', 'student');
    $this->db_default->where('students.is_active', 'yes');
    $school['total_student'] = $this->db_default->count_all_results('students');
    $school['db_name']       = $default_db;
    $school['session']       = $current_db->session;
    //====================


    $default_db = $this->db_default->database;
    $this->db_default->select('*');
    $this->db_default->from('classes');
     $queryy = $this->db_default->get();         
    $resultt = $queryy->result();   

    $aray = [];
   
  foreach($resultt as $res){
     $aray[$res->class] = array();  // Blank Array of class with index as class name
     $ab = array();
   
     $default_db = $this->db_default->database;
     $this->db_default->select('*');
     $this->db_default->where('class_id', $res->id);
     $this->db_default->from('class_sections');
     $queryy = $this->db_default->get();         
     $abb = $queryy->result();   



  foreach($abb as $ab){

  
    $this->db_default->select('*');
    $this->db_default->where('id', $ab->section_id);
   $this->db_default->from('sections');
   
   $queryy = $this->db_default->get();         
   $section = $queryy->row();   

   $currentses = $this->setting_model->getCurrentSession();
  $this->db_default->select('*');
  $this->db_default->where('section_id',$ab->class_id);
  $this->db_default->where('section_id', $ab->section_id);
  $this->db_default->where('session_id', $currentses);
  $this->db_default->from('student_session');
  $queryy = $this->db_default->get(); 
  


  $count = $queryy->num_rows();   


  $total_fees    = 0;
  $total_paid    = 0;
  $total_balance = 0;

//   foreach ($school_array as $_branch_key => $_branch_value) {

//   if (!empty($feesdat[$_branch_key])) {

      foreach ($feesdat[$default_db] as $sch_fee_key => $sch_fee_value) {

if(($section->section  == $sch_fee_value->section) &&  ($sch_fee_value->class == $res->class)){
          
          $total_fees += $sch_fee_value->fee_amount;
          if (isJSON($sch_fee_value->amount_detail)) {
              $amount_paid_array = json_decode($sch_fee_value->amount_detail);
              foreach ($amount_paid_array as $amount_paid_key => $amount_paid_value) {
                  $total_paid += ($amount_paid_value->amount + $amount_paid_value->amount_discount);
              }

          }
      }

    }

//     }
//   }

//   echo "<pre>";
//   print_r($feesdat[$default_db]);die;

   $sec =   array('id' => $ab->id??'',
   'class_id' => $ab->class_id??'',
   'section_id' => $ab->section_id??'',
   'section' => $section->section??'',
   'count' => $count ,
   'total_fees' => $total_fees ,
   'total_paid' => $total_paid ,
   'total_balance' => $total_balance ,
);

   $aray[$res->class][] = $sec ;

  }
    



  

  }

    // echo "<pre>";
    // print_r($aray);die;



    $school['data']= $aray;



    $results[$default_db] = $school;

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;

            $current_db     = $school_array[$db_dynamic_name];
            $school         = [];
            $school['name'] = $current_db->name;

            $db_dynamic->join('student_session', 'student_session.student_id = students.id');
            $db_dynamic->join('classes', 'student_session.class_id = classes.id');
            $db_dynamic->join('sections', 'sections.id = student_session.section_id');
            $db_dynamic->join('categories', 'students.category_id = categories.id', 'left');
            $db_dynamic->join('users', 'users.user_id = students.id', 'left');
            $db_dynamic->where('student_session.session_id', $current_db->session_id);
            $db_dynamic->where('users.role', 'student');
            $db_dynamic->where('students.is_active', 'yes');
            $school['total_student']   = $db_dynamic->count_all_results('students');
            $school['db_name']         = $db_dynamic_name;
            $school['session']         = $current_db->session;
           
            //====================







// cutom 

$db_dynamic->select('*');
$db_dynamic->from('classes');
 $queryy = $db_dynamic->get();         
$resultt = $queryy->result();   

$aray = [];

foreach($resultt as $res){
 $aray[$res->class] = array();  // Blank Array of class with index as class name
 $ab = array();


 $db_dynamic->select('*');
 $db_dynamic->where('class_id', $res->id);
 $db_dynamic->from('class_sections');
 $queryy = $db_dynamic->get();         
 $abb = $queryy->result();   



foreach($abb as $ab){


$db_dynamic->select('*');
$db_dynamic->where('id', $ab->section_id);
$db_dynamic->from('sections');

$queryy = $db_dynamic->get();         
$section = $queryy->row();   

$currentses = $this->setting_model->getCurrentSession();
$db_dynamic->select('*');
$db_dynamic->where('section_id',$ab->class_id);
$db_dynamic->where('section_id', $ab->section_id);
$db_dynamic->where('session_id', $currentses);
$db_dynamic->from('student_session');
$queryy = $db_dynamic->get(); 



$count = $queryy->num_rows();   


$total_fees    = 0;
$total_paid    = 0;
$total_balance = 0;

//   foreach ($school_array as $_branch_key => $_branch_value) {

//   if (!empty($feesdat[$_branch_key])) {

    foreach ($feesdat[$db_dynamic_name] as $sch_fee_key => $sch_fee_value) {

if(($section->section??''  == $sch_fee_value->section??0) &&  ($sch_fee_value->class == $res->class)){

        $total_fees += $sch_fee_value->fee_amount;
        if (isJSON($sch_fee_value->amount_detail)) {
            $amount_paid_array = json_decode($sch_fee_value->amount_detail);
            foreach ($amount_paid_array as $amount_paid_key => $amount_paid_value) {
                $total_paid += ($amount_paid_value->amount + $amount_paid_value->amount_discount);
            }

        }
    }

  }

//     }
//   }





$sec =   array('id' => $ab->id??'',
'class_id' => $ab->class_id??'',
'section_id' => $ab->section_id??'',
'section' => $section->section??'',
'count' => $count ,
'total_fees' => $total_fees ,
'total_paid' => $total_paid ,
'total_balance' => $total_balance ,
);

$aray[$res->class][] = $sec ;

}


$school['data']= $aray;


$results[$db_dynamic_name] = $school;
}
///-----------








        }

    }
    //=========================================
    return $results;
}
/*
This function is used to get student fees based on active current session
*/
public function getCurrentSessionStudentFees($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;

    $current_db = $school_array[$default_db];

    $school = [];

    $sql = "SELECT table0.*,$default_db.fee_session_groups.fee_groups_id,$default_db.fee_session_groups.session_id,$default_db.fee_groups.name,$default_db.fee_groups.is_system,$default_db.fee_groups_feetype.amount as `fee_amount`,$default_db.fee_groups_feetype.id as fee_groups_feetype_id,$default_db.student_fees_deposite.id as `student_fees_deposite_id`,$default_db.student_fees_deposite.amount_detail,$default_db.students.id as student_id,$default_db.classes.class,$default_db.sections.section FROM $default_db.`student_fees_master` table0 INNER JOIN $default_db.fee_session_groups on $default_db.fee_session_groups.id=table0.fee_session_group_id INNER JOIN $default_db.student_session on student_session.id=table0.student_session_id INNER JOIN $default_db.students on $default_db.students.id=student_session.student_id inner join $default_db.classes on student_session.class_id=$default_db.classes.id INNER JOIN $default_db.sections on $default_db.sections.id=student_session.section_id inner join $default_db.fee_groups on $default_db.fee_groups.id=$default_db.fee_session_groups.fee_groups_id INNER JOIN $default_db.fee_groups_feetype on $default_db.fee_session_groups.id=$default_db.fee_groups_feetype.fee_session_group_id LEFT JOIN $default_db.student_fees_deposite on $default_db.student_fees_deposite.student_fees_master_id=table0.id and $default_db.student_fees_deposite.fee_groups_feetype_id=$default_db.fee_groups_feetype.id WHERE $default_db.student_session.session_id='" . $current_db->session_id . "' and  $default_db.fee_session_groups.session_id='" . $current_db->session_id . "'";
    
    $query  = $this->db->query($sql);
    $result = $query->result();

    //====================

    $results[$default_db] = $result;

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;

            $current_db = $school_array[$db_dynamic_name];
            $school     = [];

            $sql = "SELECT table$branch_value->id.*,$db_dynamic_name.fee_session_groups.fee_groups_id,$db_dynamic_name.fee_session_groups.session_id,$db_dynamic_name.fee_groups.name,$db_dynamic_name.fee_groups.is_system,$db_dynamic_name.fee_groups_feetype.amount as `fee_amount`,$db_dynamic_name.fee_groups_feetype.id as fee_groups_feetype_id,$db_dynamic_name.student_fees_deposite.id as `student_fees_deposite_id`,$db_dynamic_name.student_fees_deposite.amount_detail,$db_dynamic_name.students.id as student_id,$db_dynamic_name.classes.class,$db_dynamic_name.sections.section FROM $db_dynamic_name.`student_fees_master` table$branch_value->id INNER JOIN $db_dynamic_name.fee_session_groups on $db_dynamic_name.fee_session_groups.id=table$branch_value->id.fee_session_group_id INNER JOIN $db_dynamic_name.student_session on student_session.id=table$branch_value->id.student_session_id INNER JOIN $db_dynamic_name.students on $db_dynamic_name.students.id=student_session.student_id inner join $db_dynamic_name.classes on student_session.class_id=$db_dynamic_name.classes.id INNER JOIN $db_dynamic_name.sections on $db_dynamic_name.sections.id=student_session.section_id inner join $db_dynamic_name.fee_groups on $db_dynamic_name.fee_groups.id=$db_dynamic_name.fee_session_groups.fee_groups_id INNER JOIN $db_dynamic_name.fee_groups_feetype on $db_dynamic_name.fee_session_groups.id=$db_dynamic_name.fee_groups_feetype.fee_session_group_id LEFT JOIN $db_dynamic_name.student_fees_deposite on $db_dynamic_name.student_fees_deposite.student_fees_master_id=table$branch_value->id.id and $db_dynamic_name.student_fees_deposite.fee_groups_feetype_id=$db_dynamic_name.fee_groups_feetype.id WHERE $db_dynamic_name.student_session.session_id='" . $current_db->session_id . "' and  $db_dynamic_name.fee_session_groups.session_id='" . $current_db->session_id . "'";

            $query  = $this->db->query($sql);
            $result = $query->result();

            $results[$db_dynamic_name] = $result;
            //====================

        }

    }
    //=========================================
    return $results;
}

/*
This function is used to get staff list
*/
public function getStaff($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school         = [];
    $school['name'] = $current_db->name;

    $this->db_default->join('staff_designation', "staff_designation.id = staff.designation", "left");
    $this->db_default->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
    $this->db_default->join('roles', "roles.id = staff_roles.role_id", "left");
    $this->db_default->join('department', "department.id = staff.department", "left");
    $this->db_default->where('staff.is_active', 1);
    $school['total_staff'] = $this->db_default->count_all_results('staff');
    $school['db_name']     = $default_db;

    //====================

    $results[$default_db] = $school;

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;

            $current_db     = $school_array[$db_dynamic_name];
            $school         = [];
            $school['name'] = $current_db->name;

            $db_dynamic->join('staff_designation', "staff_designation.id = staff.designation", "left");
            $db_dynamic->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
            $db_dynamic->join('roles', "roles.id = staff_roles.role_id", "left");
            $db_dynamic->join('department', "department.id = staff.department", "left");
            $db_dynamic->where('staff.is_active', 1);

            $school['total_staff'] = $db_dynamic->count_all_results('staff');
            $school['db_name']     = $db_dynamic_name;

            $results[$db_dynamic_name] = $school;
            //====================
        }

    }
    //=========================================
    return $results;
}

/*
This function is used to get staff attendance based on date
*/
public function getStaffAttendance($school_array = [], $date)
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school['name'] = $current_db->name;
    $sql            = "select $default_db.staff_attendance.staff_attendance_type_id,$default_db.staff_attendance_type.type as `att_type`,$default_db.staff_attendance_type.key_value as `key`,$default_db.staff_attendance.remark,table0.name,table0.surname,table0.employee_id,table0.contact_no,table0.email,$default_db.roles.name as user_type,IFNULL($default_db.staff_attendance.date, 'xxx') as date, IFNULL($default_db.staff_attendance.id, 0) as attendence_id, table0.id as id from $default_db.`staff` table0  left join $default_db.staff_roles on (table0.id = $default_db.staff_roles.staff_id) left join $default_db.roles on ($default_db.roles.id = $default_db.staff_roles.role_id) left join $default_db.staff_attendance on (table0.id = $default_db.staff_attendance.staff_id) and $default_db.staff_attendance.date = " . $this->db->escape($date) . " left join $default_db.staff_attendance_type on $default_db.staff_attendance_type.id = $default_db.staff_attendance.staff_attendance_type_id  where table0.is_active = 1 ";

    $query  = $this->db->query($sql);
    $result = $query->result();

    //====================

    $results[$default_db] = $result;

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;

            $current_db = $school_array[$db_dynamic_name];

            $school['name'] = $current_db->name;
            $sql            = "select $db_dynamic_name.staff_attendance.staff_attendance_type_id,$db_dynamic_name.staff_attendance_type.type as `att_type`,$db_dynamic_name.staff_attendance_type.key_value as `key`,$db_dynamic_name.staff_attendance.remark,table$branch_value->id.name,table$branch_value->id.surname,table$branch_value->id.employee_id,table$branch_value->id.contact_no,table$branch_value->id.email,$db_dynamic_name.roles.name as user_type,IFNULL($db_dynamic_name.staff_attendance.date, 'xxx') as date, IFNULL($db_dynamic_name.staff_attendance.id, 0) as attendence_id, table$branch_value->id.id as id from $db_dynamic_name.`staff` table$branch_value->id  left join $db_dynamic_name.staff_roles on (table$branch_value->id.id = $db_dynamic_name.staff_roles.staff_id) left join $db_dynamic_name.roles on ($db_dynamic_name.roles.id = $db_dynamic_name.staff_roles.role_id) left join $db_dynamic_name.staff_attendance on (table$branch_value->id.id = $db_dynamic_name.staff_attendance.staff_id) and $db_dynamic_name.staff_attendance.date = " . $this->db->escape($date) . " left join $db_dynamic_name.staff_attendance_type on $db_dynamic_name.staff_attendance_type.id = $db_dynamic_name.staff_attendance.staff_attendance_type_id  where table$branch_value->id.is_active = 1 ";

            $query  = $this->db->query($sql);
            $result = $query->result();

            $results[$db_dynamic_name] = $result;
            //====================

        }

    }
    //=========================================
    return $results;
}

/*
This function is used to get offline admitted student list
*/
public function getOfflineStudentAdmissions($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];

    $school_arr         = sessionYearDetails($current_db->session, $current_db->start_month);
    $school_month_start = $school_arr['month_start'];
    $school_month_end   = $school_arr['month_end'];

    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;
    $this->db_default->join('student_session', 'student_session.student_id = students.id');
    $this->db_default->join('classes', 'student_session.class_id = classes.id');
    $this->db_default->join('sections', 'sections.id = student_session.section_id');
    $this->db_default->join('categories', 'students.category_id = categories.id', 'left');
    $this->db_default->join('users', 'users.user_id = students.id', 'left');
    $this->db_default->where('student_session.session_id', $current_db->session_id);
    $this->db_default->where('admission_date >=', $school_month_start);
    $this->db_default->where('admission_date <=', $school_month_end);
    $this->db_default->where('users.role', 'student');
    $school['offline_admission'] = $this->db_default->count_all_results('students');
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;

    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];
            $school_arr         = sessionYearDetails($db_dynamic_array->session, $db_dynamic_array->start_month);
            $school_month_start = $school_arr['month_start'];
            $school_month_end   = $school_arr['month_end'];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;
            $db_dynamic->join('student_session', 'student_session.student_id = students.id');
            $db_dynamic->join('classes', 'student_session.class_id = classes.id');
            $db_dynamic->join('sections', 'sections.id = student_session.section_id');
            $db_dynamic->join('categories', 'students.category_id = categories.id', 'left');
            $db_dynamic->join('users', 'users.user_id = students.id', 'left');
            $db_dynamic->where('student_session.session_id', $db_dynamic_array->session_id);
            $db_dynamic->where('admission_date >=', $school_month_start);
            $db_dynamic->where('admission_date <=', $school_month_end);
            $db_dynamic->where('users.role', 'student');
            $school['offline_admission'] = $db_dynamic->count_all_results('students');
            $school['db_name']         = $db_dynamic_name;

            $results[$db_dynamic_name] = $school;

            //====================
        }

    }
    //=========================================
    return $results;
}

/*
This function is used to get online admitted admission student list
*/
public function getOnlineStudentAdmissions($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school_arr         = sessionYearDetails($current_db->session, $current_db->start_month);
    $school_month_start = $school_arr['month_start'];
    $school_month_end   = $school_arr['month_end'];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;     
    $this->db_default->join('class_sections', 'online_admissions.class_section_id = class_sections.id');
    $this->db_default->where('admission_date >=', $school_month_start);
    $this->db_default->where('admission_date <=', $school_month_end);
    $school['online_admission'] = $this->db_default->count_all_results('online_admissions');
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;

    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];
            $school_arr         = sessionYearDetails($db_dynamic_array->session, $db_dynamic_array->start_month);
            $school_month_start = $school_arr['month_start'];
            $school_month_end   = $school_arr['month_end'];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;
            $db_dynamic->join('class_sections', 'online_admissions.class_section_id = class_sections.id');                $db_dynamic->where('admission_date >=', $school_month_start);
            $db_dynamic->where('admission_date <=', $school_month_end);
            $school['online_admission'] = $db_dynamic->count_all_results('online_admissions');
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================

        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get book
*/
public function getBooks($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];

    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;     
    $school['total_books'] = $this->db_default->count_all_results('books');
    $school['db_name']         = $default_db;

    $results[$default_db] = $school;

    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];            

    $school            = [];
    $school['name']    = $db_dynamic_array->name;
    $school['session'] = $db_dynamic_array->session;     
    $school['total_books'] = $db_dynamic->count_all_results('books');
    $school['db_name']     = $db_dynamic_name;
    $results[$db_dynamic_name] = $school;
    //====================

        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get library members
*/
public function getLibararyMembers($school_array = [])
{
    $results = [];
    //===================
    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;      
    $school['total_members'] = $this->db_default->count_all_results('libarary_members');
    $school['db_name']         = $default_db;

    $results[$default_db] = $school;
    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);  
            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];       

            $school            = [];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;    
            $school['total_members'] = $db_dynamic->count_all_results('libarary_members');
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================
        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get issue book
*/
public function getLibararyBookIssued($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;
    $this->db_default->where('is_returned',0);     
    $school['total_book_issued'] = $this->db_default->count_all_results('book_issues');
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;

    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name]; 
            $school            = [];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;
            $db_dynamic->where('is_returned',0);     
            $school['total_book_issued'] = $db_dynamic->count_all_results('book_issues');
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================
        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get alumni student
*/
public function getAlumniStudents($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;    
    $school['total_alumni_student'] = $this->db_default->count_all_results('alumni_students');
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;
    
    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];
            $school            = [];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;    
            $school['total_alumni_student'] = $db_dynamic->count_all_results('alumni_students');
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================
        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get user log detail
*/
public function getUserLog($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;    
    $school['total_userlog'] = $this->db_default->count_all_results('userlog');
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;
    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);  
            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];
            $school            = [];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;    
            $school['total_userlog'] = $db_dynamic->count_all_results('userlog');
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================
        }
    }
    //=========================================
    return $results;
}

/*
This function is used to get student transport fees
*/
public function getStudentTransportFees($school_array = [])
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;
     $this->db_default->select('student_transport_fees.*,route_pickup_point.fees,transport_feemaster.month,transport_feemaster.due_date ,transport_feemaster.fine_amount, transport_feemaster.fine_type,transport_feemaster.fine_percentage,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail`,students.id as `student_id`');
    $this->db_default->from('student_transport_fees');
    $this->db_default->join('transport_feemaster' ,'transport_feemaster.id =student_transport_fees.transport_feemaster_id');   
    $this->db_default->join('student_fees_deposite' ,'student_fees_deposite.student_transport_fee_id=student_transport_fees.id','LEFT');
    $this->db_default->join('student_session' ,'student_session.id= student_transport_fees.student_session_id'); 
    $this->db_default->join('classes' ,'classes.id= student_session.class_id');  
    $this->db_default->join('sections' ,'sections.id= student_session.section_id');  
    $this->db_default->join('students' ,'students.id=student_session.student_id');  
    $this->db_default->join('route_pickup_point' ,'route_pickup_point.id = student_transport_fees.route_pickup_point_id'); 
    $this->db_default->join('categories' ,'students.category_id = categories.id','LEFT');
    $q =$this->db_default->get();
    $total_fees=$q->result();     
    $school['total_fees_record'] = $total_fees;
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;
    
    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
            $is_branch_available = true;
            foreach ($branches as $branch_key => $branch_value) {

                    $school     = [];
                    $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

                    //===================
                    $db_dynamic_name = $db_dynamic->database;

                    $db_dynamic_array = $school_array[$db_dynamic_name];
                    $school            = [];
                    $school['name']    = $db_dynamic_array->name;
                    $school['session'] = $db_dynamic_array->session;

                    $db_dynamic->select('student_transport_fees.*,route_pickup_point.fees,transport_feemaster.month,transport_feemaster.due_date ,transport_feemaster.fine_amount, transport_feemaster.fine_type,transport_feemaster.fine_percentage,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail`,students.id as `student_id`');
                    $db_dynamic->from('student_transport_fees');
                    $db_dynamic->join('transport_feemaster' ,'transport_feemaster.id =student_transport_fees.transport_feemaster_id');   
                    $db_dynamic->join('student_fees_deposite' ,'student_fees_deposite.student_transport_fee_id=student_transport_fees.id','LEFT');
                    $db_dynamic->join('student_session' ,'student_session.id= student_transport_fees.student_session_id'); 
                    $db_dynamic->join('classes' ,'classes.id= student_session.class_id');  
                    $db_dynamic->join('sections' ,'sections.id= student_session.section_id');  
                    $db_dynamic->join('students' ,'students.id=student_session.student_id');  
                    $db_dynamic->join('route_pickup_point' ,'route_pickup_point.id = student_transport_fees.route_pickup_point_id');  
                    $db_dynamic->join('categories' ,'students.category_id = categories.id','LEFT');
                    $q=$db_dynamic->get();
                    $total_fees=$q->result();     
                    $school['total_fees_record'] = $total_fees;
                    $school['db_name']         = $db_dynamic_name;
                    $results[$db_dynamic_name] = $school;
                    //====================
            }
    }
    //=========================================
    return $results;
}

/*
This function is used to get payrol of staff from all branch based of month and year
*/
public function getStaffPayslipCount($school_array = [],$month,$year)
{
    $results = [];
    //===================

    $default_db = $this->db_default->database;
    $current_db = $school_array[$default_db];
    $school            = [];
    $school['name']    = $current_db->name;
    $school['session'] = $current_db->session;
    $this->db_default->select('staff_payslip.*,');
    $this->db_default->from('staff_payslip');
    $this->db_default->join('staff' ,'staff.id =staff_payslip.staff_id');   
    $this->db_default->where('staff_payslip.month' ,$month);   
    $this->db_default->where('staff_payslip.year' ,$year);        
    $q =$this->db_default->get();
    $total_fees=$q->result();     
    $school['total_payroll_record'] = $total_fees;
    $school['db_name']         = $default_db;
    $results[$default_db] = $school;
    //====================

    $condition = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {

            $school     = [];
            $db_dynamic = $this->load->database('branch_' . $branch_value->id, true);

            //===================
            $db_dynamic_name = $db_dynamic->database;
            $db_dynamic_array = $school_array[$db_dynamic_name];
            $school            = [];
            $school['name']    = $db_dynamic_array->name;
            $school['session'] = $db_dynamic_array->session;
            $db_dynamic->select('staff_payslip.*,');
            $db_dynamic->from('staff_payslip');
            $db_dynamic->join('staff' ,'staff.id =staff_payslip.staff_id');   
            $db_dynamic->where('staff_payslip.month' ,$month);  
            $db_dynamic->where('staff_payslip.year' ,$year);  
            $q=$db_dynamic->get();
            $total_fees=$q->result();     
            $school['total_payroll_record'] = $total_fees;
            $school['db_name']         = $db_dynamic_name;
            $results[$db_dynamic_name] = $school;
            //====================
        }
    }
    //=========================================
    return $results;
}




// --------------multi_student_fee_model-------------------------multi_student_fee_model-------------------------multi_student_fee_model-------------------------multi_student_fee_model-----------


    /*
    This function is used to get expense
    */
    public function getexpenselist()
    {
        $default_db = $this->db_default->database;
        $sql        = "SELECT table0.*,`$default_db`.`expense_head`.`exp_category`,'".$this->lang->line('home_branch')."' as branch_name FROM `$default_db`.`expenses` table0  JOIN `$default_db`.`expense_head` ON `table0`.`exp_head_id` = `expense_head`.`id` UNION ALL ";
        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches = $this->gett();
        $is_branch_available=false;
        if (!empty($branches)) {
        $is_branch_available=true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic   = $this->{'db_' . $branch_value->id}->database;
                $condition[]  = "SELECT table$branch_value->id.*,`$db_dynamic`.`expense_head`.`exp_category`,'$branch_value->branch_name' as branch_name FROM `$db_dynamic`.`expenses` table$branch_value->id  JOIN `$db_dynamic`.`expense_head` ON `table$branch_value->id`.`exp_head_id` = `expense_head`.`id`";
        //=============================
            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql= "SELECT * FROM ($sql) as tempTable";
      
        $this->datatables->query($sql)
            ->searchable('name,invoice_no,date,amount,exp_category')
            ->orderable('branch_name,name,note,invoice_no,date,exp_category,amount')
            ->query_where_enable(false);
        return $this->datatables->generate('json');
    }

    /*
    This function is used to get student fees 
    */
    public function getCurrentSessionStudentFeess()
    {
        $default_db = $this->db_default->database;
        $sql ="SELECT '$default_db' as db_name, '".$this->lang->line('home_branch')."' as branch_name, table0.*,`$default_db`.fee_session_groups.fee_groups_id,`$default_db`.fee_session_groups.session_id,`$default_db`.fee_groups.name,`$default_db`.fee_groups.is_system as `fee_groups_is_system`,`$default_db`.fee_groups_feetype.amount as `fee_amount`,`$default_db`.fee_groups_feetype.id as fee_groups_feetype_id,`$default_db`.student_fees_deposite.id as `student_fees_deposite_id`,`$default_db`.student_fees_deposite.amount_detail,`$default_db`.students.admission_no , `$default_db`.students.roll_no,`$default_db`.students.admission_date,`$default_db`.students.firstname,`$default_db`.students.middlename,  `$default_db`.students.lastname,`$default_db`.students.father_name,`$default_db`.students.image, `$default_db`.students.mobileno, `$default_db`.students.email ,`$default_db`.students.state ,   `$default_db`.students.city , `$default_db`.students.pincode ,classes.class,sections.section FROM `$default_db`.`student_fees_master` table0 INNER JOIN `$default_db`.fee_session_groups on `$default_db`.fee_session_groups.id=table0.fee_session_group_id INNER JOIN `$default_db`.student_session on `$default_db`.student_session.id=table0.student_session_id INNER JOIN `$default_db`.students on `$default_db`.students.id=`$default_db`.student_session.student_id inner join `$default_db`.classes on `$default_db`.student_session.class_id=`$default_db`.classes.id INNER JOIN `$default_db`.sections on `$default_db`.sections.id=`$default_db`.student_session.section_id inner join `$default_db`.fee_groups on `$default_db`.fee_groups.id=`$default_db`.fee_session_groups.fee_groups_id INNER JOIN `$default_db`.fee_groups_feetype on `$default_db`.fee_groups.id=`$default_db`.fee_groups_feetype.fee_groups_id LEFT JOIN `$default_db`.student_fees_deposite on `$default_db`.student_fees_deposite.student_fees_master_id=table0.id and `$default_db`.student_fees_deposite.fee_groups_feetype_id=`$default_db`.fee_groups_feetype.id WHERE `$default_db`.student_session.session_id='" . $this->current_session . "' and  `$default_db`.fee_session_groups.session_id='" . $this->current_session . "'  UNION ALL ";

        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches = $this->gett();
        $is_branch_available=false;
        if (!empty($branches)) {
        $is_branch_available=true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic   = $this->{'db_' . $branch_value->id}->database;
                $condition[]  = "SELECT '$db_dynamic' as db_name, '$branch_value->branch_name' as branch_name,table$branch_value->id.*,`$db_dynamic`.fee_session_groups.fee_groups_id,`$db_dynamic`.fee_session_groups.session_id,`$db_dynamic`.fee_groups.name,`$db_dynamic`.fee_groups.is_system as `fee_groups_is_system`,`$db_dynamic`.fee_groups_feetype.amount as `fee_amount`,`$db_dynamic`.fee_groups_feetype.id as fee_groups_feetype_id,`$db_dynamic`.student_fees_deposite.id as `student_fees_deposite_id`,`$db_dynamic`.student_fees_deposite.amount_detail,`$db_dynamic`.students.admission_no , `$db_dynamic`.students.roll_no,`$db_dynamic`.students.admission_date,`$db_dynamic`.students.firstname,`$db_dynamic`.students.middlename,  `$db_dynamic`.students.lastname,`$db_dynamic`.students.father_name,`$db_dynamic`.students.image, `$db_dynamic`.students.mobileno, `$db_dynamic`.students.email ,`$db_dynamic`.students.state ,   `$db_dynamic`.students.city , `$db_dynamic`.students.pincode ,classes.class,sections.section FROM `$db_dynamic`.`student_fees_master` table$branch_value->id INNER JOIN `$db_dynamic`.fee_session_groups on `$db_dynamic`.fee_session_groups.id=table$branch_value->id.fee_session_group_id INNER JOIN `$db_dynamic`.student_session on `$db_dynamic`.student_session.id=table$branch_value->id.student_session_id INNER JOIN  `$db_dynamic`.students on `$db_dynamic`.students.id=student_session.student_id INNER JOIN `$db_dynamic`.classes on student_session.class_id=classes.id INNER JOIN `$db_dynamic`.sections on sections.id=student_session.section_id INNER JOIN `$db_dynamic`.fee_groups on `$db_dynamic`.fee_groups.id=`$db_dynamic`.fee_session_groups.fee_groups_id INNER JOIN `$db_dynamic`.fee_groups_feetype on `$db_dynamic`.fee_groups.id=`$db_dynamic`.fee_groups_feetype.fee_groups_id LEFT JOIN `$db_dynamic`.student_fees_deposite on `$db_dynamic`.student_fees_deposite.student_fees_master_id=table$branch_value->id.id and `$db_dynamic`.student_fees_deposite.fee_groups_feetype_id=`$db_dynamic`.fee_groups_feetype.id WHERE `$db_dynamic`.student_session.session_id='" . $this->current_session . "' and  `$db_dynamic`.fee_session_groups.session_id='" . $this->current_session . "'";

        //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql=($is_branch_available) ? "SELECT * FROM ($sql) as tempTable" :$sql;
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    /*
    This function is used to get student deposit fees 
    */
    public function getFeesDepositeByIdArray($id_array = array())
    {
        $default_db = $this->db_default->database;       
        $array_implode_value =array_key_exists($default_db, $id_array) ? implode("','", $id_array[$default_db]) :0;
        $id_implode = $imp = "'" . $array_implode_value . "'";
        $sql ="SELECT '$default_db' as db_name, '".$this->lang->line('home_branch')."' as branch_name, table0.*,`$default_db`.fee_session_groups.fee_groups_id,`$default_db`.fee_session_groups.session_id,`$default_db`.fee_groups.name,`$default_db`.fee_groups_feetype.amount as `fee_amount`,`$default_db`.fee_groups_feetype.id as fee_groups_feetype_id,`$default_db`.student_fees_deposite.id as `student_fees_deposite_id`,`$default_db`.student_fees_deposite.amount_detail,`$default_db`.students.admission_no , `$default_db`.students.roll_no,`$default_db`.students.admission_date,`$default_db`.students.firstname,`$default_db`.students.middlename,  `$default_db`.students.lastname,`$default_db`.students.father_name,`$default_db`.students.image, `$default_db`.students.mobileno, `$default_db`.students.email ,`$default_db`.students.state ,   `$default_db`.students.city , `$default_db`.students.pincode ,`$default_db`.classes.class,`$default_db`.sections.section FROM `$default_db`.`student_fees_master` table0  INNER JOIN `$default_db`.fee_session_groups on `$default_db`.fee_session_groups.id=table0.fee_session_group_id INNER JOIN `$default_db`.student_session on `$default_db`.student_session.id=table0.student_session_id INNER JOIN `$default_db`.students on `$default_db`.students.id=`$default_db`.student_session.student_id inner join `$default_db`.classes on `$default_db`.student_session.class_id=`$default_db`.classes.id INNER JOIN `$default_db`.sections on `$default_db`.sections.id=`$default_db`.student_session.section_id inner join `$default_db`.fee_groups on `$default_db`.fee_groups.id=`$default_db`.fee_session_groups.fee_groups_id INNER JOIN `$default_db`.fee_groups_feetype on `$default_db`.fee_groups.id=`$default_db`.fee_groups_feetype.fee_groups_id  JOIN `$default_db`.student_fees_deposite on `$default_db`.student_fees_deposite.student_fees_master_id=table0.id and `$default_db`.student_fees_deposite.fee_groups_feetype_id=`$default_db`.fee_groups_feetype.id WHERE `$default_db`.student_session.session_id='" . $this->current_session . "' and  `$default_db`.fee_session_groups.session_id='" . $this->current_session . "' and `$default_db`.student_fees_deposite.id in (" . $id_implode . ")  UNION ALL ";     

        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches = $this->gett();
        $is_branch_available=false;
        if (!empty($branches)) {
        $is_branch_available=true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic   = $this->{'db_' . $branch_value->id}->database;
                $array_dy_namic_implode_value =array_key_exists($db_dynamic, $id_array) ? implode("','", $id_array[$db_dynamic]) :0;
                $id_implode_dynamic = $imp = "'" . $array_dy_namic_implode_value . "'";
                $condition[]  = "SELECT '$db_dynamic' as db_name, '$branch_value->branch_name' as branch_name, table$branch_value->id.*,`$db_dynamic`.fee_session_groups.fee_groups_id,`$db_dynamic`.fee_session_groups.session_id,`$db_dynamic`.fee_groups.name,`$db_dynamic`.fee_groups_feetype.amount as `fee_amount`,`$db_dynamic`.fee_groups_feetype.id as fee_groups_feetype_id,`$db_dynamic`.student_fees_deposite.id as `student_fees_deposite_id`,`$db_dynamic`.student_fees_deposite.amount_detail,`$db_dynamic`.students.admission_no , `$db_dynamic`.students.roll_no,`$db_dynamic`.students.admission_date,`$db_dynamic`.students.firstname,`$db_dynamic`.students.middlename,  `$db_dynamic`.students.lastname,`$db_dynamic`.students.father_name,`$db_dynamic`.students.image, `$db_dynamic`.students.mobileno, `$db_dynamic`.students.email ,`$db_dynamic`.students.state ,   `$db_dynamic`.students.city , `$db_dynamic`.students.pincode ,`$db_dynamic`.classes.class,`$db_dynamic`.sections.section FROM `$db_dynamic`.`student_fees_master` table$branch_value->id  INNER JOIN `$db_dynamic`.fee_session_groups on `$db_dynamic`.fee_session_groups.id=table$branch_value->id.fee_session_group_id INNER JOIN `$db_dynamic`.student_session on `$db_dynamic`.student_session.id=table$branch_value->id.student_session_id INNER JOIN `$db_dynamic`.students on `$db_dynamic`.students.id=`$db_dynamic`.student_session.student_id inner join `$db_dynamic`.classes on `$db_dynamic`.student_session.class_id=`$db_dynamic`.classes.id INNER JOIN `$db_dynamic`.sections on `$db_dynamic`.sections.id=`$db_dynamic`.student_session.section_id inner join `$db_dynamic`.fee_groups on `$db_dynamic`.fee_groups.id=`$db_dynamic`.fee_session_groups.fee_groups_id INNER JOIN `$db_dynamic`.fee_groups_feetype on `$db_dynamic`.fee_groups.id=`$db_dynamic`.fee_groups_feetype.fee_groups_id  JOIN `$db_dynamic`.student_fees_deposite on `$db_dynamic`.student_fees_deposite.student_fees_master_id=table$branch_value->id.id and `$db_dynamic`.student_fees_deposite.fee_groups_feetype_id=`$db_dynamic`.fee_groups_feetype.id WHERE `$db_dynamic`.student_session.session_id='" . $this->current_session . "' and  `$db_dynamic`.fee_session_groups.session_id='" . $this->current_session . "' and `$db_dynamic`.student_fees_deposite.id in (" . $id_implode_dynamic . ")";

        //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql=($is_branch_available) ? "SELECT * FROM ($sql) as tempTable" :$sql;
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;

    }


// --------------multi_payroll_model-------------------------------multi_payroll_model-------------------------------multi_payroll_model-----------------

    /*
    This function is used to get staff payroll from all branch based on date
    */
    public function getbetweenpayrollReport($start_date, $end_date)
    {
      $default_db = $this->db_default->database;
        $sql        = "SELECT  '".$this->lang->line('home_branch')."' as branch_name,table0.`employee_id`, table0.`name`, `$default_db`.`roles`.`name` as `user_type`, table0.`surname`, `$default_db`.`staff_designation`.`designation`, `$default_db`.`department`.`department_name` as `department`, `$default_db`.`staff_payslip`.* FROM `$default_db`.`staff` table0 INNER JOIN `$default_db`.`staff_payslip` ON `$default_db`.`staff_payslip`.`staff_id` = table0.`id` LEFT JOIN  `$default_db`.`staff_designation` ON table0.`designation` = `$default_db`.`staff_designation`.`id` LEFT JOIN  `$default_db`.`department` ON table0.`department` = `$default_db`.`department`.`id` LEFT JOIN  `$default_db`.`staff_roles` ON  `$default_db`.`staff_roles`.`staff_id` = table0.`id` LEFT JOIN  `$default_db`.`roles` ON  `$default_db`.`staff_roles`.`role_id` = `$default_db`.`roles`.`id` WHERE date_format( `$default_db`.staff_payslip.payment_date,'%Y-%m-%d') between ".$this->db->escape($start_date)." and  ".$this->db->escape($end_date)." UNION ALL ";
        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches = $this->gett();
        $is_branch_available=false;
        if (!empty($branches)) {
        $is_branch_available=true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic   = $this->{'db_' . $branch_value->id}->database;
              

                $condition[]       = "SELECT  '$branch_value->branch_name' as branch_name,table$branch_value->id.`employee_id`, table$branch_value->id.`name`, `$db_dynamic`.`roles`.`name` as `user_type`, table$branch_value->id.`surname`, `$db_dynamic`.`staff_designation`.`designation`, `$db_dynamic`.`department`.`department_name` as `department`, `$db_dynamic`.`staff_payslip`.* FROM `$db_dynamic`.`staff` table$branch_value->id INNER JOIN `$db_dynamic`.`staff_payslip` ON `$db_dynamic`.`staff_payslip`.`staff_id` = table$branch_value->id.`id` LEFT JOIN  `$db_dynamic`.`staff_designation` ON table$branch_value->id.`designation` = `$db_dynamic`.`staff_designation`.`id` LEFT JOIN  `$db_dynamic`.`department` ON table$branch_value->id.`department` = `$db_dynamic`.`department`.`id` LEFT JOIN  `$db_dynamic`.`staff_roles` ON  `$db_dynamic`.`staff_roles`.`staff_id` = table$branch_value->id.`id` LEFT JOIN  `$db_dynamic`.`roles` ON  `$db_dynamic`.`staff_roles`.`role_id` = `$db_dynamic`.`roles`.`id` WHERE date_format( `$db_dynamic`.staff_payslip.payment_date,'%Y-%m-%d') between ".$this->db->escape($start_date)." and  ".$this->db->escape($end_date);

                //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql=($is_branch_available) ? "SELECT * FROM ($sql) as tempTable" :$sql;
        $query = $this->db->query($sql);

        return $query->result_array();
     
    }
// ---------------Multi_expense_model---------------------------------Multi_expense_model---------------------------------Multi_expense_model---------------------------------Multi_expense_model---------------------------------Multi_expense_model------------------


/*
    This function is used to get expenses from all branch
    */

public function getexpenselistt()
{
    $default_db = $this->db_default->database;
    $sql        = "SELECT table0.*,`$default_db`.`expense_head`.`exp_category`,'".$this->lang->line('home_branch')."' as branch_name FROM `$default_db`.`expenses` table0  JOIN `$default_db`.`expense_head` ON `table0`.`exp_head_id` = `expense_head`.`id` UNION ALL ";
    $condition  = array();
    $this->load->model("multibranch_model");
    //=============================
    $branches = $this->multibranch_model->get();
    $is_branch_available=false;
    if (!empty($branches)) {
    $is_branch_available=true;
        foreach ($branches as $branch_key => $branch_value) {
            $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
            $db_dynamic   = $this->{'db_' . $branch_value->id}->database;
            $condition[]  = "SELECT table$branch_value->id.*,`$db_dynamic`.`expense_head`.`exp_category`,'$branch_value->branch_name' as branch_name FROM `$db_dynamic`.`expenses` table$branch_value->id  JOIN `$db_dynamic`.`expense_head` ON `table$branch_value->id`.`exp_head_id` = `expense_head`.`id`";

            //=============================

        }
        $sql = $sql . implode(" UNION ALL ", $condition);
    } else {
        $sql = substr($sql, 0, -11);
    }
    $sql= "SELECT * FROM ($sql) as tempTable";
  
    $this->datatables->query($sql)
        ->searchable('name,invoice_no,date,amount,exp_category')
        ->orderable('branch_name,name,note,invoice_no,date,exp_category,amount')
        ->query_where_enable(false);
    return $this->datatables->generate('json');
}

/*
This function is used to get expenses from all branch based on date
*/
public function search($start_date = null, $end_date = null)
{


    $default_db = $this->db_default->database;
    $sql        = "SELECT table0.`id`, table0.`date`, table0.`name`, table0.`invoice_no`, table0.`amount`, table0.`documents`, table0.`note`,  `$default_db`.`expense_head`.`exp_category`, table0.`exp_head_id`,'".$this->lang->line('home_branch')."' as branch_name  FROM `$default_db`.`expenses`  table0 JOIN  `$default_db`.`expense_head` ON  table0.`exp_head_id` =  `$default_db`.`expense_head`.`id` WHERE  table0.`date` <= " . $this->db->escape($end_date) . " AND  table0.`date` >= " . $this->db->escape($start_date) . " UNION ALL ";
    $condition  = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {
            $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
            $db_dynamic                        = $this->{'db_' . $branch_value->id}->database;

            $condition[] = "SELECT  table$branch_value->id.`id`,  table$branch_value->id.`date`,  table$branch_value->id.`name`,  table$branch_value->id.`invoice_no`,  table$branch_value->id.`amount`,  table$branch_value->id.`documents`,  table$branch_value->id.`note`, `$db_dynamic`.`expense_head`.`exp_category`,  table$branch_value->id.`exp_head_id`,'$branch_value->branch_name' as branch_name FROM `$db_dynamic`.`expenses`   table$branch_value->id JOIN `$db_dynamic`.`expense_head` ON   table$branch_value->id.`exp_head_id` = `$db_dynamic`.`expense_head`.`id` WHERE   table$branch_value->id.`date` <= " . $this->db->escape($end_date) . " AND table$branch_value->id.`date` >= " . $this->db->escape($start_date);

            //=============================

        }
        $sql = $sql . implode(" UNION ALL ", $condition);
    } else {
        $sql = substr($sql, 0, -11);
    }
    $sql =  "SELECT * FROM ($sql) as tempTable" ;       

    $this->datatables->query($sql)
        ->searchable('branch_name,name,invoice_no,date,amount,exp_category')
        ->orderable('branch_name,name,note,invoice_no,date,exp_category,amount')
        ->query_where_enable(false);
    return $this->datatables->generate('json');
}

// ------------------multi_income_model----------------------------------multi_income_model----------------------------------multi_income_model----------------

   /*
    This function is used to get income from all branch
    */
    public function getttt()
    {
        $default_db = $this->db_default->database;
        $sql        = "SELECT * FROM `$default_db`.`income` table0 UNION ALL ";
        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches = $this->gett();
        if (!empty($branches)) {
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic                        = $this->{'db_' . $branch_value->id}->database;
                $condition[]                       = "SELECT * FROM `$db_dynamic`.`income` table$branch_value->id";

                //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }

        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;

    }

    /*
    This function is used to get income with income head from all branch
    */
    public function getincomelist()
    {
        $default_db = $this->db_default->database;
        $sql        = "SELECT table0.*,`$default_db`.`income_head`.`income_category`,'".$this->lang->line('home_branch')."' as branch_name FROM `$default_db`.`income` table0  JOIN `$default_db`.`income_head` ON `table0`.`income_head_id` = `income_head`.`id` UNION ALL ";
        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches            = $this->gett();
        $is_branch_available = false;
        if (!empty($branches)) {
            $is_branch_available = true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic                        = $this->{'db_' . $branch_value->id}->database;
                $condition[]                       = "SELECT table$branch_value->id.*,`$db_dynamic`.`income_head`.`income_category`,'$branch_value->branch_name' as branch_name FROM `$db_dynamic`.`income` table$branch_value->id  JOIN `$db_dynamic`.`income_head` ON `table$branch_value->id`.`income_head_id` = `income_head`.`id`";

                //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql = ($is_branch_available) ? "SELECT * FROM ($sql) as tempTable" : $sql;

        $this->datatables->query($sql)
            ->searchable('name,invoice_no,date,amount,income_category')
            ->orderable('branch_name,name,note,invoice_no,date,income_category,amount')
            ->sort('id','desc')
            ->query_where_enable(false);
        return $this->datatables->generate('json');
    }

    /*
    This function is used to get income from all branch based on date
    */
    public function searchh($start_date = null, $end_date = null)
    {
        $default_db = $this->db_default->database;
        $sql        = "SELECT table0.`id`, table0.`date`, table0.`name`, table0.`invoice_no`, table0.`amount`, table0.`documents`, table0.`note`,  `$default_db`.`income_head`.`income_category`, table0.`income_head_id`,'".$this->lang->line('home_branch')."' as branch_name  FROM `$default_db`.`income`  table0 JOIN  `$default_db`.`income_head` ON  table0.`income_head_id` =  `$default_db`.`income_head`.`id` WHERE  table0.`date` <= " . $this->db->escape($end_date) . " AND  table0.`date` >= " . $this->db->escape($start_date) . " UNION ALL ";
        $condition  = array();
        // $this->load->model("multibranch_model");
        //=============================
        $branches            = $this->gett();
        $is_branch_available = false;
        if (!empty($branches)) {
            $is_branch_available = true;
            foreach ($branches as $branch_key => $branch_value) {
                $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
                $db_dynamic                        = $this->{'db_' . $branch_value->id}->database;

                $condition[] = "SELECT  table$branch_value->id.`id`,  table$branch_value->id.`date`,  table$branch_value->id.`name`,  table$branch_value->id.`invoice_no`,  table$branch_value->id.`amount`,  table$branch_value->id.`documents`,  table$branch_value->id.`note`, `$db_dynamic`.`income_head`.`income_category`,  table$branch_value->id.`income_head_id`,'$branch_value->branch_name' as branch_name FROM `$db_dynamic`.`income`   table$branch_value->id JOIN `$db_dynamic`.`income_head` ON   table$branch_value->id.`income_head_id` = `$db_dynamic`.`income_head`.`id` WHERE   table$branch_value->id.`date` <= " . $this->db->escape($end_date) . " AND table$branch_value->id.`date` >= " . $this->db->escape($start_date);

                //=============================

            }
            $sql = $sql . implode(" UNION ALL ", $condition);
        } else {
            $sql = substr($sql, 0, -11);
        }
        $sql ="SELECT * FROM ($sql) as tempTable";       
         

        $this->datatables->query($sql)
            ->searchable('branch_name,name,invoice_no,date,amount,income_category')
            ->orderable('branch_name,name,note,invoice_no,date,income_category,amount')
            ->query_where_enable(false);
        return $this->datatables->generate('json');
    }   

// ----------------Multi_user_log_model-----------------------------------Multi_user_log_model-----------------------------------Multi_user_log_model-------------------
public function searchhhh($start_date = null, $end_date = null)
{
    $default_db = $this->db_default->database;
    $sql        = "SELECT table0.*,'".$this->lang->line('home_branch')."' as branch_name ,IFNULL(`$default_db`.`classes`.`class`, '') as `class_name`,IFNULL(`$default_db`.`sections`.`section`, '') as `section_name` FROM `$default_db`.`userlog` table0 LEFT JOIN  `$default_db`.`class_sections` ON  `$default_db`.`class_sections`.`id` = `table0`.`class_section_id` LEFT JOIN  `$default_db`.`classes` ON  `$default_db`.`classes`.`id` =  `$default_db`.`class_sections`.`class_id` LEFT JOIN  `$default_db`.`sections` ON  `$default_db`.`sections`.`id` =  `$default_db`.`class_sections`.`section_id` WHERE   DATE(table0.`login_datetime`) <= " . $this->db->escape($end_date) . " AND   DATE(table0.`login_datetime`) >= " . $this->db->escape($start_date) . " UNION ALL ";
    $condition  = array();
    // $this->load->model("multibranch_model");
    //=============================
    $branches            = $this->gett();
    $is_branch_available = false;
    if (!empty($branches)) {
        $is_branch_available = true;
        foreach ($branches as $branch_key => $branch_value) {
            $this->{'db_' . $branch_value->id} = $this->load->database('branch_' . $branch_value->id, true);
            $db_dynamic                        = $this->{'db_' . $branch_value->id}->database;

            $condition[] = "SELECT  table$branch_value->id.*,'$branch_value->branch_name' as branch_name ,IFNULL(`$db_dynamic`.`classes`.`class`,'') as `class_name`,IFNULL(`$db_dynamic`.`sections`.`section`,'') as `section_name` FROM `$db_dynamic`.`userlog` table$branch_value->id LEFT JOIN  `$db_dynamic`.`class_sections` ON  `$db_dynamic`.`class_sections`.`id` = table$branch_value->id.`class_section_id` LEFT JOIN  `$db_dynamic`.`classes` ON  `$db_dynamic`.`classes`.`id` =  `$db_dynamic`.`class_sections`.`class_id` LEFT JOIN  `$db_dynamic`.`sections` ON  `$db_dynamic`.`sections`.`id` =  `$db_dynamic`.`class_sections`.`section_id` WHERE  DATE(table$branch_value->id.`login_datetime`) <= " . $this->db->escape($end_date) . " AND  DATE(table$branch_value->id.`login_datetime`) >= " . $this->db->escape($start_date);

            //=============================

        }
        $sql = $sql . implode(" UNION ALL ", $condition);
    } else {
        $sql = substr($sql, 0, -11);
    }
    
    $sql ="SELECT * FROM ($sql) as tempTable";  
    $this->datatables->query($sql)
        ->searchable('branch_name,user,class_name,section_name,role,ipaddress,user_agent,login_datetime')
        ->orderable('branch_name,user,role,class_name,ipaddress,login_datetime,user_agent')
        ->query_where_enable(false);
    return $this->datatables->generate('json');
}


}
