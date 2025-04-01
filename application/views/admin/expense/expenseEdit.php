<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<style>
/* original idea http://www.bootply.com/phf8mnMtpe */

.tree {
    min-height:20px;
    padding:19px;
    margin-bottom:20px;
    background-color:#fbfbfb;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:30px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}


.fillhead:hover {
  background-color: yellow;
   cursor:pointer;
   font-weight:bolder;
   

}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('expense', 'can_add') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_expense'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <form action="<?php echo site_url("admin/expense/edit/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">

                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="session" value="<?php echo $expense['session']; ?>">

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                                    <select autofocus="" id="exp_head_category" name="exp_head_category" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($expheadlist as $exphead) {
        ?>
                                            <option value="<?php echo $exphead['id'] ?>"<?php
if ($expense['exp_head_category'] == $exphead['id']) {
            echo "selected =selected";
        }
        ?>><?php echo $exphead['title'] ?></option>
                                                    <?php
$count++;
    }
    ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>
                                </div>
                                </div>

                                <div class="col-md-6">
                                            <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                            <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($expense['date']))); ?>" readonly="readonly" />
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                           
<?php 
   $this->account->select()->from('income_expense_subgroups');
   $this->account->where('id',$expense['exp_head_id']);
   $query = $this->account->get();
   $subdata = $query->row();

