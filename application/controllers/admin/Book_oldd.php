<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Book extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encoding_lib');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');

        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $listbook           = $this->book_model->listbook();
        $data['listbook']   = $listbook;


        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;


        $this->load->view('layout/header');
        $this->load->view('admin/book/createbook', $data);
        $this->load->view('layout/footer');
    }

    public function getall()
    {

        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');



        $this->db->select()->from('books');
		$this->db->group_by('book_title');
        $this->db->order_by('books.id', 'desc');
        $query = $this->db->get();
        $titldata = $query->result();
        $data['titldata']  = $titldata;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;

        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;


        $this->db->select()->from('books_list');
        $this->db->where('lost',0);
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $totalcopies = $listbook->num_rows();
        $data['totalcopies']  =$totalcopies; 
  

        $this->db->select('books.created_by')->from('books');
        $this->db->group_by('created_by');// add group_by
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $created_bylist = $listbook->result();
        $data['created_bylist']  =$created_bylist; 


      

        $this->load->view('layout/header');
        $this->load->view('admin/book/getall',$data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('books', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->form_validation->set_rules('book_title', $this->lang->line('book_title'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('book_category', $this->lang->line('book_category'), 'trim|required|xss_clean');

        
        // $this->form_validation->set_rules('perunitcost', $this->lang->line('book_price'), 'numeric');
        $this->form_validation->set_rules('qty', $this->lang->line('qty'), 'numeric');
        
        // $this->form_validation->set_rules('subject','subject', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('publish', 'publisher', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('author', 'author', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('department', 'department', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('book_language','book language', 'trim|required|xss_clean');





        
if($this->input->post('writeoff') == 1){
    $this->form_validation->set_rules('writeoffyear', 'Write Off Year', 'trim|required|numeric|xss_clean');

}




        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;


        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;


        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/createbook', $data);
            $this->load->view('layout/footer');
        } else {
            
            if($this->input->post('perunitcost')){
                $perunitcost    =   convertCurrencyFormatToBaseAmount($this->input->post('perunitcost'));
            }else{
                $perunitcost    = '';
            }

            $userdata        = $this->customlib->getUserData();
            
            
            $data = array(
                'book_title'  => $this->input->post('book_title'),
                // 'book_no'     => $this->input->post('book_no'),
                'isbn_no'     => $this->input->post('isbn_no'),
                'subject'     => $this->input->post('subject'),
                // 'rack_no'     => $this->input->post('rack_no'),
                'publish'     => $this->input->post('publisher'),
                'author'      => $this->input->post('author'),
                'qty'         => $this->input->post('qty'),
                'publishing_year'     => $this->input->post('publishing_year'),
                'department'      => $this->input->post('department'),
                'pages_count'         => $this->input->post('pages_count'),
                'perunitcost' => $perunitcost,
                'description' => $this->input->post('description'),
                'book_category' => $this->input->post('book_category'),
                'book_edition'         => $this->input->post('book_edition'),
                'book_format' =>$this->input->post('book_format'),
                'book_language' => $this->input->post('book_language'),
                'generation' => $this->input->post('generation'),
                'tags' => $this->input->post('tags'),
                'created_by' => $userdata['email'],
                'writeoff' => $this->input->post('writeoff'),
                'writeoffyear' => $this->input->post('writeoffyear'),
            );

            if (isset($_POST['postdate']) && $_POST['postdate'] != '') {
                $data['postdate'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate')));
            } else {
                $data['postdate'] = null;
            }
             $lastid =   $this->book_model->addbooks($data);


             $bookcopysucess = 0;

             $book_number = $this->input->post('book_no');
             $book_location = $this->input->post('book_location');

             $writeoffst = $this->input->post('writeoff');
             if($writeoffst == 1){
                      $xzn = 1;
             }else{
                $xzn = 0;
             }

              
             $book_numberArrayy = count(array_filter($book_number));
             if($book_numberArrayy > 0)
             {
                 $boookArray = array();
                 foreach($book_number as $key => $book)
                 {
                    if($book_number[$key]??'' != ''){
$bookcopysucess++;

                        $boookArray[] = array(
                            'book_id'=> $lastid,
                             'bookcode'=> $book_number[$key]??'',
                             'location' => $book_location[$key]??'',
                             'created_by' => $userdata['email'],
                             'lost' => $xzn,
                         );
    

                    }
                    
                 }


         

                 $this->db->insert_batch('books_list', $boookArray);

             }


            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book Inserted Successfully ( ' . $bookcopysucess .' Copies inserted successfully out of '. $book_numberArrayy . ' Copies )</div>');
            redirect('admin/book/getall');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('books', 'can_edit')) {
            access_denied();
        }

        $data['title']      = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $data['id']         = $id;
        $editbook           = $this->book_model->get($id);
        $data['editbook']   = $editbook;
        $this->form_validation->set_rules('book_title', $this->lang->line('book_title'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('perunitcost', $this->lang->line('book_price'), 'numeric');
        $this->form_validation->set_rules('qty', $this->lang->line('qty'), 'numeric');
        $this->form_validation->set_rules('book_category', $this->lang->line('book_category'), 'trim|required|xss_clean');
     
        // $this->form_validation->set_rules('subject','subject', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('publish', 'publisher', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('author', 'author', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('department', 'department', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('book_language','book language', 'trim|required|xss_clean');
        if($this->input->post('writeoff') == 1){
            $this->form_validation->set_rules('writeoffyear', 'Write Off Year', 'trim|required|numeric|xss_clean');
        
        }


        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;



        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/editbook', $data);
            $this->load->view('layout/footer');
        } else {
            
            if($this->input->post('perunitcost')){
                $perunitcost    =   convertCurrencyFormatToBaseAmount($this->input->post('perunitcost'));
            }else{
                $perunitcost    = '';
            }
            
            $data = array(
                'id'          => $this->input->post('id'),
                'book_title'  => $this->input->post('book_title'),
                // 'book_no'     => $this->input->post('book_no'),
                'isbn_no'     => $this->input->post('isbn_no'),
                'subject'     => $this->input->post('subject'),
                // 'rack_no'     => $this->input->post('rack_no'),
                'publish'     => $this->input->post('publisher'),
                'author'      => $this->input->post('author'),
                'qty'         => $this->input->post('qty'),
                'publishing_year'     => $this->input->post('publishing_year'),
                'department'      => $this->input->post('department'),
                'pages_count'         => $this->input->post('pages_count'),
                'perunitcost' => $perunitcost,
                'description' => $this->input->post('description'),
                'book_category' => $this->input->post('book_category'),
                'book_edition'         => $this->input->post('book_edition'),
                'book_format' =>$this->input->post('book_format'),
                'book_language' => $this->input->post('book_language'),
                'generation' => $this->input->post('generation'),
                'tags' => $this->input->post('tags'),
                'writeoff' => $this->input->post('writeoff'),
                'writeoffyear' => $this->input->post('writeoffyear'),
            );
            if (isset($_POST['postdate']) && $_POST['postdate'] != '') {
                $data['postdate'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate')));
            } else {
                $data['postdate'] = null;
            }

            $this->book_model->addbooks($data);


            $data2 = array(          
                'lost'	 => 0,
            );
            $this->db->where('book_id',$this->input->post('id'));
            $this->db->where('lost',2);
            $this->db->update('books_list', $data2);






            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/book/getall');
        }
    }
    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';



        $this->db->select()->from('books_list');
        $this->db->where('books_list.book_id', $id);
        $check = $this->db->get()->num_rows();

if($check == 0){
    $this->book_model->remove($id);
    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
}else{
  
    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error ! Delete Related Accession No. First</div>');
}
      
        redirect('admin/book/getall');
    }

    public function getAvailQuantity()
    {

        $book_id   = $this->input->post('book_id');
        $available = 0;
        if ($book_id != "") {
            $result    = $this->bookissue_model->getAvailQuantity($book_id);
            // print_r($result);
           
        }
         
        if($result){
            $resultt=  $this->book_model->get($result->book_id);
            $available = 1 - $result->total_issue;
            $result_final = array('status' => '1', 'qty' => $available , 'name' => $resultt ,'id' => $result->id);
        }else{
            $resultt=  array();
            $available = 0;
            $result_final = array('status' => '0', 'qty' => $available , 'name' => $resultt );
        }
       

       
        echo json_encode($result_final);
    }

    public function import()
    {


        $data["fields"]   = array('book_title','isbn_no','subject','publish','author','qty','publishing_year','department','pages_count','perunitcost','description','book_edition','book_format','book_language','generation','tags','refrence','bookcode','location');



        // $data["fields"]   = array('book_title', 'book_no', 'isbn_no', 'subject', 'rack_no', 'publish', 'author', 'qty', 'perunitcost', 'postdate', 'description', 'available');
        $this->form_validation->set_rules('file', $this->lang->line('images'), 'callback_handle_csv_upload');
        $this->form_validation->set_rules('book_category', $this->lang->line('book_category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {


            $this->db->select()->from('book_category');
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $book_category = $query->result();
            $data['book_category']  =$book_category;



            $this->db->select()->from('library_dropdown_data');
            $this->db->order_by('library_dropdown_data.id', 'desc');
            $query = $this->db->get();
            $dropdowndata = $query->result();
            $data['dropdowndata']  =$dropdowndata;
            

            $this->load->view('layout/header');
            $this->load->view('admin/book/import', $data);
            $this->load->view('layout/footer');
        } else {
       

           $bookcategory = $this->input->post('book_category');
           $publisher = $this->input->post('publisher');
           $author = $this->input->post('author');
           $subject = $this->input->post('subject');
           $book_language = $this->input->post('book_language');
           $department = $this->input->post('department');

           
           $this->db->select('id')->from('library_dropdown_data');
           $this->db->where('type',1);
           $check2 = $this->db->get();
           $check2 = $check2->result_array();
           $publisharray = array_map (function($value){
               return $value['id'];
           } , $check2);

           $this->db->select('id')->from('library_dropdown_data');
           $this->db->where('type',2);
           $check2 = $this->db->get();
           $check2 = $check2->result_array();
           $authorarray = array_map (function($value){
               return $value['id'];
           } , $check2);
   
           $this->db->select('id')->from('library_dropdown_data');
           $this->db->where('type',3);
           $check2 = $this->db->get();
           $check2 = $check2->result_array();
           $subjectarray = array_map (function($value){
               return $value['id'];
           } , $check2);


           $this->db->select('id')->from('library_dropdown_data');
           $this->db->where('type',4);
           $check2 = $this->db->get();
           $check2 = $check2->result_array();
           $languagearray = array_map (function($value){
               return $value['id'];
           } , $check2);



           $this->db->select('id')->from('library_dropdown_data');
           $this->db->where('type',5);
           $check2 = $this->db->get();
           $check2 = $check2->result_array();
           $deapartmentarray = array_map (function($value){
               return $value['id'];
           } , $check2);


            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    $fields = $data["fields"];
                    //print_r($result);
                    $book_copy_data = array();
                    $insert_id = 0;

                    if (!empty($result)) {
                           // $rowcount = 0;

                            for ($i = 1; $i <= count($result); $i++) {

                            $book_data = array();
                            $n                = 0;

                        foreach ($result[$i] as $r_key => $r_value) 
                        {

                            
                           $book_data[$i][$fields[$n]] =  $this->encoding_lib->toUTF8($result[$i][$r_key]) ;

                           $book_data[$i]['book_category'] = $bookcategory;
                           
                        
                           if( $fields[$n] == 'publish'){
                           if(in_array($this->encoding_lib->toUTF8($result[$i]['publish']),$publisharray)){
                            $book_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i]['publish']);
                          
                        }else{
                            
                            $book_data[$i][$fields[$n]]  = $publisher;
                           }}

                           if( $fields[$n] == 'author'){
                           if(in_array($this->encoding_lib->toUTF8($result[$i]['author']),$authorarray)){
                            $book_data[$i][$fields[$n]]= $this->encoding_lib->toUTF8($result[$i]['author']);
                           }else{
                            $book_data[$i][$fields[$n]]  = $author;
                           }}
                        

                           if( $fields[$n] == 'subject'){
                           if(in_array($this->encoding_lib->toUTF8($result[$i]['subject']),$subjectarray)){
                            $book_data[$i][$fields[$n]]  = $this->encoding_lib->toUTF8($result[$i]['subject']);
                           }else{
                            $book_data[$i][$fields[$n]]  = $subject;
                           }}

                           if( $fields[$n] == 'book_language'){
                           if(in_array($this->encoding_lib->toUTF8($result[$i]['book_language']),$languagearray)){
                            $book_data[$i][$fields[$n]]  = $this->encoding_lib->toUTF8($result[$i]['subject']);
                           }else{
                            $book_data[$i][$fields[$n]]  = $book_language;
                           }}


                           if( $fields[$n] == 'department'){
                           if(in_array($this->encoding_lib->toUTF8($result[$i]['department']),$deapartmentarray)){
                            $book_data[$i][$fields[$n]]  = $this->encoding_lib->toUTF8($result[$i]['department']);
                           }else{
                            $book_data[$i][$fields[$n]]  = $department;
                           }}
                           // exit();

                            // $result[$r_key]['book_title']  = $this->encoding_lib->toUTF8($result[$r_key]['book_title']);
                            // $result[$r_key]['book_no']     = $this->encoding_lib->toUTF8($result[$r_key]['book_no']);
                            // $result[$r_key]['isbn_no']     = $this->encoding_lib->toUTF8($result[$r_key]['isbn_no']);
                            // $result[$r_key]['subject']     = $this->encoding_lib->toUTF8($result[$r_key]['subject']);
                            // $result[$r_key]['rack_no']     = $this->encoding_lib->toUTF8($result[$r_key]['rack_no']);
                            // $result[$r_key]['publish']     = $this->encoding_lib->toUTF8($result[$r_key]['publish']);
                            // $result[$r_key]['author']      = $this->encoding_lib->toUTF8($result[$r_key]['author']);
                            // $result[$r_key]['qty']         = $this->encoding_lib->toUTF8($result[$r_key]['qty']);
                            // $result[$r_key]['perunitcost'] = convertCurrencyFormatToBaseAmount($this->encoding_lib->toUTF8($result[$r_key]['perunitcost']));
                            // $result[$r_key]['postdate']    = $this->encoding_lib->toUTF8($result[$r_key]['postdate']);
                            // $result[$r_key]['description'] = $this->encoding_lib->toUTF8($result[$r_key]['description']);
                           // $rowcount++;
// print_r($book_data);die;

                     
                       

                            $n++;
                            }
   //    $this->db->insert('books', $book_data);

//   echo  $this->encoding_lib->toUTF8($result[$i]['refrence']);
  unset($book_data[$i]['refrence']);
  if($this->encoding_lib->toUTF8($result[$i]['refrence']) == '0'){

    unset($book_data[$i]['bookcode']);
    unset($book_data[$i]['location']);


 

    $this->db->insert('books', $book_data[$i]);
    $insert_id = $this->db->insert_id();

  }else{
    unset($book_data[$i]['book_title']);
    unset($book_data[$i]['book_category']);
    unset($book_data[$i]['isbn_no']);
    unset($book_data[$i]['subject']);
    unset($book_data[$i]['publish']);
    unset($book_data[$i]['author']);
    unset($book_data[$i]['qty']);
    unset($book_data[$i]['publishing_year']);
    unset($book_data[$i]['department']);
    unset($book_data[$i]['pages_count']);
    unset($book_data[$i]['perunitcost']);
    unset($book_data[$i]['description']);
    unset($book_data[$i]['book_edition']);
    unset($book_data[$i]['book_format']);
    unset($book_data[$i]['book_language']);
    unset($book_data[$i]['generation']);
    unset($book_data[$i]['tags']);


       $book_data[$i]['book_id'] = $insert_id;
   

   
if($insert_id){
    $book_copy_data[] = $book_data[$i];
}
  

  }

                        }
                     
                        $this->db->insert_batch('books_list', $book_copy_data);

                    }
                   
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_csv_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully'));
                }
            } else {
                $msg = array(
                    'e' => $this->lang->line('the_file_field_is_required'),
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . "  " . $this->lang->line('records_found_in_csv_file_total') . " " . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
            redirect('admin/book/import');
        }
    }

    

    public function import_new()
    {

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);

                $rowcount = 0;
                if (!empty($result)) {
                    foreach ($result as $r_key => $r_value) {
                        $result[$r_key]['book_title']  = $this->encoding_lib->toUTF8($result[$r_key]['book_title']);
                        $result[$r_key]['book_no']     = $this->encoding_lib->toUTF8($result[$r_key]['book_no']);
                        $result[$r_key]['isbn_no']     = $this->encoding_lib->toUTF8($result[$r_key]['isbn_no']);
                        $result[$r_key]['subject']     = $this->encoding_lib->toUTF8($result[$r_key]['subject']);
                        $result[$r_key]['rack_no']     = $this->encoding_lib->toUTF8($result[$r_key]['rack_no']);
                        $result[$r_key]['publish']     = $this->encoding_lib->toUTF8($result[$r_key]['publish']);
                        $result[$r_key]['author']      = $this->encoding_lib->toUTF8($result[$r_key]['author']);
                        $result[$r_key]['qty']         = $this->encoding_lib->toUTF8($result[$r_key]['qty']);
                        $result[$r_key]['perunitcost'] = convertCurrencyFormatToBaseAmount($this->encoding_lib->toUTF8($result[$r_key]['perunitcost']));
                        $result[$r_key]['postdate']    = $this->encoding_lib->toUTF8($result[$r_key]['postdate']);
                        $result[$r_key]['description'] = $this->encoding_lib->toUTF8($result[$r_key]['description']);
                        $rowcount++;
                    }

                    $this->db->insert_batch('books', $result);
                }
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_csv_file_total') . $rowcount . $this->lang->line('records_imported_successfully'));
            }
        } else {
            $msg = array(
                'e' => $this->lang->line('the_file_field_is_required'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        }

        echo json_encode($array);
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_book_sample_file.csv';
        force_download($name, $data);
    }

    public function issue_report()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/book/issue_report');
        $data['title']        = 'Add Teacher';
        $teacher_result       = $this->teacher_model->getLibraryTeacher();
        $data['teacherlist']  = $teacher_result;
        $genderList           = $this->customlib->getGender();
        $data['genderList']   = $genderList;
        $issued_books         = $this->bookissue_model->getissueMemberBooks();
        $data['issued_books'] = $issued_books;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/book/issuereport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function issue_returnreport()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/library');
        $this->session->set_userdata('subsub_menu', 'Reports/library/issue_returnreport');
        $data['title']        = 'Add Teacher';
        $teacher_result       = $this->teacher_model->getLibraryTeacher();
        $data['searchlist']   = $this->customlib->get_searchtype();
        $data['search_type'] = '';
        
        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;


        // $issued_books         = $this->bookissue_model->getissuereturnMemberBooks();
        // $data['issued_books'] = $issued_books;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/book/issue_returnreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getbooklist()
    {

       
        $start =  $this->input->post('start');
 if($start){
    
 }else{
    $start = 0;
 }

   
            $listbook        = $this->book_model->getbooklist();



        $i =$start ;
    
      
        $m               = json_decode($listbook);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();


    
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {

                $i++;

                $editbtn   = '';
                $deletebtn = '';


                
                if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                    $bulkdel = '<span style="display: flex;"><input name="checkbox[]" class="ids" type="checkbox" value="'.$value->id.'" style="margin-right:5px">'.$i.' </span>';
                }

                if ($this->rbac->hasPrivilege('books', 'can_edit')) {
                    $viewbtn = "<a href='" . base_url() . "admin/book/importcopies/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='Import Accession No.'><i class='fa fa-plus'></i></a>"."<a data-id='". $value->id . "'   class='btn btn-default btn-xs viewbooks'  data-toggle='tooltip' title='View Accession No.'><i class='fa fa-eye'></i></a>";

                    $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }

                if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

                $row   = array();
                $row[] = $bulkdel;
                $row[] = $value->book_title;


                $abc =$value->book_category;
                $this->db->select()->from('book_category');
                $this->db->where('book_category.id', $abc);
                $query = $this->db->get();
                $book_category = $query->row();



                
if($book_category){
    $pq     = $book_category->book_category;
}else{
    $pq    = 'NA';
}

              

$row[]   = $pq;


                // if ($value->description == "") {
                //     $row[] = $this->lang->line('no_description');
                // } else {
                //     $row[] = $value->description;
                // }
                // $row[]     = $value->book_no;



                // $row[]     = $value->isbn_no;
                $this->db->select()->from('library_dropdown_data');
                $this->db->where('library_dropdown_data.id', $value->publish);
                $query = $this->db->get();
                $val = $query->row();

                $row[]     = $val->name??'NA';

                $this->db->select()->from('library_dropdown_data');
                $this->db->where('library_dropdown_data.id', $value->author);
                $query = $this->db->get();
                $val = $query->row();

                $row[]     = $val->name??'NA';


                // $this->db->select()->from('library_dropdown_data');
                // $this->db->where('library_dropdown_data.id', $value->subject);
                // $query = $this->db->get();
                // $val = $query->row();

                // $row[]     = $val->name??'NA';


                // $this->db->select()->from('library_dropdown_data');
                // $this->db->where('library_dropdown_data.id', $value->department);
                // $query = $this->db->get();
                // $val = $query->row();



                // $row[]     = $val->name??'NA';

                $this->db->select()->from('books_list');
                $this->db->where('lost',0);
                $this->db->where('book_id', $value->id);
                $query = $this->db->get();
                $valcount = $query->num_rows();


                $row[] = $valcount;


              
                $this->db->select('id')->from('books_list');
                $this->db->where('book_id', $value->id);
                $this->db->where('lost',0);
                $check2 = $this->db->get();
                $check2 = $check2->result_array();
                $arr2 = array_map (function($value){
                    return $value['id'];
                } , $check2);

if(count($arr2)> 0){
    $this->db->select()->from('book_issues');
    $this->db->where_in('book_id', $arr2);
    $this->db->where('is_returned',0);
    $query = $this->db->get();
    $val = $query->num_rows();
}else{
    $val = 0;

}
         
$val1 = $valcount -$val;



                $row[] = $val1;
                $row[]     = $val;


                if($value->perunitcost){
                    $row[]     = $currency_symbol . amountFormat($value->perunitcost);
                }else{
                    $row[]     = '';
                }



                $row[]     = $value->publishing_year;
                $row[]     = $this->customlib->dateformat($value->created_at);
                $row[]     = '<span>'.$viewbtn. ' ' .$editbtn . ' ' . $deletebtn.'</span><br><span><a data-id="'. $value->id.'" class="btn btn-danger btn-xs writeoff" data-toggle="tooltip" title="" data-original-title="Book Write Off">Book Write Off</a></span>';
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    /* function to get book inventory report by using datatable */
    public function dtbookissuereturnreportlist()
    {
        /* search code start from here */

        $book_category  = $this->input->post('book_category');
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }


        if (isset($_POST['book_category']) && $_POST['book_category'] != '') {
            $data['book_category'] = $_POST['book_category'];
        } else {
            $data['book_category'] = '';
        }

        $sch_setting = $this->sch_setting_detail;
        $start_date    = date('Y-m-d', strtotime($dates['from_date']));
        $end_date      = date('Y-m-d', strtotime($dates['to_date']));
        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

        /* search code ends here */
        $issued_books = $this->bookissue_model->getissuereturnMemberBooks(' ', $start_date, $end_date);

        $resultlist = json_decode($issued_books);
        $dt_data    = array();

        if (!empty($resultlist->data)) {

            $editbtn   = "";
            $deletebtn = "";

            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);

            foreach ($resultlist->data as $resultlist_key => $value) {

                $row = array();

                $abc =$value->book_category;
                $this->db->select()->from('book_category');
                $this->db->where('book_category.id', $abc);
                $query = $this->db->get();
                $book_categoryy = $query->row();
                
if($book_categoryy){
    $pq     = $book_categoryy->book_category;
}else{
    $pq    = 'NA';
}



                $row[] = $value->book_title;
                $row[] = $pq;
                $row[] = $value->book_no;
           
                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->issue_date));
                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->return_date));
                // $row[] = $value->members_id;
                $row[] = $value->library_card_no;

                
                if ($value->admission) {
                    $admission = ' (' . $value->admission . ')';
                    
                } else {
                    $admission = '';
                    
                }

              
                if ($value->member_type == 'student') {
                    $row[] = '<a href="'.base_url().'/student/view/'.$value->staff_id.'">'.$this->customlib->getFullName($value->fname, $value->mname, $value->lname, $sch_setting->middlename, $sch_setting->lastname) . $admission.'</a>';



                } else {

                    $row[] = '<a href="'.base_url().'/admin/staff/profile/'.$value->staff_id.'">'.$this->customlib->getFullName($value->fname, $value->mname, $value->lname, $sch_setting->middlename, $sch_setting->lastname) . $admission.'</a>';
                }
                
                
                $row[] = $this->lang->line($value->member_type);

                $dt_data[] = $row;

            }

        }

        $json_data = array(
            "draw"            => intval($resultlist->draw),
            "recordsTotal"    => intval($resultlist->recordsTotal),
            "recordsFiltered" => intval($resultlist->recordsFiltered),
            "data"            => $dt_data,
        );

        echo json_encode($json_data);
    }


    public function getstatelist()
    {
        $liststate       = $this->book_model->getstatelist();
        // print_r($liststate);
        // exit();
        $m               = json_decode($liststate);
       // $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';

               

                $name = $value->name;
               
                    $editbtn = "<a href='" . base_url() . "admin/location/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
               
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/location/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
               
                $row   = array();
                $row[] = $name;
                $row[] = $editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            // "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
    
    public function bulk_dlete()
    {
        $checkbox = $this->input->post('checkbox');


        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        // $data['title'] = 'Fees Master List';
        foreach($checkbox as $checkbo){
            $this->book_model->remove($checkbo);
        }
       
        echo 1;
    }



    public function bookcategory()
        {
            $data =array();
            $this->session->set_userdata('top_menu', 'Reports');
            $this->session->set_userdata('sub_menu', 'Reports/library');
            $this->session->set_userdata('subsub_menu', 'Reports/library/bookcategory');
  
            $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean|is_unique[book_category.book_category]');
            if ($this->form_validation->run() == true) {


                $data = array(
                   
                    'book_category'	 => $this->input->post('category'),
                    'description'        => $this->input->post('description'),
                    
                );
                
                $this->db->insert('book_category', $data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/book/bookcategory');

            }


           
            $this->db->select()->from('book_category');
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $book_category = $query->result();
            
            $data['book_category']  =$book_category;



            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/bookcategory', $data);
            $this->load->view('layout/footer', $data);

        }

        public function bookcategorydelete($id){

            $this -> db -> where('id', $id);
            $this -> db -> delete('book_category');
            $this->load->model('MY_Model');
            $message   = 'Delete_book_category' . " On id " . $id;
            $action    = "Delete";
            $record_id = $id;
            $this->load->model('MY_Model');
            $this->MY_Model->log($message, $record_id, $action);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
            redirect('admin/book/bookcategory');
    
     

        }


        public function  bookcategoryedit($id){


            $data =array();
            $this->session->set_userdata('top_menu', 'Reports');
            $this->session->set_userdata('sub_menu', 'Reports/library');
            $this->session->set_userdata('subsub_menu', 'Reports/library/bookcategory');
  
         
            $this->db->select()->from('book_category');
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $book_category = $query->result();
            $data['book_category']  =$book_category;


            $this->db->select()->from('book_category');
            $this->db->where('book_category.id', $id);
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $fet = $query->row();
            $data['fet']  =$fet;


            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/bookcategoryedit', $data);
            $this->load->view('layout/footer', $data);


        }


        public function  bookcategoryupdate(){

         
            $data =array();
            $this->session->set_userdata('top_menu', 'Reports');
            $this->session->set_userdata('sub_menu', 'Reports/library');
            $this->session->set_userdata('subsub_menu', 'Reports/library/bookcategory');
  
            $id= $this->input->post('id');
            $this->db->select()->from('book_category');
            $this->db->where('book_category.id', $id);
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $fet = $query->row();
            $data['fet']  =$fet;

            if($this->input->post('category') == $fet->book_category) {
                $is_unique =  '';
             } else {
                $is_unique  =  '|is_unique[book_category.book_category]';
             }
             
               $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean'.$is_unique);

                   

            if ($this->form_validation->run() == true) {


                $data = array(
                   
                    'book_category'	 => $this->input->post('category'),
                    'description'        => $this->input->post('description'),
                    
                );
                $this->db->where('id', $this->input->post('id'));
                $this->db->update('book_category', $data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/book/bookcategory');

            }

           
            $this->db->select()->from('book_category');
            $this->db->order_by('book_category.id', 'desc');
            $query = $this->db->get();
            $book_category = $query->result();
            $data['book_category']  =$book_category;

  


            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/bookcategoryedit', $data);
            $this->load->view('layout/footer', $data);


        }


        public function  book_location(){

         
            $data =array();
            $this->session->set_userdata('top_menu', 'Library');
            $this->session->set_userdata('sub_menu', 'book/book_location');


            $this->form_validation->set_rules('location','location', 'trim|required|xss_clean|is_unique[book_location.location]');
            if ($this->form_validation->run() == true) {


                $data = array(
                   
                    'location'	 => $this->input->post('location'),
                    'description'        => $this->input->post('description'),
                    'refrence_location'=> 0,
                    
                );
                
                $this->db->insert('book_location', $data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/book/book_location');

            }


            $this->db->select()->from('book_location');
            $this->db->order_by('book_location.id', 'desc');
            $this->db->where('book_location.refrence_location', 0);
            $query = $this->db->get();
            $book_location = $query->result();
            $data['book_location']  =$book_location;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/book_location', $data);
            $this->load->view('layout/footer', $data);



        }


        
        public function booklocationdelete($id){

            $this -> db -> where('id', $id);
            $this -> db -> delete('book_location');
            $this->load->model('MY_Model');
            $message   = 'Delete_book_location AND its refrences' . " On id " . $id;
            $action    = "Delete";
            $record_id = $id;
            $this->load->model('MY_Model');



            $this->MY_Model->log($message, $record_id, $action);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
            redirect('admin/book/book_location');
    
     

        }


        
        public function  booklocationedit($id){


            $data =array();
            $this->session->set_userdata('top_menu', 'Library');
            $this->session->set_userdata('sub_menu', 'book/book_location');
            $this->session->set_userdata('subsub_menu', 'book/book_location/location_edit');
  
         
            $this->db->select()->from('book_location');
            $this->db->order_by('book_location.id', 'desc');
            $this->db->where('book_location.refrence_location', 0);
            $query = $this->db->get();
            $book_location = $query->result();
            $data['book_location']  =$book_location;


            $this->db->select()->from('book_location');
            $this->db->where('book_location.id', $id);
            $this->db->order_by('book_location.id', 'desc');
            $query = $this->db->get();
            $fet = $query->row();
            $data['fet']  =$fet;


            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/book_locationedit', $data);
            $this->load->view('layout/footer', $data);


        }


        public function  booklocationupdate(){

         
            $data =array();
            $this->session->set_userdata('top_menu', 'Library');
            $this->session->set_userdata('sub_menu', 'book/book_location');
            $this->session->set_userdata('subsub_menu', 'book/book_location/location_edit');
  
            $id= $this->input->post('id');
            $this->db->select()->from('book_location');
            $this->db->where('book_location.id', $id);
            $this->db->order_by('book_location.id', 'desc');
            $query = $this->db->get();
            $fet = $query->row();
           


           

            if($this->input->post('location') == $fet->location) {
                $is_unique =  '';
             } else {
                $is_unique  =  '|is_unique[book_location.location]';
             }
             
               $this->form_validation->set_rules('location','location', 'trim|required|xss_clean'.$is_unique);

                     
  
            if ($this->form_validation->run() == true) {


                $data = array(
                   
                    'location'	 => $this->input->post('location'),
                    'description'        => $this->input->post('description'),
                    
                );
                
   



        
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('book_location', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/book/book_location');

            }

              

            

           
            $this->db->select()->from('book_location');
            $this->db->where('book_location.refrence_location', 0);
            $this->db->order_by('book_location.id', 'desc');
            
            $query = $this->db->get();
            $book_location = $query->result();
            $data['book_location']  =$book_location;

            $id= $this->input->post('id');
            $this->db->select()->from('book_location');
            $this->db->where('book_location.id', $id);
            $this->db->order_by('book_location.id', 'desc');
            $query = $this->db->get();
            $fet = $query->row();
            $data['fet']  =$fet;


            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/book_locationedit', $data);
            $this->load->view('layout/footer', $data);


        }


        



public function  book_sublocation(){

     $subsublocations= $this->input->post('data');
     $id= $this->input->post('id');


     $this->db->select()->from('book_location');
            $this->db->where('book_location.location', $subsublocations);
            $this->db->where('book_location.refrence_location', $id);
            $check = $this->db->get()->num_rows();


    if($check == 0){
        $data = array(
                   
            'location'	 => $subsublocations,
    
            'refrence_location'=> $id,
            
        );
        $this->db->insert('book_location', $data);
   echo "Record Saved Successfully";
    }else{


        echo "Error ! This location is already present for this refrence location";

    }
   
    
    


}


public function  book_append(){

    $id= $this->input->post('data');

  

  $id++;
  $this->db->select()->from('book_location');
  $this->db->where('book_location.refrence_location', 0);
  $this->db->order_by('book_location.id', 'Asc');
  
  $query = $this->db->get();
  $book_location = $query->result();
 $a = '<div class="row" id="remove_row'.$id.'">
  <div class="form-group col-md-3">
               <label for="exampleInputEmail1">Accession No.</label>
               <input id="isbn_no" name="book_no[]" placeholder="" data-id="'.$id.'" type="text" class="form-control check_book"  value="" />
              <input id="onlyforcheck" name="onlyforcheck[]" placeholder="" type="hidden" class="form-control onlyforcheck'.$id.'"  value="0" />
               <span class="text-danger check_book_found'.$id.'"></span>
               <span class="text-danger"></span>
           </div>
           <div class="form-group col-md-3">
               <label for="exampleInputEmail1">Location</label>

                <a id="chngecolor'.$id.'" class="form-control btn btn-info book_location" data-id="'.$id.'" style=""  >Location</a>
                <input  name="book_location[]" placeholder="" type="hidden" class="form-control abcd'.$id.'"  value="" />
               <span class="text-danger"></span>
           </div>

           <div class="form-group col-md-1">
               <label for="exampleInputEmail1" style="color: white;">n</label>
               <a class=" form-control btn btn-danger remove-input-field" data-id="'.$id.'">-</a>

                
               <span class="text-danger"></span>
           </div>
          
<!-- Modal for Location -->
<div class="modal fade" id="book_modal'.$id.'" role="dialog">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Set Book Location</h4>
</div>
<div class="modal-body">
<span class="">
<ol>';
foreach($book_location as $location1){ 

    $a .= '<li><label class="vertical-middle line-h-18">
<input value="'.$location1->id.'"
 type="radio"
name="location" class="location_refrence" data-id="'.$id.'"> 
'.$location1->location .'
</label>';
   
   
$this->db->select()->from('book_location');
$this->db->where('book_location.refrence_location', $location1->id);
$this->db->order_by('book_location.id', 'Asc');

$queryy = $this->db->get();
$book_locationn = $queryy->result();

$a .=      '<ol type="A">'  ;        
foreach($book_locationn as $location2){     
    $a .=   '<li><label class="vertical-middle line-h-18">
           <input value="'.$location2->id.'"
            type="radio"
           name="location" class="location_refrence" data-id="'.$id.'"> 
           '.$location2->location.'
       </label>';
     
$this->db->select()->from('book_location');
$this->db->where('book_location.refrence_location', $location2->id);
$this->db->order_by('book_location.id', 'Asc');

$queryyy = $this->db->get();
$book_locationnn = $queryyy->result();

    

$a .=  '<ol type="I">';
foreach($book_locationnn as $location3){    
$a .=   '<li><label class="vertical-middle line-h-18">
           <input value="'.$location3->id.'"
            type="radio"
           name="location" class="location_refrence" data-id="'.$id.'"> 
          '.$location3->location.'
       </label>';
    
$this->db->select()->from('book_location');
$this->db->where('book_location.refrence_location', $location3->id);
$this->db->order_by('book_location.id', 'Asc');

$queryyyy = $this->db->get();
$book_locationnnn = $queryyyy->result();

  
$a .=  '<ol type="a">';          
foreach($book_locationnnn as $location4){     

$a .='<li><label class="vertical-middle line-h-18">
           <input value="'.$location4->id.'"
            type="radio"
           name="location" class="location_refrence" data-id="'.$id.'"> 
           '.$location4->location.'
       </label>
       </li>';
 }
$a .='</ol>
</li>';

 }
 $a .='</ol></li>';        
}
 $a .='</ol>   
   
   </li>';                              
}
 $a .='</ol>
</span>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
</div>
<!-- Modal for Location -->
        
           <div class="clearfix"></div>
</div>';





echo $a;



}

public function  dropdowndata(){
    // echo 1;die;
    $type= $this->input->post('type');
    $info= $this->input->post('info');


  $data = array(
       'name' => $info,
       'type' => $type,
  );

  $this->db->insert('library_dropdown_data', $data);
  redirect('admin/book/create');
}



public function  dropdowndataa(){
    // echo 1;die;
    $type= $this->input->post('type');
    $info= $this->input->post('info');


  $data = array(
       'name' => $info,
       'type' => $type,
  );

  $this->db->insert('library_dropdown_data', $data);


  $this->db->select()->from('library_dropdown_data');
  $this->db->order_by('library_dropdown_data.id', 'desc');
  $query = $this->db->get();
  $dropdowndata = $query->result();

$a = "<option value=''>Select</option>";
$b = "<option value=''>Select</option>";
$c = "<option value=''>Select</option>";
$d = "<option value=''>Select</option>";
$e = "<option value=''>Select</option>";
$x = "<option value=''>Select</option>";
 foreach($dropdowndata as $drop){
    if($drop->type == 1){  
        $a .= '<option value="'.$drop->id.'">'.$drop->name.'</option>';
       } }

       foreach($dropdowndata as $drop){
        if($drop->type == 2){  
            $b .= '<option value="'.$drop->id.'">'.$drop->name.'</option>';
           } }

           foreach($dropdowndata as $drop){
            if($drop->type == 3){  
                $c .= '<option value="'.$drop->id.'">'.$drop->name.'</option>';
               } }

               foreach($dropdowndata as $drop){
                if($drop->type == 4){  
                    $d .= '<option value="'.$drop->id.'">'.$drop->name.'</option>';
                   } }
           
                   foreach($dropdowndata as $drop){
                    if($drop->type == 5){  
                        $e .= '<option value="'.$drop->id.'">'.$drop->name.'</option>';
                       } }


                       $x = $a.'~'.$b.'~'.$c.'~'.$d.'~'.$e;

                       echo $x;
                  
}

public function  viewbooks(){
    $masterid= $this->input->post('masterid');
  
    $check_array = array();
  
    
    $this->db->select()->from('books_list');
    $this->db->where('book_id',$masterid);
    // $this->db->where('lost',0);
    $this->db->order_by('books_list.id', 'desc');
    $query = $this->db->get();
    $books = $query->result();
    $a = "";
  $count = 0;
$a .= "<Style>.overlay {
  position: absolute;
  background: rgb(135 175 187 / 62%);
  left: 0em;
  right: 0em;
  height: 1.2em;
  text-align: center !important;
}

tr {
  position: relative;
}
  </style><div class='col-md-12' style='text-align: end;margin-bottom: 3px;'>
<a  href='" . base_url() . "admin/book/addparticular/" . $masterid . "' class='btn btn-default btn-xs' title='Add' data-toggle='tooltip'><i class='fa fa-plus'> Add Accession No.</i></a>
</div>

<form action='" . base_url() . "admin/book/deletecopybulk' method='post'>
<table  class='table table-hover table-striped table-bordered example dataTable no-footer'> 
<thead>
<tr>
<th>Sr <button type='submit' style='cursor:pointer' class='btn btn-danger btn-xs bulkdelcopy'><i class='fa fa-trash'></i></button>
</th>
<th>Accession No.</th>
<th>Location</th>
<th>Action</th>
</tr>
</thead>
<tbody>";
        foreach($books as $book){


            if (in_array($book->bookcode, $check_array))
            {
                 $style = "style='background-color:yellow !important'";
                 $question = 'checked';
            }
          else
            {
                $style ="";
                $question = '';
            }     



            $count++ ;
            $b = "";
            $c = "";
            $d = "";
            $e = "";
            $a .= "<tr ". $style."><td><span style='display: flex;'>".$count."<input name='checkboxcopy[]' class='idc' type='checkbox' value='".$book->id."' ".$question." style='margin-right:5px' autocomplete='off'></span></td>
            
            
            <td>".$book->bookcode."</td>"."<td ><span>";
             $this->db->select()->from('book_location');
             $this->db->where('id',$book->location);
            $query = $this->db->get();
            $location1 = $query->row();
            if($location1){
                $b = $location1->location;

                $this->db->select()->from('book_location');
                $this->db->where('id',$location1->refrence_location);
                $query = $this->db->get();
                $location2 = $query->row();
                if($location2){
                    $c .= $location2->location. ' -> ';
                    $this->db->select()->from('book_location');
                    $this->db->where('id',$location2->refrence_location);
                    $query = $this->db->get();
                    $location3 = $query->row();
                    if($location3){
                        $d .=  $location3->location. ' -> ';
                        $this->db->select()->from('book_location');
                        $this->db->where('id',$location3->refrence_location);
                        $query = $this->db->get();
                        $location4 = $query->row();
                        if($location4){
                            $e .= $location4->location. ' -> ';
                        }
                    }
                }
            }
           
            $a .=  $e.$d.$c.$b;
            
            
            $a .="</span></td>";
            $a .= "<td ><span>
            <a href='" . base_url() . "admin/book/editbookparticular/" . $book->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>
            <a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/deleteparticular/" . $book->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>
            </span></td>";

            if($book->lost == 1){
$a .= "<td class='overlay'>Book Lost</td>";
            }
            
            
          $a .= "</tr>";
    
            array_push($check_array,$book->bookcode);
        }

$a .= "</tbody></table></form>";


echo $a;
}




public function deleteparticular($id)
{
    if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
        access_denied();
    }
    $data['title'] = 'Fees Master List';
    $this->db->where('id', $id);
    $this->db->delete('books_list');
     


    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
    redirect('admin/book/getall');
}


public function editbookparticular($id)
{
// echo $id;die;
    if (!$this->rbac->hasPrivilege('books', 'can_edit')) {
        access_denied();
    }
    $check_array = array();
    $data['title']      = 'Edit Book Copy';
    $data['title_list'] = 'Book Copy';
    $data['id']         = $id;




    $this->db->select()->from('books_list');
    
    $this->db->where('id',$id);
    $query = $this->db->get();
    $editbook = $query->row();

    $this->db->select()->from('book_location');
    $this->db->where('book_location.refrence_location', 0);
    $this->db->order_by('book_location.id', 'desc');
    
    $query = $this->db->get();
    $book_location = $query->result();
    $data['book_location']  =$book_location;

    $data['editbook']   = $editbook;


    $a = "";
    $b = "";
    $c = "";
    $d = "";
    $e = "";



    $this->db->select()->from('book_location');
             $this->db->where('id',$editbook->location);
            $query = $this->db->get();
            $location1 = $query->row();
            if($location1){
                $b = $location1->location;

                $this->db->select()->from('book_location');
                $this->db->where('id',$location1->refrence_location);
                $query = $this->db->get();
                $location2 = $query->row();
                if($location2){
                    $c .= $location2->location. ' -> ';
                    $this->db->select()->from('book_location');
                    $this->db->where('id',$location2->refrence_location);
                    $query = $this->db->get();
                    $location3 = $query->row();
                    if($location3){
                        $d .=  $location3->location. ' -> ';
                        $this->db->select()->from('book_location');
                        $this->db->where('id',$location3->refrence_location);
                        $query = $this->db->get();
                        $location4 = $query->row();
                        if($location4){
                            $e .= $location4->location. ' -> ';
                        }
                    }
                }
            }
           
            $a .=  $e.$d.$c.$b;
            $data['z']   = $a;


            $this->db->select()->from('books');
            $this->db->where('id',$editbook->book_id);
            $query = $this->db->get();
            $booktitle = $query->row();
            $data['booktitle']   = $booktitle->book_title;
            
    $this->db->select()->from('books_list');
    $this->db->where('book_id',$editbook->book_id);
  
    $this->db->order_by('books_list.id', 'desc');
    $query = $this->db->get();
    $books = $query->result();
    $count= 0;
    $a ="";
            foreach($books as $book){

if($book->lost == 1){
    $style ="style='background-color:rgb(135 175 187 / 62%) !important;color:'";
}else{
    if (in_array($book->bookcode, $check_array))
                {
                     $style = "style='background-color:yellow !important'";
                }
              else
                {
                    $style ="";
                }     


    
}
            
            

                $count++;
                $b = "";
                $c = "";
                $d = "";
                $e = "";
                $a .= "<tr ". $style."><td>".$count."</td>"."<td>".$book->bookcode."</td>"."<td ><span>";
                 $this->db->select()->from('book_location');
                 $this->db->where('id',$book->location);
                $query = $this->db->get();
                $location1 = $query->row();
                if($location1){
                    $b = $location1->location;
    
                    $this->db->select()->from('book_location');
                    $this->db->where('id',$location1->refrence_location);
                    $query = $this->db->get();
                    $location2 = $query->row();
                    if($location2){
                        $c .= $location2->location. ' -> ';
                        $this->db->select()->from('book_location');
                        $this->db->where('id',$location2->refrence_location);
                        $query = $this->db->get();
                        $location3 = $query->row();
                        if($location3){
                            $d .=  $location3->location. ' -> ';
                            $this->db->select()->from('book_location');
                            $this->db->where('id',$location3->refrence_location);
                            $query = $this->db->get();
                            $location4 = $query->row();
                            if($location4){
                                $e .= $location4->location. ' -> ';
                            }
                        }
                    }
                }
               
                $a .=  $e.$d.$c.$b;
                
                
                $a .="</span></td>";
                $a .= "<td ><span>
                <a href='" . base_url() . "admin/book/editbookparticular/" . $book->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>
                <a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/deleteparticularr/" . $book->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>
                </span></td></tr>";
                array_push($check_array,$book->bookcode);
        
        
            }

            $data['a']   = $a;



    $this->form_validation->set_rules('book_no', 'Accession No.', 'trim|required|xss_clean');
    $this->form_validation->set_rules('book_location', 'Book Location', 'trim|required|xss_clean');
    if ($this->form_validation->run() == true) {


        $data = array(
            'bookcode'	 => $this->input->post('book_no'),
            'location'        => $this->input->post('book_location'),
        );


    $this->db->where('id', $this->input->post('idd'));
    $this->db->update('books_list', $data);
    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
    redirect('admin/book/addparticular/'.$editbook->book_id);

    }




            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/book_copyedit', $data);
            $this->load->view('layout/footer', $data);



}


public function addparticular($id)
{
    if (!$this->rbac->hasPrivilege('books', 'can_add')) {
        access_denied();
    }
$check_array = array();


    $data['title']      = 'Edit Book Copy';
    $data['title_list'] = 'Book Copy';
    $data['id']         = $id;



    $this->db->select()->from('book_location');
    $this->db->where('book_location.refrence_location', 0);
    $this->db->order_by('book_location.id', 'desc');
    
    $query = $this->db->get();
    $book_location = $query->result();
    $data['book_location']  =$book_location;



            $this->db->select()->from('books');
            $this->db->where('id',$id);
            $query = $this->db->get();
            $booktitle = $query->row();
            $data['booktitle']   = $booktitle->book_title;
            
    $this->db->select()->from('books_list');
  
    $this->db->where('book_id',$id);
    $this->db->order_by('books_list.id', 'desc');
    $query = $this->db->get();
    $books = $query->result();
    $count= 0;
    $a ="";
            foreach($books as $book){

               
                if($book->lost == 1){
                    $style ="style='background-color:rgb(135 175 187 / 62%) !important;color:'";
                }else{
                if (in_array($book->bookcode, $check_array))
                {
                     $style = "style='background-color:yellow !important'";
                }
              else
                {
                    $style ="";
                }     
            }
                $count++;
                $b = "";
                $c = "";
                $d = "";
                $e = "";
                $a .= "<tr ". $style."><td>".$count."</td>"."<td>".$book->bookcode."</td>"."<td ><span>";
                 $this->db->select()->from('book_location');
                 $this->db->where('id',$book->location);
                $query = $this->db->get();
                $location1 = $query->row();
                if($location1){
                    $b = $location1->location;
    
                    $this->db->select()->from('book_location');
                    $this->db->where('id',$location1->refrence_location);
                    $query = $this->db->get();
                    $location2 = $query->row();
                    if($location2){
                        $c .= $location2->location. ' -> ';
                        $this->db->select()->from('book_location');
                        $this->db->where('id',$location2->refrence_location);
                        $query = $this->db->get();
                        $location3 = $query->row();
                        if($location3){
                            $d .=  $location3->location. ' -> ';
                            $this->db->select()->from('book_location');
                            $this->db->where('id',$location3->refrence_location);
                            $query = $this->db->get();
                            $location4 = $query->row();
                            if($location4){
                                $e .= $location4->location. ' -> ';
                            }
                        }
                    }
                }
               
                $a .=  $e.$d.$c.$b;
                
                
                $a .="</span></td>";
                $a .= "<td ><span>
                <a href='" . base_url() . "admin/book/editbookparticular/" . $book->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>
                <a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/deleteparticularr/" . $book->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>
                </span></td></tr>";
          

                array_push($check_array,$book->bookcode);
            }

            $data['a']   = $a;



    $this->form_validation->set_rules('book_no', 'Accession No.', 'trim|required|xss_clean');
    $this->form_validation->set_rules('book_location', 'Book Location', 'trim|required|xss_clean');
    if ($this->form_validation->run() == true) {

        $userdata        = $this->customlib->getUserData();
        $data = array(
            'book_id'	 => $this->input->post('idd'),
            'bookcode'	 => $this->input->post('book_no'),
            'location'        => $this->input->post('book_location'),
            'created_by' => $userdata['email'],
        );

    $this->db->insert('books_list', $data);
    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
    redirect('admin/book/addparticular/'.$id);

    }




            $this->load->view('layout/header', $data);
            $this->load->view('admin/book/add_bookcopy.php', $data);
            $this->load->view('layout/footer', $data);

}


public function deleteparticularr($id)
{
    if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
        access_denied();
    }
   
    $this->db->select()->from('books_list');
    $this->db->where('id',$id);
    $query = $this->db->get();
    $editbook = $query->row();
    $data['editbook']   = $editbook;



    $this->db->where('id', $id);
    $this->db->delete('books_list');
     


    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
    redirect('admin/book/addparticular/'.$editbook->book_id);
}

public function searchbook()
{

   $book = $this->input->post('book');

 $this->db->select()->from('books');
 $this->db->like('book_title', $book);
 $this->db->or_like('isbn_no', $book);
 $this->db->or_like('tags', $book);
 $this->db->where('books.writeoff',0);
 $query =  $this->db->get();
 $check1 = $query->result_array();
 $arr1 = array_map (function($value){
    return $value['id'];
} , $check1);



 $this->db->select('id')->from('library_dropdown_data');
        $this->db->like('name', $book);
        $check2 = $this->db->get();
        $check2 = $check2->result_array();
   
        $arr0 = array_map (function($value){
            return $value['id'];
        } , $check2);


        if(count($arr0) > 0){

      
        $this->db->select()->from('books');
        $this->db->where_in('subject', $arr0);
        $query =  $this->db->get();
        $check1 = $query->result_array();
        $arr2 = array_map (function($value){
           return $value['id'];
       } , $check1);


       $this->db->select()->from('books');
       $this->db->where_in('author', $arr0);
       $query =  $this->db->get();
       $check1 = $query->result_array();
       $arr3 = array_map (function($value){
          return $value['id'];
      } , $check1);


      $this->db->select()->from('books');
      $this->db->where_in('publish', $arr0);
      $query =  $this->db->get();
      $check1 = $query->result_array();
      $arr4 = array_map (function($value){
         return $value['id'];
     } , $check1);


     $this->db->select()->from('books');
     $this->db->where_in('department', $arr0);
     $query =  $this->db->get();
     $check1 = $query->result_array();
     $arr5 = array_map (function($value){
        return $value['id'];
    } , $check1);

 
// Merge the arrays
$mergedArr = array_merge($arr1, $arr2, $arr3, $arr4, $arr5);

// Remove duplicates
$uniqueArr = array_unique($mergedArr);


        }else{
$uniqueArr = $arr1;

        }

       

////////////////////////////////
$a = '<option val="">Select</option>';
if(count($uniqueArr) > 0){

    $this->db->select()->from('books');
    $this->db->where_in('id', $uniqueArr);
    $query =  $this->db->get();
    $datas = $query->result();


    foreach($datas as $data){
    


        $this->db->select('*')->from('library_dropdown_data');
        $this->db->where('id', $data->author);
        $check2 = $this->db->get();
        $check2 = $check2->row();
        $author = $check2->name??'';
        $this->db->select('*')->from('library_dropdown_data');
        $this->db->where('id`', $data->publish);
        $check2 = $this->db->get();
        $check2 = $check2->row();
        $publish = $check2->name??'';


        $a .= '<option value="'.$data->id.'">'.$data->book_title.'(by '.$author.', '.$publish.' ,'.$data->publishing_year.')</option>';  
    
    
    }


}else{
    $a .= '<option value="">No Related Data Found</option>';  

}

  
    
echo $a ;




}


public function searchbookno(){

    $book = $this->input->post('book');


    $this->db->select('books_list.*,IFNULL(total_issue, "0") as `total_issue` ');
    $this->db->join(" (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0  GROUP by book_id) as `book_count`", "books_list.id=book_count.book_id", "left");
    $this->db->where('books_list.book_id', $book);
    $this->db->where('books_list.lost',0);
    $this->db->order_by('books_list.id','desc');
    $this->db->from('books_list');

    $check = $this->db->get();
    $check = $check->result();

    $a = "";
    foreach($check as $data){
        if($data->total_issue == 0){
            $a .=  "<option value='".$data->id."'>".$data->bookcode."</option>";
        }
    }

    echo $a;
   

}


public function importcopies($id)
    {


        $data["fields"]   = array('bookcode','location');
        $data["id"] = $id;
        $this->form_validation->set_rules('file', $this->lang->line('images'), 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/book/importbookcopies', $data);
            $this->load->view('layout/footer');
        } else {


            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    $fields = $data["fields"];
                   


                    if (!empty($result)) {
                           // $rowcount = 0;

                            for ($i = 1; $i <= count($result); $i++) {

                            $book_data[$i] = array();
                            $n                = 0;

                        foreach ($result[$i] as $r_key => $r_value) 
                        {

                            
                           $book_data[$i][$fields[$n]] =  $this->encoding_lib->toUTF8($result[$i][$r_key]) ;

                           $book_data[$i]['book_id'] = $id;

                           $n++;

                              }

                        }
                     
                        $this->db->insert_batch('books_list', $book_data);

                    }
                   
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_csv_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully'));
                }
            } else {
                $msg = array(
                    'e' => $this->lang->line('the_file_field_is_required'),
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . "  " . $this->lang->line('records_found_in_csv_file_total') . " " . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
            redirect('admin/book/importcopies/'.$id);
        }



    }

    public function exportformatt()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_copies_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_book_copies_sample_file.csv';
        force_download($name, $data);
    }


    public function dropdown_master()
    {
        

        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');

        $data['title']      = 'Book Dropdown Master';
        $data['title_list'] = 'Book Dropdown Details';

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;


        $this->load->view('layout/header');
        $this->load->view('admin/book/dropdownmaster', $data);
        $this->load->view('layout/footer');




    }


    
    public function  dropdowndatamaster(){
        // echo 1;die;
        $type= $this->input->post('type');
        $info= $this->input->post('info');
    
    
      $data = array(
           'name' => $info,
           'type' => $type,
      );
    
      $this->db->insert('library_dropdown_data', $data);
      $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Data Added Successfully</div>');

      redirect('admin/book/dropdown_master');
    }
    



    

    public function dropdata_del()
    {


     
        $id= $this->input->post('id');




        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->from('library_dropdown_data');
        $fet =   $this->db->get()->row(); 
        
        $hidden = $fet->type;
        
        if($hidden == 1){
         $q = 'publish';
}else if ($hidden == 2){
    $q = 'author';
}else if ($hidden == 3){
    $q = 'subject';
}else if ($hidden == 4){
    $q = 'book_language';

}else{
    $q = 'department';
} 



$this->db->select('*');
$this->db->where($q, $id);
$this->db->from('books');
$coub =   $this->db->get()->num_rows(); 

if($coub > 0){

    echo 0;
  
}else{

  
    if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
        access_denied();
    }
    $data['title'] = 'Delete Dropdown Data';
   
    $this->db->where('id', $id);
    $this->db->delete('library_dropdown_data');
echo 1;
}










    }


    
   
    public function  dropdowndataimport(){
        
        $type= $this->input->post('type');
        $info= $this->input->post('info');
    
    
      $data = array(
           'name' => $info,
           'type' => $type,
      );
    
      $this->db->insert('library_dropdown_data', $data);
      $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Data Added Successfully</div>');

      redirect('admin/book/import');
    }
    



    public function check_dropdata()
    {


        $a = "";
        $id= $this->input->post('id');
        $value= $this->input->post('value');

    $this->db->select()->from('library_dropdown_data');
    $this->db->where('library_dropdown_data.name',$value);
    $this->db->where('type', $id);
    $query = $this->db->get();
    $dropdowndata = $query->result();


    if(count($dropdowndata) > 0){
        
        $a .= "<div class='row'><div class='alert alert-danger'>Similar Data Found</div>";
        

         $count= 0;

        foreach($dropdowndata as $data){ $count++;
               $a .="<div class='col-md-6'><div class='row'>";
                $a .="<div class='col-md-2'>".$count."</div>";
            $a .= "<div class='col-md-10'>".$data->name."</div>"; 
            $a .="</div></div>";
        }
        
        $a .= "</div>";


    }

         echo $a;


    }




    
    public function check_duplicytitle()
    {
        $a = "";
      
        $value= $this->input->post('value');

    $this->db->select()->from('books');
    $this->db->like('books.book_title',$value, 'both');
    $query = $this->db->get();
    $check_duplicytitle = $query->result();


    if(count($check_duplicytitle) > 0){
        $a .= "<div class='row'><div class='alert alert-danger'>Similar Book Titles Found</div><table class='table table-striped table-bordered table-hover no-footer '>";
        $a .= "<thead><th>Sr</th><th>Title</th><th>Publisher</th><th>Author</th><th>Subject</th><th>Pages Count</th></thead><tbody>";
         $count= 0;
        foreach($check_duplicytitle as $data){ $count++;
               $a .="<tr>";
                $a .="<td>".$count."</td>";
            $a .= "<td>".$data->book_title."</td>"; 
            $this->db->select()->from('library_dropdown_data');
            $this->db->where('library_dropdown_data.id', $data->publish);
            $query = $this->db->get();
            $val = $query->row();
            $a .= "<td>".$val->name??'NA'."</td>"; 
            $this->db->select()->from('library_dropdown_data');
            $this->db->where('library_dropdown_data.id', $data->author);
            $query = $this->db->get();
            $val = $query->row();
            $a .= "<td>".$val->name??'NA'."</td>"; 
            $this->db->select()->from('library_dropdown_data');
            $this->db->where('library_dropdown_data.id', $data->subject);
            $query = $this->db->get();
            $val = $query->row();
            $a .= "<td>".$val->name??'NA'."</td>"; 
            $a .= "<td>".$data->pages_count??'NA'."</td>"; 
            $a .="</tr>";
        }
        $a .= "</tbody></table></div>";
    }
         echo $a;
    }


    public function check_duplicybookcode()
    {
        $a = "";
      
        $value= $this->input->post('value');

    $this->db->select()->from('books_list');
    $this->db->where('books_list.bookcode',$value);
    $query = $this->db->get();
    $check_duplicybookcode = $query->num_rows();
    echo $check_duplicybookcode;
}





    public function getallfilters($id)
    {

        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
    



        $this->db->select()->from('books');
        $this->db->where('books.writeoff',0);
		$this->db->group_by('book_title');
        $this->db->order_by('books.id', 'desc');
        $query = $this->db->get();
        $titldata = $query->result();
        $data['titldata']  =$titldata;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;



        $this->db->select('books.created_by')->from('books');
        $this->db->where('books.writeoff',0);
        $this->db->group_by('created_by');// add group_by
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $created_bylist = $listbook->result();
        $data['created_bylist']  =$created_bylist; 





        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;
        $id = intval($id);
 





        
    $val1 = $id - 15;
    $val2 = $id + 15;
 


 $data['ai']  =$id;
 $data['val1']  =$val1;
 $data['val2']  =$val2;
 
 


$book_title = $this->input->post('book_title');
$bookcategory = $this->input->post('book_category');
$author = $this->input->post('author');
$publisher = $this->input->post('publisher');
$avaiblity = $this->input->post('Avaiblity');
$writeoff = $this->input->post('writeoff');


$created_by = $this->input->post('created_by');

if (isset($_POST['created_from']) && $_POST['created_from'] != '') {
    $created_from =  date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('created_from'))));
} else {
    $created_from =  $this->input->post('created_from');
}



