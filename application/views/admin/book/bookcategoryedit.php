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
   
                            
   


                                <!-- Income -->
                                <div class="row">
                        

                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Book Category</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/book/bookcategoryupdate"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
    
    <input type="hidden" name="id" value="<?= $fet->id ?>">
    

                                <?php echo $this->customlib->getCSRF(); ?>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Book Category<small class="req"> *</small></label>
                                    <input id="category" name="category" placeholder="" type="text" class="form-control"  value="<?= $fet->book_category ?>" />
                                    <span class="text-danger"><?php echo form_error('category'); ?></span>
                                </div>
                           
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?= $fet->description ?></textarea>
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
           
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Book Category List</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Book Category</th>
                                        <th class="white-space-nowrap">Books Count</th>
                                        <th class="">Description</th>
                                        <th class="white-space-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0 ;foreach($book_category as $dat ){ $i++ ; 
                                        
                                        
                                        $sql = "SELECT * FROM books_list WHERE book_id  IN (SELECT DISTINCT id FROM books where book_category = ".$dat->id.")";
                                        $query = $this->db->query($sql);
                                        $valcount =  $query->num_rows();
                                        
                                        ?>
                                        <tr>
                                        <td><?=  $i ?></td>
                                        <td><?=  $dat->book_category ?></td>
                                        <form action="<?=base_url() ?>admin/book/getallfilters/0" method="post">
                                        <td class="white-space-nowrap  text-center"><button type="submit" class="btn btn-success btn-xs"> <?=  $valcount ?></button></td>
                                        <input type="hidden" value="<?= $dat->id ?>" name="book_category">
                                            </form>
                                        <td class=""><?=  $dat->description ?></td>
                                        <td class="white-space-nowrap">
                                        <a href="<?php echo base_url() ?>admin/book/bookcategoryedit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url() ?>admin/book/bookcategorydelete/<?= $dat->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>

                                        </td>
                                    </tr>
                                    <?php }   ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>
                         
                             <!-- Income -->
                       
                             
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
    })
</script>
<script>
 $(document).on('click', '.check_confirm', function () {

    if (confirm('Are you sure you want to delete this?')) {
             return true;
    }else{
        return false;
    }

});
</script>