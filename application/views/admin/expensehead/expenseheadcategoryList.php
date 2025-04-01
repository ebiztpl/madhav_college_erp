<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
                ?>
                <div class="col-md-4" style="display:none">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_expense_head'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" action="<?php echo site_url('admin/expensehead/expenseheadcategorycreate') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">                            
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?> Category</label><small class="req"> *</small>
                                    <input autofocus="" id="expensehead" name="expensehead" placeholder="" type="text" class="form-control"  value="<?php echo set_value('expensehead'); ?>" />
                                    <span class="text-danger"><?php echo form_error('expensehead'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?></textarea>
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
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
                echo "12";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Expense Head Category List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label">Expense Head Category List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Expenses Head Category </th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <!-- <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categorylist)) {

    $count = 1;
    foreach ($categorylist as $category) {
        ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $category['title'] ?></td>
                                                <td class="mailbox-name"><?php echo $category['description']; ?></td>
                                                <!-- <td class="mailbox-date pull-right no-print">
                                                    <?php
if ($this->rbac->hasPrivilege('income_head', 'can_edit')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/expensehead/editcategory/<?php echo $category['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('income_head', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/expensehead/deletecategory/<?php echo $category['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php }?>
                                                </td> -->
                                            </tr>
                                            <?php
}
    $count++;
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>

<!-- <script type="text/javascript">
    $(document).ready(function() {
      initDatatable('expense-head-list','admin/expensehead/ajaxSearch',[],[],100);
  });
</script> -->