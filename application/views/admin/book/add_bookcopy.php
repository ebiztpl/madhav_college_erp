<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }
    
    tr.odd { background-color: #ededed !important; }
    /* tr.odd td { padding-top: 40px;} */
    tr.odd:nth-child(2) { padding-top: 40px !important;}
/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 70vh;
    overflow-y: auto;
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
                            <h3 class="box-title">Add Accession No.</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/book/addparticular/<?=$id?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
    
    <input  type="hidden" name="idd" value="<?php echo set_value('id', $id); ?>" >

    

                                <?php echo $this->customlib->getCSRF(); ?>
                              
                               
                               
                                <div class="form-group">
                                <label for="exampleInputEmail1">Accession No.</label>
                                <input id="isbn_no" name="book_no" placeholder="" type="text" class="form-control check_book"  value="<?php echo set_value('book_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_no'); ?></span>
                                <span class="text-danger check_book_found"></span>
                                </div>
                       


 
                            <div class="form-group">
                                <label for="exampleInputEmail1">Location</label>
        
                                 <a id="chngecolor0" class="form-control btn btn-info book_location" data-id="0" style=""  >Location</a>
                                 <input  name="book_location" placeholder="" type="hidden" class="form-control abcd0"   value="" />
                                 <span class="text-danger"><?php echo form_error('book_location'); ?></span>
                                <span class="text-danger"></span>
                            </div>

                            <div class="form-group text-center">
                                <span class="prevlocation"></span>
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
                        <h3 class="box-title titlefix">Accession No. List of <?=$booktitle?></h3>
                        <div class="box-tools pull-right">

                        <a href="<?php echo base_url() ?>/admin/book/getall" type="button" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> Back</a>
                        </div><!-- /.box-tools -->
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                    <div class="box-tools pull-right" style="margin-bottom: 10px;">   
                                <span class="badge badge-success success" style="background-color:rgb(135 175 187 / 62%) !important;color:rgb(135 175 187 / 62%)">Blue</span> <b>Indicates Book Lost By Member</b>
                                </div>
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th style="width:8%">S. No</th>
                                        <th >Accession No.</th>
                                        <th style="width:75%" class="white-space-nowrap" style="width:10%">Location</th>
                                        <th class="white-space-nowrap" style="width:10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $a ?>
                            
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


                    
<!-- Modal for Location -->
<div class="modal fade" id="book_modal0" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Set Book Location</h4>
        </div>
        <div class="modal-body">
          <span class="">
            <ol>
          <?php foreach($book_location as $location1){ ?>
                 
            <li><label class="vertical-middle line-h-18">
                 <input value="<?php echo  $location1->id; ?>"
                  type="radio"
                 name="location" class="location_refrence" data-id="0"> 
                 <?php echo $location1->location ; ?>
             </label>
                    
                    
<?php
   $this->db->select()->from('book_location');
   $this->db->where('book_location.refrence_location', $location1->id);
   $this->db->order_by('book_location.id', 'Asc');
   
   $queryy = $this->db->get();
   $book_locationn = $queryy->result();
  
?>                 <ol type="A">          
    <?php foreach($book_locationn as $location2){ ?>      
                    <li><label class="vertical-middle line-h-18">
                            <input value="<?php echo  $location2->id; ?>"
                             type="radio"
                            name="location" class="location_refrence" data-id="0"> 
                            <?php echo $location2->location ; ?>
                        </label>
                        <?php
   $this->db->select()->from('book_location');
   $this->db->where('book_location.refrence_location', $location2->id);
   $this->db->order_by('book_location.id', 'Asc');
   
   $queryyy = $this->db->get();
   $book_locationnn = $queryyy->result();
  
?>       
  
  <ol type="I">    
<?php foreach($book_locationnn as $location3){ ?>      
    <li><label class="vertical-middle line-h-18">
                            <input value="<?php echo  $location3->id; ?>"
                             type="radio"
                            name="location" class="location_refrence" data-id="0"> 
                            <?php echo $location3->location ; ?>
                        </label>
                        <?php
   $this->db->select()->from('book_location');
   $this->db->where('book_location.refrence_location', $location3->id);
   $this->db->order_by('book_location.id', 'Asc');
   
   $queryyyy = $this->db->get();
   $book_locationnnn = $queryyyy->result();
  
?>       
          <ol type="a">          
          <?php foreach($book_locationnnn as $location4){ ?>      

            <li><label class="vertical-middle line-h-18">
                            <input value="<?php echo  $location4->id; ?>"
                             type="radio"
                            name="location" class="location_refrence" data-id="0"> 
                            <?php echo $location4->location ; ?>
                        </label>
                        </li>
            <?php }?>
            </ol>
</li>

    <?php }?>
</ol>






                    </li>        
                    <?php }?>
          </ol>   
                    
                    </li>                              
           <?php }?>
          </ol>
        </span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Location -->
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>
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
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
<!-- <script>
    $(document).ready(function () {
        $(document).on('change', '.location1', function () {
                 $location1 = $(this).val();
                 alert($location1);
            });
    });
</script> -->
<script>
    $(document).ready(function () {
        $(document).on('click', '.book_location', function () {

            
             $ch = $(this).attr('data-id');

            $('#book_modal'+$ch).modal('show');

            });
    });


    $(document).ready(function () {
        $(document).on('change', '.location_refrence', function () {

            
            $stat = $(this).attr('data-id');

            $val = $(this).val();

            $('.abcd'+$stat).val($val);
            $('#chngecolor'+$stat).css("background-color","yellow");
            $('#chngecolor'+$stat).css("color","black");
            $('#book_modal'+$stat).modal('hide');
            $('.prevlocation').text('');
            });
    });
</script>
<script>
    $(document).on('keyup', '.check_book', function () {
        var $check_value = $(this).val();
     
        if($check_value.length > 0){
            $.ajax({
            url: '<?php echo site_url("admin/book/check_duplicybookcode") ?>',
            type: 'POST',
            data: { value : $check_value},
            success: function (response) {
                if(response == 0){
                    $('.check_book_found').html('');
                    $('#submitbtn').prop('disabled', false);
                    
                }else{
                    $('.check_book_found').html('Accession No. Already Used');
                    $('#submitbtn').prop('disabled', true);
                
                }
            }
        });
        }
    });
    </script>