?>



                                    <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label> <small class="req">*</small>
                                <input id="exp_head_idfake" name="exp_head_idfake" placeholder="" type="text" class="form-control"  value="<?php echo set_value('exp_head_idfake',$subdata->name); ?>" readonly />
                                <input id="exp_head_id" name="exp_head_id" placeholder="" type="hidden" class="form-control"  value="<?php echo set_value('exp_head_id', $expense['exp_head_id']); ?>" />

                                <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>

                                </div>



                                    <!-- <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $expense['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                    </div> -->
                                    <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>  <small class="req">*</small>
                                    <!-- <input id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" /> -->

                                    <select autofocus="" id="name" name="name" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>


                    

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('voucher_number'); ?></label>
                                        <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('invoice_no', $expense['invoice_no']); ?>" />
                                        <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1">Cash Book Page No</label>
                                        <input id="page_no" name="page_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('page_no', $expense['page_no']); ?>" />
                                        <span class="text-danger"><?php echo form_error('page_no'); ?></span>
                                        </div>
                                    </div>
                                </div>
                           

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</label><small class="req"> *</small>
                                        <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount', convertBaseAmountCurrencyFormat($expense['amount'])); ?>" />
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('payment_mode'); ?></label> <small class="req">*</small>
                                        <select class="form-control payment_mode" name="payment_mode">
                                            <option value="">--Select--</option>
                                            <option value="Cash" <?php if ($expense['payment_mode'] == "Cash") { echo "selected =selected"; } ?>><?php echo $this->lang->line('cash'); ?></option>
                                            <option value="Cheque" <?php if ($expense['payment_mode'] == "Cheque") { echo "selected =selected"; } ?>><?php echo $this->lang->line('cheque'); ?></option>
                                            <option value="DD" <?php if ($expense['payment_mode'] == "DD") { echo "selected =selected"; } ?>><?php echo $this->lang->line('dd'); ?></option>
                                            <option value="bank_transfer" <?php if ($expense['payment_mode'] == "bank_transfer") { echo "selected =selected"; } ?>><?php echo $this->lang->line('bank_transfer'); ?></option>
                                            <option value="upi" <?php if ($expense['payment_mode'] == "upi") { echo "selected =selected"; } ?>><?php echo $this->lang->line('upi'); ?></option>
                                            <option value="card" <?php if ($expense['payment_mode'] == "card") { echo "selected =selected"; } ?>><?php echo $this->lang->line('card'); ?></option>
                                        </select>
                                         <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                                        </div>
                                    </div>
                                </div> 


                                <div class="form-group  other-input">
                                    <label>Payment Details </label>
                                   <textarea class="form-control" hidden name="p_info"  rows="2" placeholder="Payment Details" ><?php echo $expense['p_info']; ?></textarea>
                                </div>       


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bank Cash Account</label> <small class="req">*</small>
                                    <select autofocus="" id="ledger" name="ledger" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                $count = 1;
                                                    foreach ($bank_cash as $bank) { ?>
                                                    <option value="<?php echo $bank['id'] ?>" <?php
                                                    if ($expense['bankcash'] == $bank['id']) {
                                                                echo "selected =selected";
                                                    } ?>><?php echo $bank['name'] ?></option>
                                                <?php
                                                    $count++;
                                                    }
                                                ?>
                                            </select>
                                    <span class="text-danger"><?php echo form_error('ledger'); ?></span>
                                </div>





                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('created_by'); ?></label>
                                        <!-- <input type="text" class="form-control" name="created_by" value="<?php echo set_value('created_by', $expense['created_by']); ?>" /> -->

                                        <select autofocus="" id="created_by" name="created_by" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                             
                                                    foreach ($staff_list as $staff) { 
                                                        if( $staff['expense_create']  == 1){ 
                                                        if($staff['id'] == set_value('created_by', $expense['created_by'])){?>
                                                    <option value="<?php echo $staff['id'] ?>" selected><?php echo $staff['name'].''.$staff['surname']?> <small>(<?= $staff["designation"] ?>)</small> </option>

                                                <?php
                                                        }else{  ?>

<option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'].''.$staff['surname']?> <small>(<?= $staff["designation"] ?>)</small> </option>

                                                       <?php   }
                                                    }}
                                                ?>
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('approved_by'); ?></label>
                                        <!-- <input type="text" class="form-control" name="approved_by" value="<?php echo set_value('approved_by', $expense['approved_by']); ?>" /> -->
                                        <select autofocus="" id="approved_by" name="approved_by" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                             
                                                    foreach ($staff_list as $staff) { 
                                                        if( $staff['expense_approve']  == 1){
                                                        if($staff['id'] == set_value('approved_by', $expense['approved_by'])){?>
                                                    <option value="<?php echo $staff['id'] ?>" selected><?php echo $staff['name'].''.$staff['surname']?> <small>(<?= $staff["designation"] ?>)</small> </option>

                                                <?php
                                                        }else{  ?>

<option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'].''.$staff['surname']?> <small>(<?= $staff["designation"] ?>)</small> </option>

                                                       <?php   }
                                                    }}
                                                ?>
                                            </select>


                                        </div>
                                    </div>


                    
             

                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail2"> Created By <?php echo $this->lang->line('date'); ?></label>
                                            <input id="created_by_date"  value="<?php echo set_value('created_by_date', $expense['created_by_date']); ?>" name="created_by_date" placeholder="" type="date" class="form-control created_by_date" />
                                            <span class="text-danger"><?php echo form_error('created_by_date'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail3"> Approved By <?php echo $this->lang->line('date'); ?></label>  
                                            <input id="approved_by_date" value="<?php echo set_value('approved_by_date', $expense['approved_by_date']); ?>" name="approved_by_date" placeholder="" type="date"   class="form-control approved_by_date" />
                                          
                                        </div>
                                    </div>
                                </div>



                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('paid_by'); ?></label>
                                        <input type="text" class="form-control" name="paid_by"  value="<?php echo set_value('paid_by', $expense['paid_by']); ?>"/>
                                        
                                        </div>
                                    </div> -->
                                </div>
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?><?php echo set_value('description', $expense['note']) ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('expense', 'can_add') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive overflow-visible-lg">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover expense-list" data-export-title="<?php echo $this->lang->line('expense_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th>Bank Cash Account
                                            </th>
                                          <th><?php echo $this->lang->line('description'); ?>
                                            </th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('expense_head'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)
                                        </th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 




<div id="modalfetch" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" id="">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Expenses Head</h4>
      </div>
      <div class="modal-body" id="appendhead">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('expense-list','admin/expense/getexpenselist',[],[],15);
    });
} ( jQuery ) )
</script>
<script>
        $(function() {
        $(".other-input").hide();
            $(".payment_mode").change(function() {
                if($(this).val() != 'Cash')
                { 
                    $(".other-input").show();
                }
                else
                {
                    $(".other-input").hide();
                    $('.other-input').val("");
                }
          });
    });
    </script>
    <script>
      $( document ).ready(function() {
        
                if('<?= $expense['payment_mode'] ?>' != 'Cash')
                { 
                    $(".other-input").show();
                }
                else
                { 
                    $(".other-input").hide();
                    $('.other-input').val("");
                }
         
});
    </script>
    <script>
 $(document).on("click","#exp_head_idfake",function() {
   $cat =  $('#exp_head_category').val();
     if($cat != ''){

$.ajax({
             type: "POST",
             url: baseurl + "admin/expense/getheadmodal",
             dataType: 'text',
             data: {'category':$cat},
             success: function(data) {
                $('#appendhead').empty();
                $('#modalfetch').modal('show');
                $('#appendhead').append(data);
             },
        
         });




     }else{
        alert('Select Expense Head Category First');
     }
      });




</script>
<script>
     $(document).on("click",".fillhead",function() {
       $text =   $(this).text();
       $id =   $(this).attr('data-id');
        $('#exp_head_idfake').val($text);
        $('#exp_head_id').val($id);
        $('#modalfetch').modal('hide');

    
});


$(document).on("change","#exp_head_category",function() {
        $('#exp_head_idfake').val('');
        $('#exp_head_id').val('');
       $val = $(this).val();
    

       $.ajax({
             type: "POST",
             url: baseurl + "admin/expense/getgrouplasers",
             dataType: 'text',
             data: {'group':$val},
             success: function(data) {
                $('#name').empty();
       $('#name').append(data);
             },
        
         });
});


$(document).ready(function(){
    
       $val = $('#exp_head_category').val();
    

       $.ajax({
             type: "POST",
             url: baseurl + "admin/expense/getgrouplasers",
             dataType: 'text',
             data: {'group':$val},
             success: function(data) {
                $('#name').empty();
       $('#name').append(data);
       $('#name').val(<?= $expense['name'] ?>);
    
       
             },
        
         });
});

    </script>