if (isset($_POST['created_to']) && $_POST['created_to'] != '') {
    $created_to =   date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('created_to'))));
} else {
    $created_to =  $this->input->post('created_to');
}



 
$this->load->library('session');
$this->session->set_userdata(array(
    'book_title'  => $book_title,
    'bookcategory' =>  $bookcategory,
    'author'  =>  $author,
    'publisher'     =>  $publisher,
    'avaiblity'   =>  $avaiblity,
    'created_by'  =>  $created_by,
    'created_from'     =>  $created_from,
    'created_to'   =>  $created_to,
    'writeoff'   =>  $writeoff,
));






        $listbook         = $this->book_model->getbooklist2($id,$book_title,$bookcategory,$author,$publisher,$avaiblity,$created_by,$created_from,$created_to,$writeoff);
        $data['listbook']  = $listbook;


     
      $this->db->select()->from('books');

      if($book_title){
        $this->db->where("book_title", $book_title);
    }
    if($bookcategory){
        $this->db->where("book_category", $bookcategory);
    }
    if($author){
        $this->db->where("author", $author);
    }
    if($publisher){
        $this->db->where("publish", $publisher);
    }
    if($avaiblity){
        $this->db->where("subject", $avaiblity);
    }
    
if($created_by){
    $this->db->where("created_by", $created_by);
}
if($created_from){
    $this->db->where("created_att >=", $created_from);
}
if($created_to){
    $this->db->where("created_att <=", $created_to);
}

