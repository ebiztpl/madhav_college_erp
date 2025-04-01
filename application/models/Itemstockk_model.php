<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemstockk_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select('`item_stockk`.*, `itemm`.`name`, `itemm`.`item_category_id`, `itemm`.`description` as des, `item_categoryy`.`item_category`, `item_supplierr`.`item_supplier`, `item_storee`.`item_store`, `item_storee`.`code`')->from('item_stockk');
        $this->db->join('itemm ', 'itemm.id = item_stockk.item_id');
        $this->db->join('item_categoryy', 'itemm.item_category_id = item_categoryy.id');
        $this->db->join('item_supplierr', 'item_stockk.supplier_id = item_supplierr.id');
        $this->db->join('item_storee', 'item_storee.id = item_stockk.store_id', 'left outer');
        if ($id != null) {
            $this->db->where('item_stockk.id', $id);
        } else {
            $this->db->order_by('item_stockk.id', 'DESC');
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
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('item_stockk');
        $message   = DELETE_RECORD_CONSTANT . " On item stockk id " . $id;
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
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('item_stockk', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item stockk id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('item_stockk', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item stockk id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        } 
    }

    public function get_currentstock($start_date = null, $end_date = null)
    {       
        if ($start_date != '' && $end_date != '') {
            $this->datatables->where("date_format(item_stockk.date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'");
        }

        $this->datatables
            ->select('sum(`item_stockk`.`quantity`) as available_stock, `itemm`.`name`, `itemm`.`id`,`itemm`.`item_category_id`, `itemm`.`description` as `des`, `item_categoryy`.`item_category`, `item_supplierr`.`item_supplier`, `item_storee`.`item_store`,(SELECT sum(quantity) from item_issuee where itemm.id=item_issuee.item_id) as total_issued ,(SELECT sum(quantity) from item_issuee where itemm.id=item_issuee.item_id and is_returned=1) as total_not_returned')
            ->searchable('`itemm`.`name`,`item_categoryy`.`item_category`,`item_supplierr`.`item_supplier`,`item_storee`.`item_store`')
            ->orderable('`itemm`.`name`,`item_categoryy`.`item_category`,`item_supplierr`.`item_supplier`,`item_storee`.`item_store`," ",available_stock ')
            ->join("itemm", "`itemm`.`id` = `item_stockk`.`item_id`")
            ->join("`item_categoryy`", "`itemm`.`item_category_id` = `item_categoryy`.`id`")
            ->join("item_supplierr`", "`item_stockk`.`supplier_id` = `item_supplierr`.`id`")
            ->join("item_storee` ", " `item_storee`.`id` = `item_stockk`.`store_id`", "left outer")
            ->Group_By('`itemm`.`id`')
            ->from('item_stockk');

        return $this->datatables->generate('json');
    }

    public function get_ItemByBetweenDate($start_date, $end_date)
    {
        $condition = " and date_format(item_stockk.date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";
        $sql       = "SELECT `item_stockk`.*, `itemm`.`name`, `itemm`.`item_category_id`, `itemm`.`description` as `des`, `item_categoryy`.`item_category`, `item_supplierr`.`item_supplier`, `item_storee`.`item_store` FROM `item_stockk` JOIN `itemm` ON `itemm`.`id` = `item_stockk`.`item_id` JOIN `item_categoryy` ON `itemm`.`item_category_id` = `item_categoryy`.`id` JOIN `item_supplierr` ON `item_stockk`.`supplier_id` = `item_supplierr`.`id` LEFT OUTER JOIN `item_storee` ON `item_storee`.`id` = `item_stockk`.`store_id` where 1 " . $condition;

        $this->datatables->query($sql)
            ->searchable('name,item_category,item_supplier,item_store,item_stockk.quantity,purchase_price,item_stockk.date')
            ->orderable('name,item_category,item_supplier,item_store,quantity,purchase_price,date')
            ->query_where_enable(true)
            ->sort('item_stockk.id', 'desc');
        return $this->datatables->generate('json');
    }

}
