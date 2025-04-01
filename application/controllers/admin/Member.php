<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member extends Admin_Controller
{

    public function __construct()
    {
       
        
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library("datatables");


     
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title']      = 'Member';
        $data['title_list'] = 'Members';
        
        if ($this->input->server('REQUEST_METHOD') == "GET") {
          
            $memberList         = $this->librarymember_model->get();
        } else {
           
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $members_type      = $this->input->post('members_type');
      
            if (isset($members_type)) {

                 if($members_type == 'teacher'){
                    $memberList         = $this->librarymember_model->getteacher();

                 }elseif($members_type == 'student'){
                    $memberList         = $this->librarymember_model->getstudent($class,$section);
                    // echo "<pre>";
                    //     print_r($memberList);die;
                 }elseif($members_type == 'guest'){
                    $memberList         = $this->librarymember_model->getguest();
                    // echo "<pre>";
                    //     print_r($memberList);die;
                 }else{
                    $memberList         = $this->librarymember_model->get();

                 }



            }



      
        }
        $superadmin_visible = $this->customlib->superadmin_visible();

        if ($superadmin_visible == 'disabled') {
            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);

            if ($staffrole->id != 7) {
                foreach ($memberList as $key => $member) {
                    if ($member['member_type'] != "student") {
                        $getrole = $this->staff_model->getAll($member['staff_id']);

                        if ($getrole['role_id'] != 7) {
                            $result[] = $member;
                        }

                    } else {
                        $result[] = $member;
                    }
                }
            } else {
                $result = $memberList;
            }
        } else {
            $result = $memberList;
        }

        $data['members']    = array('' => $this->lang->line('all'), 'student' => $this->lang->line('student'), 'teacher' => $this->lang->line('teacher') , 'guest' => $this->lang->line('guest'));
  $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $data['memberList']  = $result;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }

    public function issue($id)
    {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title']        = 'Member';
        $data['title_list']   = 'Members';
        $memberList           = $this->librarymember_model->getByMemberID($id);
        $data['memberList']   = $memberList;
        $issued_books         = $this->bookissue_model->getMemberBooks($id);
        $data['issued_books'] = $issued_books;
        // $bookList             = $this->book_model->get();
        // $data['bookList']     = $bookList;
        $this->form_validation->set_rules('return_date', $this->lang->line('due_return_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_id', $this->lang->line('books'), array('required', array('check_exists', array($this->bookissue_model, 'valid_check_exists')),
        )
        );
        if ($this->form_validation->run() == false) {

        } else {
            $member_id = $this->input->post('member_id');

            $tosea = $this->input->post('book_id');

            $this->db->select()->from('books_list');
            $this->db->where('books_list.id', $tosea);
            $query = $this->db->get();
    


            $books_master_id =  $query->row();


            $data      = array(
                'book_master_id' => $books_master_id->book_id,
                'book_id'        => $this->input->post('book_id'),
                'duereturn_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('return_date'))),
                'issue_date'     => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('issue_date'))),
                'member_id'      => $this->input->post('member_id'),
            );
            // print_r($data );die;
            $this->bookissue_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/member/issue/' . $member_id);
        }
       
        //start check code for number of bookk issues
      
      $this->db->select()->from('library_setting');
      $this->db->like('type',$memberList->member_type);
      $query = $this->db->get();
      $maxbook = $query->row();




        $this->db->select()->from('book_issues');
        $this->db->where('book_issues.member_id', $id);
        $this->db->where('book_issues.is_returned', 0);
        $query = $this->db->get();
        $data['already_book_issued'] = $query->num_rows();
        $data['maxbook_book_count'] = $maxbook->book_count;

        if ($data['already_book_issued'] >= $data['maxbook_book_count']) {
            $data['issue_count_check']   = 0;
        }else{
            $data['issue_count_check']   = 1;
        }

        
         //end check code for number of bookk issues
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/issue', $data);
        $this->load->view('layout/footer');
    }

   
    public function bookreturn()
    {


      $lost_book_confirm =  $this->input->post('lost_book_confirm');
      

      if($lost_book_confirm == 1){



        $id        = $this->input->post('id');
        $member_id = $this->input->post('member_id');
        $date      = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('lost_date')));
        $data      = array(
            'id'          => $id,
            'return_date' => $date,
            'is_returned' => 2,
            'fine_paid' => 1,
            'late_fine' => $this->input->post('lost_fine'),
            'description' => $this->input->post('description')
            
        );
        $this->bookissue_model->update($data);



        $this->db->select('book_issues.book_id')->from('book_issues');
        $this->db->where('book_issues.id', $id);
        $query = $this->db->get();
        $details = $query->row();
     
        $this->db->where('id', $details->book_id);
        $this->db->update('books_list', array('lost'=>1));


        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
      }else{

        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('member_id', $this->lang->line('member_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('return_date'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id'        => form_error('id'),
                'member_id' => form_error('member_id'),
                'date'      => form_error('date'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $id        = $this->input->post('id');
            $member_id = $this->input->post('member_id');
            $date      = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));
            $data      = array(
                'id'          => $id,
                'return_date' => $date,
                'is_returned' => 1,
                'fine_paid' => $this->input->post('fine'),
                'late_fine' => $this->input->post('late_fine')
                
            );
            $this->bookissue_model->update($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
      }

     
        }
    }
    public function student()
    {
        if (!$this->rbac->hasPrivilege('add_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/student');
        $data['title']     = 'Student Search';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $button            = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {

                    } else {
                        $data['searchby']    = "filter";
                        $data['class_id']    = $this->input->post('class_id');
                        $data['section_id']  = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->searchLibraryStudent($class, $section);

                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby']    = "text";
                    $data['class_id']    = $this->input->post('class_id');
                    $data['section_id']  = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->searchFullText($search_text);
                    $data['resultlist']  = $resultlist;
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function add()
    {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', $this->lang->line('library_card_number'), 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student         = $this->input->post('member_id');
                $data            = array(
                    'member_type'     => 'student',
                    'member_id'       => $student,
                    'library_card_no' => $library_card_no,
                    'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student         = $this->input->post('member_id');
            $data            = array(
                'member_type'     => 'student',
                'member_id'       => $student,
                'library_card_no' => $library_card_no,
                'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function check_cardno_exists()
    {
        $data['library_card_no'] = $this->security->xss_clean($this->input->post('library_card_no'));

        if ($this->librarymanagement_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', $this->lang->line('card_no_already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function teacher()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/member/teacher');
        $data['title']       = 'Add Teacher';
        $data['teacherlist'] = $this->teacher_model->getLibraryTeacher(); 
        $data['genderList'] = $this->customlib->getGender();        
        
        



        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/teacher', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addteacher()
    {
        if ($this->input->post('library_card_no') != "") {
            $this->form_validation->set_rules('library_card_no', $this->lang->line('library_card_number'), 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student         = $this->input->post('member_id');
                $data            = array(
                    'member_type'     => 'teacher',
                    'member_id'       => $student,
                    'library_card_no' => $library_card_no,
                    'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student         = $this->input->post('member_id');
            $data            = array(
                'member_type'     => 'teacher',
                'member_id'       => $student,
                'library_card_no' => $library_card_no,
                'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function surrender()
    {
        $this->form_validation->set_rules('member_id', $this->lang->line('book'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {
        
            $reason = $this->input->post('reason');
            $member_id = $this->input->post('member_id');
              $row_affected=$this->librarymember_model->surrender($member_id,$reason);
            $array = array('status' => 'success', 'row_affected'=>$row_affected, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
	
	public function book_fine_paid($id)
    {
        $data      = array(
            'id'          => $id,
            'fine_paid' => 1
        );
        $this->bookissue_model->update($data);
    }


    public function guest()
    {


        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/member/guest');
        $data['title']       = 'Add Guest';

        $this->db->select()->from('guest');
        $this->db->order_by('guest.id','desc');
        $query = $this->db->get();
        $data['guestList'] =  $query->result_array();
       


        $data['genderList'] = $this->customlib->getGender();         

        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/guest', $data);
        $this->load->view('layout/footer', $data);



    }

    
    public function guest_add()
    {
        $data['title']       = 'Add Guest';

        // $data['teacherlist'] = $this->teacher_model->getLibraryGuest(); 


        $data['genderList'] = $this->customlib->getGender();         

   
        $this->db->select()->from('guest');
        $this->db->order_by('guest.id','desc');
        $query = $this->db->get();
        $data['guestList'] =  $query->result_array();

        $this->form_validation->set_rules('name', 'Guest Name', 'trim|required|xss_clean');





        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|is_unique[guest.email]');






        $this->form_validation->set_rules('gender','Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[guest.phone]');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/guest', $data);
            $this->load->view('layout/footer', $data);
    
        } else 
        {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/guest_documents/");


            $name =   $this->input->post('name');
            $email =   $this->input->post('email');
            $gender =    $this->input->post('gender');
            $dob =    $this->input->post('dob');
            $address =   $this->input->post('address');
            $phonee =   $this->input->post('phone');


            $dataa = array(
                'name' =>  $name,
                'email'        =>  $email,
                'gender'        =>$gender ,
                'dob'        => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'address'      => $address,
                'phone'      => $phonee,
                'documents'   => $img_name,
                'note'        => $this->input->post('description'),
               
            );
            
            $this->db->insert('guest', $dataa);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/member/guest');
        }

        
}

public function handle_upload()
{
    $image_validate = $this->config->item('file_validate');
    $result         = $this->filetype_model->get();
    if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
        $file_type         = $_FILES["documents"]['type'];
        $file_size         = $_FILES["documents"]["size"];
        $file_name         = $_FILES["documents"]["name"];
        $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
        $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
        $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($files = filesize($_FILES['documents']['tmp_name'])) {

            if (!in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                return false;
            }
            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }
        } else {
            $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
            return false;
        }

        return true;
    }
    return true;
}






public function guestdelete($id)
    {
      
        $this->db->where('id', $id);
        $this->db->delete('guest');
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . $this->lang->line('delete_message') . '');
        redirect('admin/member/guest');
    }



    

    public function guestedit($id)
    {
        $data['title']       = 'Add Guest';

        // $data['teacherlist'] = $this->teacher_model->getLibraryGuest(); 


        $data['genderList'] = $this->customlib->getGender();         

   
        $this->db->select()->from('guest');
        $this->db->order_by('guest.id','desc');
        $query = $this->db->get();
        $data['guestList'] =  $query->result_array();



        
   
        $this->db->select()->from('guest');
        $this->db->where('guest.id',$id);
        $query = $this->db->get();
        $data['librarian'] =  $query->row_array();
        
        $data['id'] =  $id;



        if($this->input->post('email') == $data['librarian']['email']) {
            $is_unique =  '';
         } else {
            $is_unique  =  '|is_unique[guest.email]';
         }
         
         if($this->input->post('phone') == $data['librarian']['phone']) {
            $is_unique1 =  '';
         } else {
            $is_unique1  =  '|is_unique[guest.phone]';
         }

           $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean'.$is_unique);
           $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean|regex_match[/^[0-9]{10}$/]'.$is_unique1);






        $this->form_validation->set_rules('name', 'Guest Name', 'trim|required|xss_clean');

        $this->form_validation->set_rules('gender','Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/guestedit', $data);
            $this->load->view('layout/footer', $data);
    
        } else 
        {


            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {

                $img_name = $this->media_storage->fileupload("documents", "./uploads/guest_documents/");
            } else {
                $img_name = $data['librarian']['documents'];
            }
         


            $name =   $this->input->post('name');
            $email =   $this->input->post('email');
            $gender =    $this->input->post('gender');
            $dob =    $this->input->post('dob');
            $address =   $this->input->post('address');
            $phonee =   $this->input->post('phone');


            $dataa = array(
                'name' =>  $name,
                'email'        =>  $email,
                'gender'        =>$gender ,
                'dob'        => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'address'      => $address,
                'phone'      => $phonee,
                'documents'   => $img_name,
                'note'        => $this->input->post('description'),
               
            );
            $this->db->where('guest.id',$id);
            $this->db->update('guest', $dataa);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/member/guest');
        }

        
}


public function download($id)
{
    $this->db->select()->from('guest');
    $this->db->where('guest.id',$id);
    $query = $this->db->get();
    $result =  $query->row_array();
    $this->media_storage->filedownload($result['documents'], "./uploads/guest_documents");
}





public function addguestmember()
{
    if ($this->input->post('library_card_no') != "") {
        $this->form_validation->set_rules('library_card_no', $this->lang->line('library_card_number'), 'required|trim|xss_clean|callback_check_cardno_exists');
        if ($this->form_validation->run() == false) {
            $data = array(
                'library_card_no' => form_error('library_card_no'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student         = $this->input->post('member_id');
            $data            = array(
                'member_type'     => 'guest',
                'member_id'       => $student,
                'library_card_no' => $library_card_no,
                'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    } else {
        $library_card_no = $this->input->post('library_card_no');
        $student         = $this->input->post('member_id');
        $data            = array(
            'member_type'     => 'guest',
            'member_id'       => $student,
            'library_card_no' => $library_card_no,
            'libaray_card_date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('library_card_date'))),
        );

        $inserted_id = $this->librarymanagement_model->add($data);
        $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
        echo json_encode($array);
    }
}


     public function renewed($id)
    {
        $this->db->where('id', $id);
   
        $this->db->update('libarary_members',array( 'renewed'	 =>1));



        $this->db->select()->from('libarary_members');
        $this->db->where('libarary_members.id',$id);
        $query = $this->db->get();
        $data =  $query->row();

        if($data){
            $this->db->insert('renew_data', array( 'lib_id'	 =>$id , 'stu_id'	 =>$data->member_id ,  'session'	 =>$data->renewed_session ));
        }
   


        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Membership Renewed Successfully</div>');
        redirect('admin/member/index');
    }




}