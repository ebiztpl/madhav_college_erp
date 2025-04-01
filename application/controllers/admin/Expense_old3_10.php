<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expense extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library("datatables");
    }

    
    public function pdf($id = 0)
    {      

            $data = $this->expense_model->get_pdf_data($id);
            $html = $this->load->view('admin/expense/pdf', array('data' => $data), true);
            ini_set('memory_limit', '64M');
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output('expense_pdf', 'I');

    }


    public function index()
    {

        if (!$this->rbac->hasPrivilege('expense', 'can_view')) 
        {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/index');
        $data['title']      = 'Add Expense';
        $data['title_list'] = 'Recent Expenses';
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required');

        if ($this->form_validation->run() == false) {

        } else 
        {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/school_expense/");

            $data = array(
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name'        => $this->input->post('name'),
                'date'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'invoice_no'  => $this->input->post('invoice_no'),
                'note'        => $this->input->post('description'),
                'created_by'  => $this->input->post('created_by'),
                'approved_by' => $this->input->post('approved_by'),

                'created_by_date'  => $this->input->post('created_by_date'),
                'approved_by_date' => $this->input->post('approved_by_date'),
                'p_info' => $this->input->post('p_info'),
                'page_no' => $this->input->post('page_no'),

                'paid_by'     => $this->input->post('paid_by'),
                'payment_mode'=> $this->input->post('payment_mode'),
                'documents'   => $img_name,
            );

            $insert_id = $this->expense_model->add($data); 

            $qr = $this->generate_qrcode($data,  $insert_id);

            if($qr)
            {

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/expense/index');
            }

           
        }
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseList', $data);
        $this->load->view('layout/footer', $data);
    }

    

    public function download($id)
    {
        $result = $this->expense_model->get($id);
        $this->media_storage->filedownload($result['documents'], "./uploads/school_expense");
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

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_view')) {
            access_denied();
        }
        $data['title']   = 'Fees Master List';
        $expense         = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $this->load->view('layout/header', $data);
        $this->load->view('expense/expenseShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByFeecategory()
    {
        $feecategory_id = $this->input->get('feecategory_id');
        $data           = $this->feetype_model->getTypeByFeecategory($feecategory_id);
        echo json_encode($data);
    }

    public function getStudentCategoryFee()
    {
        $type     = $this->input->post('type');
        $class_id = $this->input->post('class_id');
        $data     = $this->expense_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_delete')) {
            access_denied();
        }

        $row = $this->expense_model->get($id);
        if ($row['documents'] != '') {
            $this->media_storage->filedelete($row['documents'], "uploads/school_expense/");
        }

        $this->expense_model->remove($id);
        redirect('admin/expense/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('expense', $this->lang->line('fees_master'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('expense/expenseCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'expense' => $this->input->post('expense'),

            );
            $this->expense_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('expense/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_edit')) 
        {
            access_denied();
        }
    
        $data['id']      = $id;
        $expense         = $this->expense_model->get($id);
        $data['expense'] = $expense;    
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expense/expenseEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name'        => $this->input->post('name'),
                'invoice_no'  => $this->input->post('invoice_no'),
                'date'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'created_by'  => $this->input->post('created_by'),
                'approved_by' => $this->input->post('approved_by'),

                'created_by_date'  => $this->input->post('created_by_date'),
                'approved_by_date' => $this->input->post('approved_by_date'),
                'p_info'           => $this->input->post('p_info'),
                'page_no' => $this->input->post('page_no'),

                'paid_by'     => $this->input->post('paid_by'),
                'payment_mode'=> $this->input->post('payment_mode'),
                'note'        => $this->input->post('description'),
            );

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {

                $img_name = $this->media_storage->fileupload("documents", "./uploads/school_expense/");
            } else {
                $img_name = $expense['documents'];
            }

            $data['documents'] = $img_name;

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($expense['documents'], "uploads/school_expense");
            }

            $insert_id = $this->expense_model->add($data);       

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/expense/index');
        }
    }


    public function expenseSearch()
    {
        if (!$this->rbac->hasPrivilege('search_expense', 'can_view')) {
            access_denied();
        }
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['search_type'] = '';
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/expensesearch');
        $data['title'] = 'Search Expense';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseSearch', $data);
        $this->load->view('layout/footer', $data);

    }



    public function getexpenselist()
    {
        $m               = $this->expense_model->getexpenselist();
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $documents = '';
                $print = "";
                $pdfbtn = "";

                if ($this->rbac->hasPrivilege('expense', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/expense/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }

                if ($this->rbac->hasPrivilege('expense', 'can_delete')) 
                {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/expense/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

              
                $pdfbtn = "<a href='" . base_url() . "admin/expense/pdf/" . $value->id . "'   target='_blank' class='btn btn-default btn-xs'  data-toggle='tooltip' title='Download PDF'><i class='fa fa-file-pdf-o'></i></a>";
                
                if ($this->rbac->hasPrivilege('expense', 'can_edit')) {

                $popup = '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal'. $value->id .'"> Pay </button>

                    <form action="'.base_url().'"admin/expense/pay_edit/"' . $value->id.'"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                    <div class="modal fade" id="exampleModal'. $value->id .'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> 
                            Modal Title </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                          '. $value->id .'

                            <div class="form-group">
                                <label> Paid By </label>
                                <input type="text" class="form-control" name="paid_by" value=""/>
                            </div>

                            <div class="form-group">
                                <label> Paid Date </label>
                                <input type="date" class="form-control" name="paid_by_date" value=""/>
                            </div>

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </form>
                    ';
                }

                // $pdfbtn = "<a target='_blank' href='" . base_url() . "backend/images/Expenses_Voucher_pdf.pdf". "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='Download PDF'><i class='fa fa-file-pdf-o'></i></a>";

                $print = "<a target='_blank' href='" . base_url() . "backend/images/Expenses_Voucher.jpg". "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('print') . "'>
                        <i class='fa fa-print'></i> </a>";


                if ($value->documents) {
                    $documents = "<a href='" . base_url() . "admin/expense/download/" . $value->id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $row   = array();
                $row[] = $value->name;

                if ($value->note == "") {
                    $row[] = $this->lang->line('no_description');
                } else {
                    $row[] = $value->note;
                }

                $row[]     = $value->invoice_no;
                $row[]     = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]     = $value->exp_category;
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $row[]     = $print . ' ' . $documents . ' ' . $editbtn . ' ' . $deletebtn .' ' . $pdfbtn . ' ' . $popup ;
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

    /*----------------- function to check search validation for admission report ---*/

    public function search()
    {
        $button_type = $this->input->post('button_type');
        if ($button_type == "search_filter") {
            $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'required|trim|xss_clean');
        } elseif ($button_type == "search_full") {
            $this->form_validation->set_rules('search_text', $this->lang->line('keyword'), 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $error = array();
            if ($button_type == "search_filter") {
                $error['search_type'] = form_error('search_type');
            } elseif ($button_type == "search_full") {
                $error['search_text'] = form_error('search_text');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $button_type = $this->input->post('button_type');
            $search_text = $this->input->post('search_text');
            $date_from   = "";
            $date_to     = "";

            $search_type = $this->input->post('search_type');
            if ($search_type == 'period') {
                $date_from = $this->input->post('date_from');
                $date_to   = $this->input->post('date_to');
            }

            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'search_text' => $search_text, 'date_from' => $date_from, 'date_to' => $date_to);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchexpenselist()
    {
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $search_text = $this->input->post('search_text');

        if ($button_type == 'search_filter') {
            if ($search_type != "") {

                if ($search_type == 'all') {

                    $dates = $this->customlib->get_betweendate('this_year');
                } else {
                    $dates = $this->customlib->get_betweendate($search_type);
                }
            } else {
                $dates       = $this->customlib->get_betweendate('this_year');
                $search_type = '';
            }

            $dateformat        = $this->customlib->getSchoolDateFormat();
            $date_from         = date('Y-m-d', strtotime($dates['from_date']));
            $date_to           = date('Y-m-d', strtotime($dates['to_date']));
            $data['exp_title'] = 'Expense Result From ' . date($dateformat, strtotime($date_from)) . " To " . date($dateformat, strtotime($date_to));
            $date_from         = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_from));
            $date_to           = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_to));
            $resultList        = $this->expense_model->search("", $date_from, $date_to);

        } else {

            $search_text = $this->input->post('search_text');
            $resultList  = $this->expense_model->search($search_text, "", "");
            $resultList  = $resultList;
        }

        $m               = json_decode($resultList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $grand_total += $value->amount;
                $row   = array();
                $row[] = $value->name;
                $row[] = $value->invoice_no;
                $row[] = $value->exp_category;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[] = $currency_symbol . amountFormat($value->amount);

                $dt_data[] = $row;
            }

            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b style='font-weight:normal'>" . $this->lang->line('grand_total') . " :  " . ($currency_symbol . amountFormat($grand_total)) . "</b>";
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }




    function generate_qrcode($data, $insert_id)
    {
        /* Load QR Code Library */
        $this->load->library('ciqrcode');
        //print_r($data);

        // echo  $lastId = $this->db->insert_id();
        // die();

        /* Data */
        $data1 = rand();
        $hex_data   = bin2hex($data1);
  

        $save_name  = $hex_data.'.png';

        /* QR Code File Directory Initialize */
        $dir = 'assets/media/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '1024';
        $config['black']        = array(255,255,255);
        $config['white']        = array(255,255,255);
        $this->ciqrcode->initialize($config);
  
        /* QR Data  */
        $params['data']     = $data1;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = FCPATH.$config['imagedir']. $save_name;
        
        $this->ciqrcode->generate($params);

        /* Return Data */
        // $return = array(
        //     'qr' => $dir. $save_name
        // );
        // return $return;


        $data = array(
            'qr' => $dir. $save_name
        );
        $this->db->set($data);
        $this->db->where('id', $insert_id);
        $this->db->update('expenses');
        return $data;
      

    }
    
  
 






}
