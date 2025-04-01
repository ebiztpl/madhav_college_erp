<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('fees_group', 'can_add') || $this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_fees_group'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <form action="<?php echo site_url("admin/feegroup/edit/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">

                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>   
                                <?php echo $this->customlib->getCSRF(); ?>                       
                                <input name="id" type="hidden" class="form-control"  value="<?php echo set_value('id', $feegroup['id']); ?>" />
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $feegroup['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>



                                <div class="form-group">
                                    <label for="exampleInputEmail1">Courses</label> <small class="req">*</small>
                                    <!-- <select autofocus="" id="class_id" name="class_id" class="form-control" > -->
                                           
                                    


<?php $classarray = json_decode($feegroup['courses'])  ?>




                                                <?php
foreach ($classlist as $class) {
    ?>
<span style="display: flex;align-items: baseline;">
 <input class="checkboxx" type="checkbox" name="class_id[]"
 
 <?php  if($feegroup['courses'] != null){  
    if(in_array($class['id'], $classarray)){
        echo "checked=checked";
    } 
}
    ?>
 value="<?php echo $class['id'] ?>">
<div style="margin-left: 2%;"><?php echo $class['class'] ?></div></span>
                                                    <?php

}
?>
                                            </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>



                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description', $feegroup['description']) ?></textarea>
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
            if ($this->rbac->hasPrivilege('fees_group', 'can_add') || $this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('fees_group_list'); ?></h3>
                        <div class="box-tools pull-right" style="position: absolute;right: 76px;"> 


<div style="display:ruby-text"> <span style="margin-left: 20%;font-size:25px"><b>Total Fee Type</b> :<span style="color:green" class=""><?= count($feegroupList) ?> </span></span> </div>


 </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('fees_group_list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible">
                        <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?> </th>
                                        <th>Courses</th>
                                        <th><?php echo $this->lang->line('description'); ?> </th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($feegroupList as $feegroup) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $feegroup['name'] ?>                                     
                                            </td>
                                            <td>
                                            <ol>
                                            <?php

$classarray = json_decode($feegroup['courses']) ;
foreach ($classlist as $class) {
    ?>


 
 <?php  if($feegroup['courses'] != null){  
    if(in_array($class['id'], $classarray)){ ?>
        <li><div style="margin-left: 2%;"><?php echo $class['class'] ?></div></li>
  <?php   } 
}
    ?>


                                                    <?php

}
?></ol>


                                    </td>
											<td class="mailbox-name">
                                                <?php echo $feegroup['description']; ?>                               
                                            </td>
                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>admin/feegroup/edit/<?php echo $feegroup['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('fees_group', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>admin/feegroup/delete/<?php echo $feegroup['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
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

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>