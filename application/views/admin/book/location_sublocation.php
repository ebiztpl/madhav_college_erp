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
                            <h3 class="box-title">Add Sublocation of <?= $fet->location ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/book/booksublocation/<?= $fet->id ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
    

    

                                <?php echo $this->customlib->getCSRF(); ?>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Sub Location<small class="req"> *</small></label>
                                    <input id="category" name="sublocation" placeholder="" type="text" class="form-control"  value="" required/>
                                    <span class="text-danger"><?php echo form_error('sublocation'); ?></span>
                                
                                </div>
                           

                                <div class="form-group addsub" data-id="0">
                                   <a class="btn btn-info btn-xs addsubbtn">Add Sub-Sub-locations</a>
                                </div>
                           


                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn">Save</button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
           
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">SubLocation List</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Libary Sublocation</th>
                                        <th class="white-space-nowrap">Description</th>
                                        <th class="white-space-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0 ;foreach($book_sublocation as $dat ){ $i++ ; ?>
                                        <tr>
                                        <td><?=  $i ?></td>
                                        <td><?=  $dat->sublocation ?>
                                    
                                        <?php $this->db->select()->from('booksub_subloactions');
                                               $this->db->order_by('booksub_subloactions.id', 'aesc');
                                               $this->db->where('booksub_subloactions.refrence_subloaction', $dat->id);
                                               $query = $this->db->get();
                                               $booksub_subloactions = $query->result();
                                                  $a = 0;
                                               foreach($booksub_subloactions as $sub_subloactions){
                                                $a = $a + 4;
                                        ?>          <div style="margin-left:<?= $a ?>px">
                                            <img src="https://madhav.enrichapp.co.in/backend/images/table-arrow.png?1731932886" alt=""><?= $sub_subloactions->subsublocations ?>
                                                    </div>


                                               <?php } ?>
                                    </td>

                                        

                                        <td class="white-space-nowrap"><?=  $dat->description ?></td>
                                        <td class="white-space-nowrap">
                                        <a href="<?php echo base_url() ?>admin/book/booksublocationlocationedit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo base_url() ?>admin/book/booksublocationlocationdelete/<?= $dat->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>

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



<script>
    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>
<!-- <script>
 $(document).on('click', '.check_confirm', function () {

    if (confirm('Are you sure you want to delete this?')) {
             return true;
    }else{
        return false;
    }

});
</script> -->
<script>
 $(document).on('click', '.addsubbtn', function () {

    // if (confirm('Are you sure you want Add Sublocation')) {
     $margin =  $('.addsub').attr('data-id');
     $margin = parseInt($margin) + 5;
    
        $a = "<div class='remove_sub' style='margin-left:"+$margin+"px;margin-top:2px;display:flex;;'>"+
                                   "<input class='form-control input-sm' name='subsublocations[]' id='inputsm' type='text' required><a class='btn btn-info btn-xs addsubbtn'>+</a><a class='btn btn-danger btn-xs removesubbtn'>-</a>"+
        "</div>";

        $(".addsub").append($a);
       
           $('.addsub').attr('data-id',$margin);
    // }else{
    //     return false;
    // }

});



$(document).on('click', '.removesubbtn', function () {

if (confirm('Are you sure, you want to delete this')) {
    $(this).closest('.remove_sub').remove();
} 



});
</script>