if($writeoff){
    $this->db->where("writeoff",1);
}else{
    $this->db->where('books.writeoff',0);

}



      $this->db->order_by("id", "desc");
      $listbook = $this->db->get();
      $count = $listbook->num_rows();
      $data['countt']  =$count; 
       

    
    

 $check1 = $listbook->result_array();
$arr5 = array_map (function($value){
return $value['id'];
} , $check1);


if(count($arr5) > 0){

    $this->db->select()->from('books_list');
    $this->db->where_in('book_id', $arr5);
    if($writeoff){
        $this->db->where("lost",2);
    }else{
        $this->db->where('lost',0);
    
    }
    $this->db->where('lost',0);
    $this->db->order_by("id", "desc");
    $listbook = $this->db->get();
    $totalcopies = $listbook->num_rows();
    $data['totalcopies']  =$totalcopies; 
}else{
   
    $data['totalcopies']  = 0; 

}


   


        $this->load->view('layout/header');
        $this->load->view('admin/book/getbooklist2',$data);
        $this->load->view('layout/footer');
    }




    public function getallpagination($id)
    {

        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');



        $this->db->select()->from('books');
        $this->db->where('books.writeoff',0);
		$this->db->group_by('book_title');
        $this->db->order_by('books.id', 'desc');
        $query = $this->db->get();
        $titldata = $query->result();
        $data['titldata']  =$titldata;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;

        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;


        $this->db->select('books.created_by')->from('books');
        $this->db->where('books.writeoff',0);
        $this->db->group_by('created_by');// add group_by
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $created_bylist = $listbook->result();
        $data['created_bylist']  =$created_bylist; 


        
        $id = intval($id);
 
        
    $val1 = $id - 15;
    $val2 = $id + 15;
 


 $data['ai']  =$id;
 $data['val1']  =$val1;
 $data['val2']  =$val2;
 
 


$book_title = $this->session->userdata('book_title');
$bookcategory =  $this->session->userdata('bookcategory');
$author =  $this->session->userdata('author');
$publisher = $this->session->userdata('publisher');
$avaiblity =  $this->session->userdata('avaiblity');
$created_by =  $this->session->userdata('created_by');
$created_from = $this->session->userdata('created_from');
$created_to =  $this->session->userdata('created_to');
$writeoff = $this->input->post('writeoff');
// echo $book_title; echo "<br>";
// echo $bookcategory;echo "<br>";
// echo $author;echo "<br>";
// echo $publisher;echo "<br>";
// echo $avaiblity;

$listbook         = $this->book_model->getbooklist2($id,$book_title,$bookcategory,$author,$publisher,$avaiblity,$created_by,$created_from,$created_to,$writeoff);
        $data['listbook']  = $listbook;


     
      $this->db->select()->from('books');

      if($book_title){
        $this->db->where("book_title", $book_title);
    }
    if($bookcategory){
        $this->db->where("book_category", $bookcategory);
    }
    if($author){
        $this->db->where("author", $author);
    }
    if($publisher){
        $this->db->where("publish", $publisher);
    }
    if($avaiblity){
        $this->db->where("subject", $avaiblity);
    }
    
if($created_by){
    $this->db->where("created_by", $created_by);
}
if($created_from){
    $this->db->where("created_att >=", $created_from);
}
if($created_to){
    $this->db->where("created_att <=", $created_to);
}
if($writeoff){
    $this->db->where("writeoff",1);
}else{
    $this->db->where('books.writeoff',0);

}



      $this->db->order_by("id", "desc");
      $listbook = $this->db->get();
      $count = $listbook->num_rows();

      $data['countt']  =$count; 
       

    

      $check1 = $listbook->result_array();
      $arr5 = array_map (function($value){
      return $value['id'];
      } , $check1);
      
      
      
      
      if(count($arr5) > 0){
            $this->db->select()->from('books_list');
              if($writeoff){
        $this->db->where("lost",2);
    }else{
        $this->db->where('lost',0);
    
    }
            $this->db->where_in('book_id', $arr5);
            $this->db->order_by("id", "desc");
            $listbook = $this->db->get();
            $totalcopies = $listbook->num_rows();
            $data['totalcopies']  =$totalcopies; 
      }else{
        $data['totalcopies']  =0; 

      }



        $this->load->view('layout/header');
        $this->load->view('admin/book/getbooklist2',$data);
        $this->load->view('layout/footer');
    }


    
    public function deletecopybulk()
    {
        $checkbox = $this->input->post('checkboxcopy');


        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        // $data['title'] = 'Fees Master List';
        foreach($checkbox as $checkbo){
            
          
            $this->db->where('id', $checkbo);
            $this->db->delete('books_list');
          
        }
       
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/book/getall');
    }

    public function searchbybookcode()
    {
    
        $bookcode = $this->input->post('book_code');


        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');



        $this->db->select()->from('books');
		$this->db->group_by('book_title');
        $this->db->order_by('books.id', 'desc');
        $query = $this->db->get();
        $titldata = $query->result();
        $data['titldata']  = $titldata;

        $this->db->select()->from('library_dropdown_data');
        $this->db->order_by('library_dropdown_data.id', 'desc');
        $query = $this->db->get();
        $dropdowndata = $query->result();
        $data['dropdowndata']  =$dropdowndata;

        $this->db->select()->from('book_category');
        $this->db->order_by('book_category.id', 'desc');
        $query = $this->db->get();
        $book_category = $query->result();
        $data['book_category']  =$book_category;


        $this->db->select()->from('books_list');
        $this->db->where('lost',0);
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $totalcopies = $listbook->num_rows();
        $data['totalcopies']  =$totalcopies; 
  
        $this->db->select('books.created_by')->from('books');
        $this->db->group_by('created_by');// add group_by
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        $created_bylist = $listbook->result();
        $data['created_bylist']  =$created_bylist; 


      

        $this->db->select('books.*,books_list.bookcode,books_list.location,books_list.lost')->from('books');
        $this->db->join('books_list', 'books_list.book_id = books.id');
        $this->db->where('books_list.bookcode', $bookcode);
        $this->db->order_by('books_list.id');
        $query = $this->db->get();
        $bookbycode = $query->result();


        $data['datapre']  =$bookcode; 
      
$data['bookbycode']  =$bookbycode; 


        $this->load->view('layout/header');
        $this->load->view('admin/book/getbybookcode',$data);
        $this->load->view('layout/footer');



    }



    public function updatedropdown()
    {
    

        $id= $this->input->post('id');
        $value= $this->input->post('value');

  
    $this->db->where('id', $id);
  
    $this->db->update('library_dropdown_data',array( 'name'	 => $value));
    echo 1;
}
    


