<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Book_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('books');
        if ($id != null) {
            $this->db->where('books.id', $id);
        } else {
            $this->db->order_by('books.id');
        }
        
// $this->db->where('books.writeoff',0);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbooklist()
    {




    //     $sql       = "SELECT books.*,IFNULL(total_issue, '0') as `total_issue` FROM books 
    //     left  join books_list on books_list.book_id = books.id 
    //   left JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0
    //     GROUP by book_id) as `book_count` on books_list.id=book_count.book_id 

    //      GROUP by books.id ";


    
    $sql       = "SELECT books.* FROM books where writeoff = 0";



      $this->datatables->query($sql)
          ->orderable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
          ->searchable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
          ->sort('books.id','desc');
        //   ->query_where_enable(true);
      return $this->datatables->generate('json');

        // $this->datatables
        //     ->select('books.*,IFNULL(total_issue, "0") as `total_issue` ')
        //     ->searchable('book_title,description,book_no,isbn_no,publish,author,subject,rack_no,qty," ",perunitcost,postdate," "')
        //     ->orderable('book_title,description,book_no,isbn_no,publish,author,subject,rack_no,qty," ",perunitcost,postdate," "')
    
           
        //     ->join("books_list" , "books_list.book_id = books.id", "left")
        //     // ->join(" (SELECT COUNT(*) as `qty`, book_id from books_list) as `qtyy`", "books.id=qtyy.book_id", "left")                                                                                                                         
        //     ->join(" (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0  GROUP by book_id) as `book_count`", "books_list.id=book_count.book_id", "left")
        //     ->sort('books.id','desc')
        //     ->from('books');
        // return $this->datatables->generate('json');
    }

    public function getBookwithQty()
    {
        $sql = "SELECT books.*,IFNULL(total_issue, '0') as `total_issue` FROM books LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0 GROUP by book_id) as `book_count` on books.id=book_count.book_id ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('books');
        // $this->db->where('book_id', $id);
        // $this->db->delete('book_issues');
        $message   = DELETE_RECORD_CONSTANT . " On books id " . $id;
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
            //return $return_value;
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */

    public function addbooks($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('books', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On books id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {
            $this->db->insert('books', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On books id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
            return $insert_id;
        }
    }

    public function listbook()
    {
        $this->db->select()->from('books');
        
$this->db->where('books.writeoff',0);
        $this->db->order_by("id", "desc");
        $listbook = $this->db->get();
        return $listbook->result_array();
    }

    public function check_Exits_group($data)
    {
        $this->db->select('*');
        $this->db->from('feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getTypeByFeecategory($type, $class_id)
    {
        $this->db->select('feemasters.id,feemasters.session_id,feemasters.amount,feemasters.description,classes.class,feetype.type')->from('feemasters');
        $this->db->join('classes', 'feemasters.class_id = classes.id');
        $this->db->join('feetype', 'feemasters.feetype_id = feetype.id');
        $this->db->where('feemasters.class_id', $class_id);
        $this->db->where('feemasters.feetype_id', $type);
        $this->db->where('feemasters.session_id', $this->current_session);
        $this->db->order_by('feemasters.id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function bookinventory($start_date, $end_date)
    {
        $condition = " and date_format(`books`.`postdate`,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        if (isset($_POST['book_category']) && $_POST['book_category'] != '') {
            $condition .= " and books.book_category='" . $_POST['book_category'] . "'";
        }
       
        $sql       = "SELECT books.*,IFNULL(total_issue, '0') as `total_issue` FROM books 
          left  join books_list on books_list.book_id = books.id 
        left JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0
          GROUP by book_id) as `book_count` on books_list.id=book_count.book_id 
  
          where 0=0 " . $condition . "  GROUP by books.id ";


        // $sql       = "SELECT books.*,IFNULL(total_issue, '0') as `total_issue` FROM books 
        // LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0
        //   GROUP by book_id) as `book_count` on books.id=book_count.book_id where 0=0 " . $condition . " ";

        $this->datatables->query($sql)
            ->orderable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
            ->searchable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
            ->query_where_enable(true);
        return $this->datatables->generate('json');
    }

    public function bookoverview($start_date, $end_date)
    {
        // $condition = " and date_format(`books`.`postdate`,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        $condition = "";
        $sql       = "SELECT sum(IFNULL(qtyy, '0')) as qty,sum(IFNULL(total_issue, '0')) as `total_issue` FROM books 
     LEFT JOIN (SELECT COUNT(*) as `qtyy`, book_id from books_list  where books_list.lost = 0  
          GROUP by book_id) as `book_countt` on books.id=book_countt.book_id
        LEFT JOIN (SELECT COUNT(*) as `total_issue`, book_id from book_issues  where is_returned= 0
          GROUP by book_id) as `book_count` on books.id=book_count.book_id where 0=0 " . $condition . " ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function getbooklist2($start,$book_title,$bookcategory,$author,$publisher,$avaiblity,$created_by,$created_from,$created_to,$writeoff,$pub,$auth,$sub,$dep)
    {

// echo $book_title;echo "<br>";
// echo $bookcategory;echo "<br>";
// echo $author;echo "<br>";
// echo $publisher;echo "<br>";
// echo $avaiblity;echo "<br>";

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

if($pub){
    $this->db->where("publish",'');
}
if($auth){
    $this->db->where("author",'');
}
if($sub){
    $this->db->where("subject",'');
}
if($dep){
    $this->db->where("department",'');
}





if($writeoff){
    $this->db->where("writeoff",1);
}else{
    $this->db->where('books.writeoff',0);

}
        $this->db->order_by("id", "desc");
        $this->db->limit(15, $start);
        $listbook = $this->db->get();
        return $listbook->result();
    





  
    }

    
    public function getenglishbooklist($id)
    {




   


    
    $sql       = "SELECT books.* FROM books where  'books.writeoff' = 0 And book_language =".$id;



      $this->datatables->query($sql)
          ->orderable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
          ->searchable('book_title,isbn_no,publish,author,subject,rack_no,qty,null,null,perunitcost,postdate')
          ->sort('books.id','desc')
         ->query_where_enable(true);
      return $this->datatables->generate('json');

}



}