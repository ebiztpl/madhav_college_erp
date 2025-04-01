<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accountant_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->account = $this->load->database('account', true);
        $this->config->load('app-config');
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('accountants.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`');
        $this->db->from('accountants');
        $this->db->join('users', 'users.user_id = accountants.id', 'left');
        $this->db->where('users.role', 'accountant');

        if ($id != null) {
            $this->db->where('accountants.id', $id);
        } else {
            $this->db->order_by('accountants.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('accountants');
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
            $this->db->update('accountants', $data);
        } else {
            $this->db->insert('accountants', $data);
            return $this->db->insert_id();
        }
    }

    public function searchNameLike($searchterm)
    {
        $this->db->select('accountants.*')->from('accountants');
        $this->db->group_start();
        $this->db->like('accountants.name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('accountants.id');
        $query = $this->db->get();
        return $query->result_array();
    }




      public function saveaccountlaser($id=null,$name=null,$unit=null,$income_expense_type_id=null,$income_expense_group_id=null,$type=null,$type_name=null)
      {


        // $this->db->trans_start(); # Starting Transaction
        // $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

          $data = array(
          'name' =>$name,
          'unit' =>$unit,
          'income_expense_type_id' =>$income_expense_type_id,
          'income_expense_group_id' =>$income_expense_group_id,
          'type'	 =>$type,
      );
  
  
      $this->account->insert('income_expense_heads', $data);
      $insert_id = $this->account->insert_id();

if($insert_id != 0){
    date_default_timezone_set('Asia/Kolkata'); 
      
    $data2 = array(
        'type' =>$type_name,
        'type_id' =>$id,
        'inserted_id' =>$insert_id,
        'created_at' =>date('Y-m-d H:i:s'),
    );
    
    $this->db->insert('laser_log', $data2);


   
    $data3 = array(
      'ledger_id' =>$insert_id,
      'branch_id' =>$this->config->item('branch_id'),
  );
  $this->account->insert('ledger_branch', $data3);
  return 1;
}else{
    return 0;
}
      

 


    //   $this->db->trans_complete(); # Completing transaction
    //   /* Optional */

    //   if ($this->db->trans_status() === false) {
    //       # Something went wrong.
    //       $this->db->trans_rollback();
    //       return false;
    //   } else {

    //       return 'yes';
    //   }
  
      }




      
      public function income_expense_groups()
      {
        $this->account->select('name as title,description,id')->from('income_expense_groups');
        $this->account->where('deleted_at',Null);
        $this->account->order_by('id', 'desc');
        $query = $this->account->get();
        return $query->result_array();


      }

      public function bank_cash()
      {

        $barcnh_i = $this->config->item('branch_id');

        $this->account->select('bank_cash')->from('bank_cash_branches');
        $this->account->where('branch_id', $barcnh_i);
        $this->account->order_by('bank_cash_branches.id', 'desc');
        $check = $this->account->get();
        $check = $check->result_array();
    
        $arr = array_map (function($value){
            return $value['bank_cash'];
        } , $check);
       

        if(count($arr) > 0){

        }else{
            $arr = [0];
        }

      $this->account->select()->from('bank_cashes');
      $this->account->where_in('id',$arr);
      $this->account->order_by('id', 'desc');
      $query = $this->account->get();
      return  $query->result_array();

      }


  
}