public function searchbookduplicacy()
{

    if (!$this->rbac->hasPrivilege('books', 'can_view')) {
        access_denied();
    }
    // $this->session->set_userdata('top_menu', 'Library');
    // $this->session->set_userdata('sub_menu', 'book/getsearchbookduplicacy');

    $this->session->set_userdata('top_menu', 'Reports');
    $this->session->set_userdata('sub_menu', 'Reports/library');
    $this->session->set_userdata('subsub_menu', 'Reports/library/searchbookduplicacy');



    $this->db->select()->from('books');
    $this->db->where('books.writeoff',0);
    $this->db->group_by('book_title');
    $this->db->order_by('books.id', 'desc');
    $query = $this->db->get();
    $titldata = $query->result();
    $data['titldata']  = $titldata;
    
    $this->db->select()->from('library_dropdown_data');
    $this->db->order_by('library_dropdown_data.id', 'desc');
    $query = $this->db->get();
    $dropdowndata = $query->result();
    $data['dropdowndata']  =$dropdowndata;
    
    $this->db->select()->from('book_category');
    $this->db->order_by('book_category.id', 'desc');
    $query = $this->db->get();
    $book_category = $query->result();
    $data['book_category']  =$book_category;
    
    
    $this->db->select()->from('books_list');
    $this->db->where('lost',0);
    $this->db->order_by("id", "desc");
    $listbook = $this->db->get();
    $totalcopies = $listbook->num_rows();
    $data['totalcopies']  =$totalcopies; 
    
    $this->db->select('books.created_by')->from('books');
    $this->db->group_by('created_by');// add group_by
    $this->db->order_by("id", "desc");
    $listbook = $this->db->get();
    $created_bylist = $listbook->result();
    $data['created_bylist']  =$created_bylist; 
    
    
    





   $this->db->select('books.*,books_list.bookcode,books_list.location,books_list.id as book_listid')->from('books');
   $this->db->join('books_list', 'books_list.book_id = books.id');
   $this->db->where('books_list.lost',1);
   $this->db->order_by('books_list.bookcode');
   $query = $this->db->get();
   $bookbycode = $query->result();



   $data['datapre']  = ''; 

   $data['bookbycode']  =$bookbycode; 
   
   
   $this->load->view('layout/header');
   $this->load->view('admin/book/getduplicateentries',$data);
   $this->load->view('layout/footer');

}



