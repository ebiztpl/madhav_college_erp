<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->


  



    <section class="content">
   
                            
    <?php if($fetch->expense_income == "income"){  ?>


                                <!-- Income -->
                                <div class="row">
                                <?php
if ($this->rbac->hasPrivilege('income', 'can_edit')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Income Budget</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/updater/budgeticomeupdate"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>                     
    
    <input type="hidden" name="id" value="<?php echo  $fetch->id ; ?>">
    <!-- <input type="hidden" name="session" value="<?php echo  $fetch->session ; ?>"> -->
    <input type="hidden" name="budget_type" id="budget_type" value="income">

                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group form_sessionmodal_body" id="form_sessionmodal_body">
                                </div>
                                <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?> Category</label><small class="req"> *</small>
                                <select autofocus="" id="inc_head_idd" name="inc_head_idd" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($incheadlist as $inchead) {
        ?>
                                            <option value="<?php echo $inchead['id'] ?>"<?php
if ($fetch->category_id == $inchead['id']) {
            echo "selected = selected";
        }
        ?>><?php echo $inchead['title'] ?></option>
                                            <?php
}
    ?>
                                    </select><span class="text-danger"><?php echo form_error('inc_head_idd'); ?></span>
                                </div>




                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?></label><small class="req"> *</small>

                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($incheadlistt as $inchead) {
        ?>
                                            <option value="<?php echo $inchead['id'] ?>"<?php
if ($fetch->head == $inchead['id']) {
            echo "selected = selected";
        }
        ?>    data-id="<?php echo $inchead['head_category'] ?>"  ><?php echo $inchead['income_category'] ?></option>
                                            <?php
}
    ?>
                                    </select><span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
                                </div>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Budget <?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                                    <input id="amount" name="amount" placeholder="" type="number" class="form-control"  value="<?php echo  $fetch->amount ; ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                           
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?><?php echo  $fetch->description ; ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                                
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Budget <?php echo $this->lang->line('income_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Income Head</th>
                                        <th class="white-space-nowrap">Amount</th>
                                        <th class="white-space-nowrap">Description</th>
                                        <th class="white-space-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php $i = 0 ;foreach($tabdat as $dat){  $i++;
                                        
                                        $this->db->select()->from('income_head');
                                        $this->db->where('income_head.id', $dat->head);
                                        $query = $this->db->get();
                                        $income = $query->row();

                                        ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $income->income_category ?></td>
                                        <td class="white-space-nowrap"><?= $dat->amount ?></td>
                                        <td class="white-space-nowrap"><?= $dat->description ?></td>
                                        <td class="white-space-nowrap">
      
                                        <a href="<?php echo base_url() ?>admin/updater/edit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url() ?>admin/updater/delete/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>

                                    
                                    
                                    </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>
                         
                             <!-- Income -->
                       
                             <?php }else{ ?>



                                 <!-- Expense -->
                           
                                 <div class="row">
                                <?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Expense Budget</h3>
                        </div><!-- /.box-header -->
                        <form id="form2" action="<?php echo base_url() ?>admin/updater/budgeticomeupdate"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>                     
    
    <input type="hidden" name="id" value="<?php echo  $fetch->id ; ?>">

    <!-- <input type="hidden" name="session" value="<?php echo  $fetch->session ; ?>"> -->
    <input type="hidden" name="budget_type" id="budget_type"  value="expense">

                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="form-group form_sessionmodal_body" id="form_sessionmodal_body">
                                </div>
                                <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?> Category</label> <small class="req">*</small>

                                            <select autofocus="" id="inc_head_idd" name="inc_head_idd" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                           
                                                    foreach ($categorylistt as $exphead) { ?>
                                                    <option value="<?php echo $exphead['id'] ?>" <?php
                                                    if ($fetch->category_id == $exphead['id']) {
                                                                echo "selected =selected";
                                                    } ?>><?php echo $exphead['title'] ?></option>

                                                <?php
                                          
                                                    }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('inc_head_idd'); ?></span>
                                        </div>



                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>

                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($expheadlist as $inchead) {
        ?>
                                         <option value="<?php echo $inchead['id'] ?>"<?php
if ($fetch->head == $inchead['id']) {
            echo "selected = selected";
        } 
        ?> data-id="<?php echo $inchead['head_category'] ?>"><?php echo $inchead['exp_category'] ?></option>
                                            <?php
}
    ?>
                                    </select><span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
                                </div>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Budget <?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                                    <input id="amount" name="amount" placeholder="" type="number" class="form-control"  value="<?php echo $fetch->amount; ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                           
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?><?php echo $fetch->description; ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                         
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Budget <?php echo $this->lang->line('expense_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Expense Head</th>
                                        <th class="white-space-nowrap">Amount</th>
                                        <th class="white-space-nowrap">Description</th>
                                        <th class="white-space-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php if(count($tabdat1) > 0){ $i = 0 ;foreach($tabdat1 as $dat){  $i++;
                                        
                                        $this->db->select()->from('expense_head');
                                        $this->db->where('expense_head.id', $dat->head);
                                        $query = $this->db->get();
                                        $income = $query->row();

                                        ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $income->exp_category ?></td>
                                        <td class="white-space-nowrap"><?= $dat->amount ?></td>
                                        <td class="white-space-nowrap"><?= $dat->description ?></td>
                                        <td class="white-space-nowrap">
                                            
                                        <a href="<?php echo base_url() ?>admin/updater/edit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url() ?>admin/updater/delete/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>

                                    
                                    </td>
                                    </tr>
                                    <?php }} ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>

                            <!-- Expense -->
                            <?php }?>
                            </div>
              





  
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- <script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('income-list','admin/income/getincomelist',[],[],100,
            [
                { "bSortable": true, "aTargets": [ -2 ] ,'sClass': 'dt-body-right'},
                 { "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}
            ]);
    });
} ( jQuery ) )
</script> -->

