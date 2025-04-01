<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper" style="min-height: 348px;">     
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i>Library Setting</h3>
                        
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <?php if ($this->session->flashdata('msg')) {
    ?>
                                <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                    <div class="" style="margin: 33px;">
                        
                    <form role="form" action="<?php echo site_url("schsettings/updatelibsetting") ?>" id="fees_form" method="post" enctype="multipart/form-data">
 
                         <table class="table table-hover table-striped table-bordered  dataTable no-footer"> 
<thead>
<tr>
<th>Sr</th>
<th>Type</th>
<th>Book Count</th>
<!-- <th>Renewal Deadline<small>(In Days)</small></th> -->
<th>Late Fine Per Day</th>
<th style="text-align: left;">Lost Book Fine %</th>
</tr>
</thead>
<tbody>
    <tr>
    <th>1</th>
    <th>Student</th>
    <td><input name="student_book_count"  type="int" class="form-control" value="<?= $library_setting[2]->book_count ?>">
    <span class="text-danger"><?php echo form_error('student_book_count'); ?></span>
</td>
    <!-- <td><input name="student_book_deadline"  type="int" class="form-control" value="<?= $library_setting[2]->renewal_deadline ?>">
    <span class="text-danger"><?php echo form_error('student_book_deadline'); ?></span></td> -->
    <td><input name="student_late_fine"  type="int" class="form-control" value="<?= $library_setting[2]->late_fine ?>">
    <span class="text-danger"><?php echo form_error('student_late_fine'); ?></span></td>
    <td><input name="student_per_day"  type="int" class="form-control" value="<?= $library_setting[2]->perday_perbook ?>">
    <span class="text-danger"><?php echo form_error('student_per_day'); ?></span></td>
    </tr>

    <tr>
    <th>2</th>
    <th>Teacher</th>
    <td><input name="teacher_book_count"  type="int" class="form-control" value="<?= $library_setting[1]->book_count ?>">
    <span class="text-danger"><?php echo form_error('teacher_book_count'); ?></span></td>
    <!-- <td><input name="teacher_book_deadline"  type="int" class="form-control" value="<?= $library_setting[1]->renewal_deadline ?>">
    <span class="text-danger"><?php echo form_error('teacher_book_deadline'); ?></span></td> -->
    <td><input name="teacher_late_fine"  type="int" class="form-control" value="<?= $library_setting[1]->late_fine ?>">
    <span class="text-danger"><?php echo form_error('teacher_late_fine'); ?></span></td>
    <td><input name="teacher_per_day"  type="int" class="form-control" value="<?= $library_setting[1]->perday_perbook ?>">
    <span class="text-danger"><?php echo form_error('teacher_per_day'); ?></span></td>
    </tr>


    <tr>
    <th>3</th>
    <th>Guest</th>
    <td><input name="guest_book_count"  type="int" class="form-control" value="<?= $library_setting[0]->book_count ?>">
    <span class="text-danger"><?php echo form_error('guest_book_count'); ?></span></td>
    <!-- <td><input name="guest_book_deadline"  type="int" class="form-control" value="<?= $library_setting[0]->renewal_deadline ?>">
    <span class="text-danger"><?php echo form_error('guest_book_deadline'); ?></span></td> -->
    <td><input name="guest_late_fine"  type="int" class="form-control" value="<?= $library_setting[0]->late_fine ?>">
    <span class="text-danger"><?php echo form_error('guest_late_fine'); ?></span></td>
    <td><input name="guest_per_day"  type="int" class="form-control" value="<?= $library_setting[0]->perday_perbook ?>">
    <span class="text-danger"><?php echo form_error('guest_per_day'); ?></span></td>
    </tr>
    </tbody>
    </table>




    <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="submit" class="btn btn-primary submit_schsetting pull-right edit_fees" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
   </form>


                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->