public function searchbookbyissuenumber()
{

    $issue_id= $this->input->post('issue_id');


    $this->db->select('books.perunitcost,book_issues.book_id,libarary_members.member_type')->from('book_issues');
    $this->db->join('books', 'books.id = book_issues.book_master_id');
    $this->db->join('libarary_members', 'libarary_members.id = book_issues.member_id');
    $this->db->where('book_issues.id', $issue_id);
    
    $query = $this->db->get();
    $details = $query->row();
 
$type = strtolower($details->member_type);



    $this->db->select('library_setting.perday_perbook')->from('library_setting');
    $this->db->where('type', $type);
    $query = $this->db->get();
    $bookfine = $query->row();

    $fineper  = $bookfine->perday_perbook;
    $a = '';

    if(($details->perunitcost??'' != '') && ($details->perunitcost != 0)){
        $amount =  convertCurrencyFormatToBaseAmount($details->perunitcost);

        $fine = $amount * ($fineper/100);
        $fine = round($fine);
    }else{

        $fine = 0;
    
    }



    $a .=  '<div class="form-group">
    <label for="exampleInputEmail1">Date<small class="req"> *</small></label>
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control datee" id="" name="lost_date" placeholder="'.$this->lang->line('date').'" value="'.date($this->customlib->getSchoolDateFormat()).'">
         <input type="hidden" class="lost_book_confirm" value="1" id="" name="lost_book_confirm" placeholder="">
    </div>
   
</div>';




         $a .='<div class="form-group">
    <label for="">Calculated Fine As per Book Price</label>
    <input type="number" class="form-control" value="'.$fine.'" name="lost_fine" id="typeadd" required>';

if($fine == 0){
 $a .='<span style="color:red;font-size:12px">Book Price Not Available</span>';
}else{
    $a .='<span style="color:red;font-size:12px">Book Price : '.$details->perunitcost.'</span>';


}


    $a .='</div>';



    $a .=  '<div class="form-group">
    <label for="exampleInputEmail1">Remark<small class="req"> </small></label>
  <textarea class="form-control" id="description" name="description" placeholder="" rows="3" autocomplete="off"></textarea>
     </div>
   
</div>';


  echo $a;

}



