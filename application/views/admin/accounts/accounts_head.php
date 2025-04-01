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
     

                                 <!-- Expense -->
                           
                                 <div class="row">
                                <?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Account Head</h3>
                        </div><!-- /.box-header -->
                        <form id="form2" action="<?php echo base_url() ?>admin/source/accounts_head"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
    
    
    <!-- <input type="hidden" name="session" value="<?php echo $this->setting_model->getCurrentSessionName(); ?>"> -->
  

                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group form_sessionmodal_body" id="form_sessionmodal_body">
                                </div>
                       




                                <div class="form-group">
                                    <label for="exampleInputEmail1">Accounts Type</label><small class="req"> *</small>

                                    <select autofocus="" id="accounts_type" name="accounts_type" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($accounts_type as $acc) {
        ?>
                                            <option value="<?php echo $acc->id ?>"<?php
if (set_value('accounts_type') == $acc->id) {
            echo "selected";
        } 
        ?> ><?php echo $acc->account_type ?></option>
                                            <?php
}
    ?>
                                    </select><span class="text-danger"><?php echo form_error('accounts_type'); ?></span>
                                </div>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Head Name<small class="req"> *</small></label>
                                    <input id="head_name" name="head_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('head_name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('head_name'); ?></span>
                                </div>
                           
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                              
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?php echo $this->lang->line('save'); ?></button>
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
                        <h3 class="box-title titlefix">Account Head List</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Account Type</th>
                                        <th class="white-space-nowrap">Account Head</th>
                                        <th class="white-space-nowrap">Status</th>
                                        <th class="white-space-nowrap">Description</th>
                                        <th class="white-space-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php if(count($head_list) > 0){ $i = 0 ;foreach($head_list as $dat){  $i++;
                                        
                                        $this->db->select()->from('account_type');
                                        $this->db->where('account_type.id', $dat->account_type);
                                        $query = $this->db->get();
                                        $account_type = $query->row();

                                        ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $account_type->account_type ?></td>
                                        <td class="white-space-nowrap"><?= $dat->head_name ?></td>
                                        <td class="white-space-nowrap">
                                            <?php if($dat->status == 1){ ?>
                                        <a  class="btn btn-xs" >Active</a>
                                        <?php }else{ ?>
                                            <a  class="btn btn-xs" >Deactive</a>
                                            <?php } ?>
                                        </td>
                                        <td class="white-space-nowrap"><?= $dat->description ?></td>
                                        <td class="white-space-nowrap">
                                            
                                        <a href="<?php echo base_url() ?>admin/update/edit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url() ?>admin/update/delete/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>

                                    
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
                            </div>
                 





  
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