<script>
    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
        $('#form2'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>
<script type="text/javascript">
     $(document).ready(function (event) {
        
        $('#inc_head_id option').each(function() {
if ($(this).attr('data-id') == <?= $fetch->category_id ?>) {
    $(this).css('display', 'inline-block');
} else {
    $(this).css('display', 'none');
}
});



         $('.form_sessionmodal_body').html("");
         $.ajax({
             type: "POST",
             url: baseurl + "admin/admin/getSessionform",
             dataType: 'text',
             data: {},
 
             success: function(data) {
                $('.form_sessionmodal_body').html(data);
                $('#ritik_custom').val(<?php echo $fetch->session; ?>).change();
             },
        
         });
     })
     

</script>
<script type="text/javascript">
    $(document).on('change', '#inc_head_idd', function() {
        $ab = $(this).val();
 
       $('#inc_head_id').val('');
  $('#inc_head_id option').each(function() {


                if ($(this).attr('data-id') == $ab) {
                    $(this).css('display', 'inline-block');
                } else {
                    $(this).css('display', 'none');
                }
            });
  
});

</script>
<script type="text/javascript">
$(document).ready(function () {
    setTimeout(function () {
        disable1();
                 }, 1300);

});


function disable1(){
    var incdis = $("#ritik_custom").val();
    $budget_type = $("#budget_type").val();
if($budget_type == 'income'){
      $urll =  baseurl + "admin/updater/incdis"
}else{
   $urll =  baseurl + "admin/updater/expdis"
}

    $.ajax({
             type: "POST",
             url:  $urll,
             dataType: 'text',
             data: {'incdis':incdis},
             success: function(data) {
                $('#inc_head_id option').each(function() {
$abc = $(this).val();
if(data.includes($abc)) {
    if(<?= intval($fetch->head)?> == parseInt($abc)){
        $(this).prop('disabled', false);
    $(this).css('color', 'black');
    $(this).css('background-color', 'white');
    }else{
        $(this).prop('disabled', true);
    $(this).css('color', 'white');
    $(this).css('background-color', '#777');

        
    }
  

} else {

    $(this).prop('disabled', false);
    $(this).css('color', 'black');
    $(this).css('background-color', 'white');
} 
});
             },
        
         });
}





</script>
<script>
$(document).on('change', '#ritik_custom', function() {
    disable1();
});
</script>