public function book_issuedatadisplay()
{
    $data= $this->input->post('data');

    $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
 $last_day_this_month  = date('Y-m-t');
 $year1st = date('Y-m-d', strtotime('first day of january this year'));
 $yearEnd = date('Y-m-d', strtotime('Dec 31'));
if($data == 'issue_today'){
  $fill =  $this->bookissue_model->getissuedata(date('Y-m-d'),date('Y-m-d'));
}elseif($data == 'last7issue_today'){
    $fill =  $this->bookissue_model->getissuedata(date('Y-m-d', strtotime('-7 days')),date('Y-m-d'));

}elseif($data == 'month_today'){

    $fill =  $this->bookissue_model->getissuedata($first_day_this_month,$last_day_this_month);
}elseif($data == 'this_yaeris'){
    
    $fill =  $this->bookissue_model->getissuedata($year1st,$yearEnd);
}elseif($data == 'returnissue_today'){

    $fill =  $this->bookissue_model->getreturndata(date('Y-m-d'),date('Y-m-d'));
}elseif($data == 'returnlast7issue_today'){

    $fill =$this->bookissue_model->getreturndata(date('Y-m-d', strtotime('-7 days')),date('Y-m-d'));
}elseif($data == 'returnmonth_today'){

    $fill =$this->bookissue_model->getreturndata($first_day_this_month,$last_day_this_month);
}elseif($data == 'returnthis_yaeris'){

    $fill = $this->bookissue_model->getreturndata($year1st,$yearEnd);
}
elseif($data == 'overduemonth'){

   $fill = $this->bookissue_model->overduedata($first_day_this_month,$last_day_this_month);
}
else{

}



$a = '';
if(count($fill) > 0){
$i = 0;
    $a .= '<div class="padding:10px;margin:5px">
    <div class="box-tools pull-right">   
                                <span class="badge badge-success success" style="background-color:rgb(135 175 187 / 62%);color:rgb(135 175 187 / 62%)">Blue</span> <b>Indicates Book Lost By Member</b>
                                </div></div> <br>
    <table class="table table-striped table-bordered table-hover examplee"><thead><tr>
  
     <th>Sr</th>
      <th>Accession No.</th>
       <th>Book Title</th>
        <th>Member Name</th>
         <th>Member Type</th>
          <th>Issue Date</th>
           <th>Due Date</th>
              <th>Return Date</th>
    </tr></thead><tbody>';

    foreach($fill as $dat){
        $i++;
        if($dat->is_returned == 2){
            $a .= '<tr style="background-color:rgb(135 175 187 / 62%);">';
        }else{
            $a .= '<tr>';
        }

  $a .= '<td>'.$i.'</td>
  <td>'.$dat->bookcode.'</td>
    <td>'.$dat->book_title.'</td>';

    if($dat->member_type == 'student'){





        $this->db->select()->from('students');
        $this->db->where("id",$dat->member_id??'');
        $query = $this->db->get();
        $datt = $query->row();




        $a .=  '<td>'.$datt->firstname??"".$datt->lastname??"".'</td>';
    }elseif($dat->member_type == 'teacher'){

        $this->db->select()->from('staff');
        $this->db->where("id",$dat->member_id??'');
        $query = $this->db->get();
        $datt = $query->row();



        $a .=  '<td>'.$datt->name??"".$datt->surname??"".'</td>';
    }else{
        $this->db->select()->from('guest');
        $this->db->where("id",$dat->member_id??'');
        $query = $this->db->get();
        $datt = $query->row();




        $a .=  '<td>'.$datt->name??"".'</td>';
    }
    



            $a .= '<td>'.$dat->member_type.'</td>
                  <td>'.$this->customlib->dateformat($dat->issue_date).'</td> <td>'.$this->customlib->dateformat($dat->duereturn_date).'</td>';
                  if($dat->return_date??'' != ''){
 $a .=   '<td>'.$this->customlib->dateformat($dat->return_date).'</td>';
                  }else{
 $a .=   '<td style="color:#0049ffa3">'.'Not Returned'.'</td>';
                  }
                 
                  $a .=   '</tr>';
    }
        $a .= '</body></table>';
}else{
    $a .= 'Zero Related Actions Were Made During This Time Period';

}



echo $a;



}

