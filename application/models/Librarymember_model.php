<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Librarymember_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get()
    {

        $query = "SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `libarary_members`
         INNER JOIN students on libarary_members.member_id= students.id
          WHERE libarary_members.member_type='student' and students.is_active = 'yes'
           UNION SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,null,null,null,null,CONCAT_WS(' ',staff.name,staff.surname) as name,staff.email,null,staff.contact_no,null, staff.id as staff_id,null as stu_id,staff.employee_id as emp_id FROM `libarary_members` 
           INNER JOIN staff on libarary_members.member_id= staff.id
            WHERE libarary_members.member_type='teacher' 
            
            UNION SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,null,null,null,null,guest.name as name,guest.email,null,guest.phone,null, guest.id as staff_id,null as stu_id,null as emp_id FROM `libarary_members` 
           INNER JOIN guest on libarary_members.member_id= guest.id
            WHERE libarary_members.member_type='guest'
            
            
            ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function checkIsMember($member_type, $id)
    {
        $this->db->select()->from('libarary_members');
        $this->db->where('libarary_members.member_id', $id);
        $this->db->where('libarary_members.member_type', $member_type);
        $query = $this->db->get();
        $result = $query->num_rows();
        if ($result > 0) {
            $row        = $query->row();
            $book_lists = $this->bookissue_model->book_issuedByMemberID($row->id);
            return $book_lists;
        } else {
            return false;
        }
    }

    public function getByMemberID($id = null)
    {
        $this->db->select()->from('libarary_members');
        if ($id != null) {
            $this->db->where('libarary_members.id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            $result = $query->row();
            if ($result->member_type == "student") {
                $return = $this->getStudentData($result->id);
            } elseif($result->member_type == "guest"){
                $return = $this->getguestData($result->id);
            }else {
                $return = $this->getTeacherData($result->id);
            }
            return $return;
        }
    }

    public function getTeacherData($id)
    {
        $this->db->select('libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,staff.*');
        $this->db->from('libarary_members');
        $this->db->join('staff', 'libarary_members.member_id = staff.id');
        $this->db->where('libarary_members.id', $id);
        $query  = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function getStudentData($id)
    {
        $this->db->select('libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,students.*,sessions.session as session_year');
        $this->db->from('libarary_members');
        $this->db->join('students', 'libarary_members.member_id = students.id');
        $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
        $this->db->where('libarary_members.id', $id);
        $query  = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function surrender($id,$reason =null)
    {




        $this->db->select('*');
        $this->db->from('libarary_members');
        $this->db->where('id', $id);
        $fill = $this->db->get()->row();
        date_default_timezone_set('Asia/Kolkata'); 
           $fillable = array(
            'library_card_no' =>    $fill->library_card_no ,
            	'member_type' =>$fill->member_type,
                	'member_id' =>$fill->member_id,
                    	'reason' =>$reason,
                 'created_at'  => date('Y-m-d H:i:s'),

           );
           
           $this->db->insert('surrender',$fillable);
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('libarary_members');
        $message   = DELETE_RECORD_CONSTANT . " On libarary members id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->where('member_id', $id);
        $this->db->delete('book_issues');
        $message   = DELETE_RECORD_CONSTANT . " On book issues id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }


    
    public function getteacher()
    {

        $query = "SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `libarary_members`
         INNER JOIN students on libarary_members.member_id= students.id
          WHERE libarary_members.member_type='studentt' and students.is_active = 'yes'
           UNION SELECT libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,null,null,null,null,CONCAT_WS(' ',staff.name,staff.surname) as name,staff.email,null,staff.contact_no,null, staff.id as staff_id,null as stu_id,staff.employee_id as emp_id FROM `libarary_members` 
           INNER JOIN staff on libarary_members.member_id= staff.id
            WHERE libarary_members.member_type='teacher' ";
        $query = $this->db->query($query);
        return $query->result_array();
    }


    public function getstudent($class = null ,$section =null)
    {
        $condition = "";
        if ($class != "" && $section != "") {
            $condition = " and student_session.class_id = '" . $class . "' and student_session.section_id = '" . $section . "'";
        }
        if ($class != "" && $section = "") {
            $condition = " and student_session.class_id = '" . $class . "' ";
        }

        
        $query = "SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id,student_session.class_id,student_session.section_id FROM `libarary_members`
         INNER JOIN students on libarary_members.member_id= students.id
          INNER JOIN student_session on students.id= student_session.student_id
          WHERE libarary_members.member_type='student' and students.is_active = 'yes'" . $condition . "  ";

        //   echo $query;die;
        $query = $this->db->query($query);
        return $query->result_array();
    }


    
    public function getsurrender()
    {

        $query = "SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `surrender`
         INNER JOIN students on surrender.member_id= students.id
          WHERE surrender.member_type='student' and students.is_active = 'yes'
           UNION SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,null,null,null,null,CONCAT_WS(' ',staff.name,staff.surname) as name,staff.email,null,staff.contact_no,null, staff.id as staff_id,null as stu_id,staff.employee_id as emp_id FROM `surrender` 
           INNER JOIN staff on surrender.member_id= staff.id
            WHERE surrender.member_type='teacher' 
            UNION SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,null,null,null,null,guest.name as name,guest.email,null,guest.phone,null, guest.id as staff_id,null as stu_id,null as emp_id FROM `surrender` 
           INNER JOIN guest on surrender.member_id= guest.id
            WHERE surrender.member_type='guest' 
            ";
        $query = $this->db->query($query);
        return $query->result_array();
    }


    
    public function getsurrenderteacher()
    {

        $query = "SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.reason,surrender.library_card_no,surrender.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `surrender`
        INNER JOIN students on surrender.member_id= students.id
         WHERE surrender.member_type='studentt' and students.is_active = 'yes'
          UNION SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.reason,surrender.library_card_no,surrender.member_type,null,null,null,null,CONCAT_WS(' ',staff.name,staff.surname) as name,staff.email,null,staff.contact_no,null, staff.id as staff_id,null as stu_id,staff.employee_id as emp_id FROM `surrender` 
          INNER JOIN staff on surrender.member_id= staff.id
           WHERE surrender.member_type='teacher' ";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    
    public function getsurrenderstudent($class = null ,$section =null)
    {


        $condition = "";
        if ($class != "" && $section != "") {
            $condition = " and student_session.class_id = '" . $class . "' and student_session.section_id = '" . $section . "'";
        }
        if ($class != "" && $section = "") {
            $condition = " and student_session.class_id = '" . $class . "' ";
        }

        $query = "SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `surrender`
        INNER JOIN students on surrender.member_id= students.id
          INNER JOIN student_session on students.id= student_session.student_id
         WHERE surrender.member_type='student' and students.is_active = 'yes'". $condition . "  ";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    
    public function getguest()
    {
        $query = "SELECT libarary_members.id as `lib_member_id`,libarary_members.renewed,libarary_members.library_card_no,libarary_members.member_type,null as admission_no,null,null,null,guest.name as teacher_name,guest.email,null,guest.phone,null, guest.id as staff_id,null as stu_id,null as emp_id,guest.email as teacher_email,guest.gender as teacher_sex,guest.phone as  teacher_phone
        
        FROM `libarary_members`   
        INNER JOIN guest on libarary_members.member_id= guest.id
         WHERE libarary_members.member_type='guest' ";

       //   echo $query;die;
       $query = $this->db->query($query);
       return $query->result_array();
   
    }


    public function getguestData($id)
    {
        $this->db->select('libarary_members.id as `lib_member_id`,libarary_members.library_card_no,libarary_members.member_type,guest.*');
        $this->db->from('libarary_members');
        $this->db->join('guest', 'libarary_members.member_id = guest.id');
        $this->db->where('libarary_members.id', $id);
        $query  = $this->db->get();
        $result = $query->row();
        return $result;
    }

    
    public function getsurrenderguset()
    {

        $query = "SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,students.admission_no,students.firstname,students.lastname,students.guardian_phone,null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,null as `teacher_phone`,students.middlename, null as staff_id,students.id as stu_id,null as emp_id FROM `surrender`
         INNER JOIN students on surrender.member_id= students.id
          WHERE surrender.member_type='studentt' and students.is_active = 'yes'
           UNION SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,null,null,null,null,CONCAT_WS(' ',staff.name,staff.surname) as name,staff.email,null,staff.contact_no,null, staff.id as staff_id,null as stu_id,staff.employee_id as emp_id FROM `surrender` 
           INNER JOIN staff on surrender.member_id= staff.id
            WHERE surrender.member_type='teacherr' 
            UNION SELECT surrender.id as `lib_member_id`,surrender.created_at,surrender.library_card_no,surrender.reason,surrender.member_type,null,null,null,null,guest.name as name,guest.email,null,guest.phone,null, guest.id as staff_id,null as stu_id,null as emp_id FROM `surrender` 
           INNER JOIN guest on surrender.member_id= guest.id
            WHERE surrender.member_type='guest'";

        $query = $this->db->query($query);
        return $query->result_array();
    }

}