public function memberdatadisplay()
{

    $data= $this->input->post('data');
if($data == 'studentmember'){
   $fill = $this->bookissue_model->newtotalmembersdata('student');
}elseif($data == 'teachingstaff'){
    $fill = $this->bookissue_model->newtotalmembersdata('teacher');
}elseif($data == 'gueststaff'){
    $fill = $this->bookissue_model->newtotalmembersdata('guest');
}else{
    $fill = $this->bookissue_model->newtotalmembersdata();
}


$a = '';
if(count($fill) > 0){
$i = 0;
    $a .= '
    <table class="table table-striped table-bordered table-hover examplee"><thead><tr>
  
     <th>Sr</th>
      <th>Card Number</th>
       <th>Name</th>';
       if($data == 'studentmember'){
       $a .= '<th>Addmission Number</th>';
     }elseif($data == 'teachingstaff'){
        $a .= '<th>Designation</th>';
     }else{
        $a .= '<th>Respected Designation</th>';
     }
         $a .= '<th>Card Issue Date</th>
         
    </tr></thead><tbody>';

    foreach($fill as $dat){
        $i++;
   
            $a .= '<tr>';
        


        

  $a .= '<td>'.$i.'</td>';
  $a .= '<td>'.$dat->library_card_no.'</td>';
  if($dat->member_type == 'student'){





    $this->db->select()->from('students');
    $this->db->where("id",$dat->member_id??'');
    $query = $this->db->get();
    $datt = $query->row();




    $a .=  '<td>'.$datt->firstname??"".$datt->lastname??"".'</td>';
    $a .=  '<td>'.$datt->admission_no??"".'</td>';
}elseif($dat->member_type == 'teacher'){

    $this->db->select()->from('staff');
    $this->db->where("id",$dat->member_id??'');
    $query = $this->db->get();
    $datt = $query->row();



    $this->db->select()->from('staff_designation');
    $this->db->where("id",$datt->designation??'');
    $query = $this->db->get();
    $dattt = $query->row();


    $a .=  '<td>'.$datt->name??"".$datt->surname??"".'</td>';




    $a .=  '<td>'.$dattt->designation??"".'</td>';
}else{
    $this->db->select()->from('guest');
    $this->db->where("id",$dat->member_id??'');
    $query = $this->db->get();
    $datt = $query->row();




    $a .=  '<td>'.$datt->name??"".'</td>';
    $a .=  '<td>'.'Guest Member'.'</td>';
}
$a .=  '<td>'.$this->customlib->dateformat($dat->libaray_card_date).'</td>';
    }
 $a .= '</body></table>';


}else{
        $a .= 'Zero Related Members Are Available';
    
    }


echo $a;

}

public function language_filters($id)
{ 

    if (!$this->rbac->hasPrivilege('books', 'can_view')) {
        access_denied();
    }
    $this->session->set_userdata('top_menu', 'Library');
    $this->session->set_userdata('sub_menu', 'book/getall');

    $this->db->select()->from('library_dropdown_data');
    $this->db->where('library_dropdown_data.type',4);
    $this->db->like('library_dropdown_data.name',$id, 'left');
    $query = $this->db->get();
    $lang = $query->row();

    $data['langname']  =$lang->name;
    $data['langid']  =$lang->id;

 

    $this->db->select('id')->from('books');
    $this->db->where('books.book_language',$lang->id);
    $check2 = $this->db->get();
    $check2 = $check2->result_array();


       
    $arr2 = array_map (function($value){
        return $value['id'];
    } , $check2);



    if(!empty($arr2)){
             
        $this->db->select()->from('books_list');
        $this->db->where("lost",0);
        $this->db->group_start();
        $sale_ids_chunk = array_chunk($arr2,25);
        foreach($sale_ids_chunk as $sale_ids)
        {
            $this->db->or_where_in('book_id', $sale_ids);
        }
        $this->db->group_end();
        $query = $this->db->get();
         $totalcopies = $query->num_rows();

         $data['totalcopies']  =$totalcopies; 
    }else{
        $data['totalcopies']  =$totalcopies; 
    }




    $this->load->view('layout/header');
    $this->load->view('admin/book/langaugefilters',$data);
    $this->load->view('layout/footer');
}





public function getenglishbooklist($id)
{

   
    $start =  $this->input->post('start');
if($start){

}else{
$start = 0;
}


        $listbook        = $this->book_model->getenglishbooklist($id);
    


    $i =$start ;

  
    $m               = json_decode($listbook);
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    $dt_data         = array();



    if (!empty($m->data)) {
        foreach ($m->data as $key => $value) {

            $i++;

            $editbtn   = '';
            $deletebtn = '';


            
            if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                $bulkdel = '<span style="display: flex;"><input name="checkbox[]" class="ids" type="checkbox" value="'.$value->id.'" style="margin-right:5px">'.$i.' </span>';
            }

            if ($this->rbac->hasPrivilege('books', 'can_edit')) {
                $viewbtn = "<a href='" . base_url() . "admin/book/importcopies/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='Import Accession No.'><i class='fa fa-plus'></i></a>"."<a data-id='". $value->id . "'   class='btn btn-default btn-xs viewbooks'  data-toggle='tooltip' title='View Accession No.'><i class='fa fa-eye'></i></a>";

                $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
            }

            if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
            }

            $row   = array();
            $row[] = $bulkdel;
            $row[] = $value->book_title;


            $abc =$value->book_category;
            $this->db->select()->from('book_category');
            $this->db->where('book_category.id', $abc);
            $query = $this->db->get();
            $book_category = $query->row();



            
if($book_category){
$pq     = $book_category->book_category;
}else{
$pq    = 'NA';
}

          

$row[]   = $pq;


            // if ($value->description == "") {
            //     $row[] = $this->lang->line('no_description');
            // } else {
            //     $row[] = $value->description;
            // }
            // $row[]     = $value->book_no;



            // $row[]     = $value->isbn_no;
            $this->db->select()->from('library_dropdown_data');
            $this->db->where('library_dropdown_data.id', $value->publish);
            $query = $this->db->get();
            $val = $query->row();

            $row[]     = $val->name??'NA';

            $this->db->select()->from('library_dropdown_data');
            $this->db->where('library_dropdown_data.id', $value->author);
            $query = $this->db->get();
            $val = $query->row();

            $row[]     = $val->name??'NA';


            // $this->db->select()->from('library_dropdown_data');
            // $this->db->where('library_dropdown_data.id', $value->subject);
            // $query = $this->db->get();
            // $val = $query->row();

            // $row[]     = $val->name??'NA';


            // $this->db->select()->from('library_dropdown_data');
            // $this->db->where('library_dropdown_data.id', $value->department);
            // $query = $this->db->get();
            // $val = $query->row();



            // $row[]     = $val->name??'NA';

            $this->db->select()->from('books_list');
            $this->db->where('lost',0);
            $this->db->where('book_id', $value->id);
            $query = $this->db->get();
            $valcount = $query->num_rows();


            $row[] = $valcount;


          
            $this->db->select('id')->from('books_list');
            $this->db->where('book_id', $value->id);
            $this->db->where('lost',0);
            $check2 = $this->db->get();
            $check2 = $check2->result_array();
            $arr2 = array_map (function($value){
                return $value['id'];
            } , $check2);

if(count($arr2)> 0){
$this->db->select()->from('book_issues');
$this->db->where_in('book_id', $arr2);
$this->db->where('is_returned',0);
$query = $this->db->get();
$val = $query->num_rows();
}else{
$val = 0;

}
     
$val1 = $valcount -$val;



            $row[] = $val1;
            $row[]     = $val;


            if($value->perunitcost){
                $row[]     = $currency_symbol . amountFormat($value->perunitcost);
            }else{
                $row[]     = '';
            }



            $row[]     = $value->publishing_year;
            $row[]     = $this->customlib->dateformat($value->created_at);
            $row[]     = '<span>'.$viewbtn. ' ' .$editbtn . ' ' . $deletebtn.'</span>';
            $dt_data[] = $row;
        }
    }

    $json_data = array(
        "draw"            => intval($m->draw),
        "recordsTotal"    => intval($m->recordsTotal),
        "recordsFiltered" => intval($m->recordsFiltered),
        "data"            => $dt_data,
    );
    echo json_encode($json_data);
}





public function searchbookgetall()
{

   $book = $this->input->post('book');

 $this->db->select()->from('books');
 $this->db->like('book_title', $book);
 $this->db->or_like('isbn_no', $book);
 $this->db->or_like('tags', $book);
 $query =  $this->db->get();
 $check1 = $query->result_array();
 $arr1 = array_map (function($value){
    return $value['id'];
} , $check1);



 $this->db->select('id')->from('library_dropdown_data');
        $this->db->like('name', $book);
        $check2 = $this->db->get();
        $check2 = $check2->result_array();
   
        $arr0 = array_map (function($value){
            return $value['id'];
        } , $check2);


        if(count($arr0) > 0){

      
        $this->db->select()->from('books');
        $this->db->where_in('subject', $arr0);
        $query =  $this->db->get();
        $check1 = $query->result_array();
        $arr2 = array_map (function($value){
           return $value['id'];
       } , $check1);


       $this->db->select()->from('books');
       $this->db->where_in('author', $arr0);
       $query =  $this->db->get();
       $check1 = $query->result_array();
       $arr3 = array_map (function($value){
          return $value['id'];
      } , $check1);


      $this->db->select()->from('books');
      $this->db->where_in('publish', $arr0);
      $query =  $this->db->get();
      $check1 = $query->result_array();
      $arr4 = array_map (function($value){
         return $value['id'];
     } , $check1);


     $this->db->select()->from('books');
     $this->db->where_in('department', $arr0);
     $query =  $this->db->get();
     $check1 = $query->result_array();
     $arr5 = array_map (function($value){
        return $value['id'];
    } , $check1);

 
// Merge the arrays
$mergedArr = array_merge($arr1, $arr2, $arr3, $arr4, $arr5);

// Remove duplicates
$uniqueArr = array_unique($mergedArr);


        }else{
$uniqueArr = $arr1;

        }

       
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
////////////////////////////////
$a = "<table width='100%' class='table table-striped table-bordered table-hover examplee' data-export-title='".$this->lang->line('book_list')."'>
                                <thead>
                                    <tr>";

                                    if ($this->rbac->hasPrivilege('books', 'can_delete')) {
                                        $a .=   "<th>#</th>";
                                        }
                                        $a .=   "<th>".$this->lang->line('book_title')."</th>
                                        <th>".$this->lang->line('book_category')."</th>
                                        
                                        <th>".$this->lang->line('publisher')."</th>
                                        <th>".$this->lang->line('author')."</th>
                                      
                                        <th>Total Accession No.</th>
                                        <th>".$this->lang->line('available')."Accession No.</th>
                                        <th>".$this->lang->line('issued')."</th>
                                        <th >".$this->lang->line('book_price')."</th>
                                        <th >Publishing Year</th>
                                        <th>Created Date</th>
                                        <th class='no-print text text-right noExport '>".$this->lang->line('action')."</th>
                                    </tr>
                                </thead><tbody>";
if(count($uniqueArr) > 0){

    $this->db->select()->from('books');
    $this->db->where_in('id', $uniqueArr);
    $query =  $this->db->get();
    $datas = $query->result();

    $ai=0;
    foreach($datas as $value){

        $ai++;
        $a .=   "<tr>";
       
       $editbtn   = '';
       $deletebtn = '';
       
       
       
       if ($this->rbac->hasPrivilege('books', 'can_delete')) {
           $bulkdel = '<span style="display: flex;"><input name="checkbox[]" class="ids" type="checkbox" value="'.$value->id.'" style="margin-right:5px"> </span>';
       }
       
       if ($this->rbac->hasPrivilege('books', 'can_edit')) {
           $viewbtn = "<a href='" . base_url() . "admin/book/importcopies/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='Add Copies'><i class='fa fa-plus'></i></a>"."<a data-id='". $value->id . "'   class='btn btn-default btn-xs viewbooks'  data-toggle='tooltip' title='View Books'><i class='fa fa-eye'></i></a>";
       
           $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
       }
       
       if ($this->rbac->hasPrivilege('books', 'can_delete')) {
           $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
       }
       
       
       $a .=  "<td style='display: flex;'>".$ai.'&nbsp'.$bulkdel."</td>";
       $a .=  "<td>".$value->book_title."</td>";
       
       
       $abc =$value->book_category;
       $this->db->select()->from('book_category');
       $this->db->where('book_category.id', $abc);
       $query = $this->db->get();
       $book_category = $query->row();
       
       
       
       
       if($book_category){
        $a .=  "<td>".$book_category->book_category."</td>";
       }else{
        $a .=  "<td>NA</td>";
       }
       
       
       
       
       $this->db->select()->from('library_dropdown_data');
       $this->db->where('library_dropdown_data.id', $value->publish);
       $query = $this->db->get();
       $val = $query->row();
       if($val){
        $a .=  "<td>".$val->name."</td>";
       }else{
        $a .=  "<td>NA</td>";
       }
       
       
       $this->db->select()->from('library_dropdown_data');
       $this->db->where('library_dropdown_data.id', $value->author);
       $query = $this->db->get();
       $val = $query->row();
       
       if($val){
        $a .=  "<td>".$val->name."</td>";
       }else{
        $a .=  "<td>NA</td>";
       }
       
       
       
       $this->db->select()->from('books_list');
       $this->db->where('book_id', $value->id);
       $this->db->where('lost',0);
       $query = $this->db->get();
       $valcount = $query->num_rows();
       
       
       $a .=  "<td>".$valcount."</td>";
       
       
       
       $this->db->select('id')->from('books_list');
       $this->db->where('lost',0);
       $this->db->where('book_id', $value->id);
       $check2 = $this->db->get();
       $check2 = $check2->result_array();
       $arr2 = array_map (function($value){
           return $value['id'];
       } , $check2);
       
       if(count($arr2)> 0){
       $this->db->select()->from('book_issues');
       $this->db->where_in('book_id', $arr2);
       $this->db->where('is_returned',0);
       $query = $this->db->get();
       $val = $query->num_rows();
       }else{
       $val = 0;
       
       }
       
       $val1 = $valcount -$val;
       
       
       
       $a .=  "<td>".$val1."</td>";
       $a .=  "<td>".$val."</td>";
       
       if($value->perunitcost){
        $a .=  "<td>".$currency_symbol . amountFormat($value->perunitcost)."</td>";
       
       }else{
        $a .=  "<td></td>";
       
       }
       
       
       
       
       $a .=  "<td>".$value->publishing_year."</td>";
       $a .=  "<td>".$this->customlib->dateformat($value->created_at)."</td>";
       $a .= '<td><span>'.$viewbtn. ' ' .$editbtn . ' ' . $deletebtn.'</span><span><a data-id="'. $value->id.'" class="btn btn-danger btn-xs writeoff" data-toggle="tooltip" title="" data-original-title="Book Write Off">Book Write Off</a></span></td>';

       $a .=  "</tr>";
       }


}

$a .= '</tbody></table>';  
    
echo $a ;




}

public function writeoffbook()
{
    $book = $this->input->post('book');
    $data = array(          
        'writeoff'	 => 1,
        'writeoffyear'        => $this->input->post('writeoffyear'),
        'writeoffdate'        => date('Y-m-d H:i:s'),   
    );
    $this->db->where('id', $book);
    $this->db->update('books', $data);




    $data2 = array(          
        'lost'	 => 2,
    );
    $this->db->where('book_id', $book);
    $this->db->where('lost',0);
    $this->db->update('books_list', $data2);


    echo "This Book is Writted Off Successfully.";




}






public function bookwriteoffreport()
{

    if (!$this->rbac->hasPrivilege('books', 'can_view')) {
        access_denied();
    }


    $this->session->set_userdata('top_menu', 'Reports');
    $this->session->set_userdata('sub_menu', 'Reports/library');
    $this->session->set_userdata('subsub_menu', 'Reports/library/bookwriteoffreport');




   $this->db->select('books.*')->from('books');
 
   $this->db->where('books.writeoff',1);
   $this->db->order_by('books.writeoffdate','desc');
   $query = $this->db->get();
   $bookbycode = $query->result();


   $data['bookbycode'] = $bookbycode;
//    echo "<pre>";
//    print_r($bookbycode);die;
   
   $this->load->view('layout/header');
   $this->load->view('admin/book/bookwriteoffreport',$data);
   $this->load->view('layout/footer');

}


public function removewriteoffbook()
{
    $book = $this->input->post('book');
    $data = array(          
        'writeoff'	 => 0,
        'writeoffyear'        => null,
        'writeoffdate'        => null,   
    );
    $this->db->where('id', $book);
    $this->db->update('books', $data);




    $data2 = array(          
        'lost'	 => 0,
    );
    $this->db->where('book_id', $book);
    $this->db->where('lost',2);
    $this->db->update('books_list', $data2);


    echo "This Book is removed from Writted Off Successfully.";




}

}

