<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
.bootstrap-tagsinput {
 width:100%;
}
/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: 70vh;
    overflow-y: auto;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<script>
        $(document).ready(function () {
            $('select').select2();


            $('#languageSwitcher').select2("destroy");
            $('#currencySwitcher').select2("destroy");
            

 
  });
  </script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Enter Book Details</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import_book'); ?></a> 
                            <?php }
                            ?>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/book/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>       
                            
                            
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?></label><small class="req"> *</small>
                                <input autofocus=""  id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book category</label><small class="req"> *</small>
                                <!-- <input autofocus=""  id="book_category" name="book_category" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_category'); ?>" /> -->


                                <select autofocus="" id="book_category" name="book_category" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($book_category as $bookcategory) {
        ?>
                                         <option value="<?php echo $bookcategory->id ?>"<?php
if (set_value('book_category') == $bookcategory->id) {
            echo "selected = selected";
        } 
        ?>  ><?php echo $bookcategory->book_category ?></option>
                                            <?php
}
    ?>
                                    </select>


                                <span class="text-danger"><?php echo form_error('book_category'); ?></span>
                            </div>

                           <!--  <div class="clearfix"></div> -->
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book Edition</label>
                                <input id="book_no" name="book_edition" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_edition'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_edition'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book Format</label>
                                <select id="book_format" name="book_format" class="form-control" >
                                <option value="Hard Copy">Hard Copy</option>
                                <option value="Soft Copy">Soft Copy</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('book_format'); ?></span>
                            </div>



                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_number'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('isbn_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                       
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?> <a class="btn btn-xs btn-success add_modal_data" data-id="1">+</a></label>
                                <select id="publisher" name="publisher" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 1){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('publisher') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                           
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?> <a class="btn btn-xs btn-success add_modal_data" data-id="2">+</a></label>
                                <!-- <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" /> -->
                                
                                <select id="author" name="author" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 2){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('author') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>    
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Generation</label>
                                <input id="author" name="generation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('generation'); ?>" />
                                <span class="text-danger"><?php echo form_error('generation'); ?></span>
                            </div>
                         
                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?>  <a class="btn btn-xs btn-success add_modal_data" data-id="3">+</a></label>
                                <select id="subject" name="subject" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 3){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('subject') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>                                  <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                         
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Language <a class="btn btn-xs btn-success add_modal_data" data-id="4">+</a></label>
                                <select id="book_language" name="book_language" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 4){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('book_language') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>                                   <span class="text-danger"><?php echo form_error('book_language'); ?></span>
                            </div>
                           <!--  <div class="clearfix"></div> -->
                            <!-- <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty'); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div> -->
                           
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_price'); ?> (<?php echo $currency_symbol; ?>)</label>
                                <input id="perunitcost" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('perunitcost'); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>

                          

                            <?php
                             if(set_value('writeoff') == 1){
                                  $check = "checked";
                                  $dis = "";
                             }else{
                                $check = "";
                                $dis = "display:none";
                             } ?>

                            <div class="form-group col-md-1">
                            <label for="writeoff">Write Off</label>
                            <input id="writeoff" name="writeoff" type="checkbox" value="1" <?= $check ?> style="display: block;">
                            </div>



                            <div class="form-group col-md-2" style="<?= $dis ?>" id="showhide">
                                <label for="exampleInputEmail1">Write Off Year</label>
                                <input id="writeoffyear" name="writeoffyear" type="number" min="1900" max="2099" step="1" value="<?= date('Y') ?>"  class="form-control" >
                                <span class="text-danger"><?php echo form_error('writeoffyear'); ?></span>
                            </div>





                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Publishing Year</label>
                                <input type="number" min="1900" max="<?= date('Y') ?>" name="publishing_year" step="1" value="<?= date('Y') ?>"   class="form-control"  />
                                <span class="text-danger"><?php echo form_error('publishing_year'); ?></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Department <a class="btn btn-xs btn-success add_modal_data" data-id="5">+</a></label>
                                <!-- <input  name="department" placeholder="" type="text" class="form-control"   /> -->
                                <select id="department" name="department" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 5){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('department') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                <span class="text-danger"><?php echo form_error('department'); ?></span>
                            </div>
                           <!--  <div class="clearfix"></div> -->
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book Pages Count</label>
                                <input id="perunitcost" min="1" name="pages_count" placeholder="" type="number" class="form-control"  value="" />
                                <span class="text-danger"><?php echo form_error('pages_count'); ?></span>
                            </div>






                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('post_date'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>


<div class="form-group col-md-12">
   
    <div class="col-sm-12 ">
    <label for="exampleInputEmail1">Enter Tags</label>
      <input type="text" class="form-control col-sm-12" name="tags" value="<?php echo set_value('tags'); ?>" data-role="tagsinput" id="exampleInputEmail1">
    </div>
  </div>




                            <div class="clearfix"></div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                        </div><!-- /.box-body -->


<!--  custom code by hritik -->

<div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add  Accession Numbers</h3>
                        <!-- <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import_book'); ?></a> 
                            <?php }
                            ?>
                        </div> -->
                   </div>


                   <?php
   $this->db->select()->from('book_location');
   $this->db->where('book_location.refrence_location', 0);
   $this->db->order_by('book_location.id', 'Asc');
   
   $query = $this->db->get();
   $book_location = $query->result();
  
?>


<div class=" col-md-12" style="margin-top:10px" id="dynamicAddRemove">
<div class="row">
                   <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book Accession No.</label>
                                <input id="isbn_no" name="book_no[]" placeholder="" data-id="0" type="text" class="form-control check_book"  value="" />

                                <input id="onlyforcheck" name="onlyforcheck[]" placeholder="" type="hidden" class="form-control onlyforcheck0"  value="0" />
                                <span class="text-danger check_book_found0"></span>
                                <span class="text-danger"><?php echo form_error('book_no'); ?></span>
                            </div>
                       


 
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Location</label>
        
                                 <a id="chngecolor0" class="form-control btn btn-info book_location" data-id="0" style=""  >Location</a>
                                 <input  name="book_location[]" placeholder="" type="hidden" class="form-control abcd0"  value="" />
                                <span class="text-danger"></span>
                            </div>

                            <div class="form-group col-md-1">
                                <label for="exampleInputEmail1" style="color: white;">n</label>
                                <a class=" form-control btn btn-success add_row" data-id="0">+</a>

                                 
                                <span class="text-danger"></span>
                            </div>
                           
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
                         
                            <div class="clearfix"></div>
</div>
</div>

</div>



<!--  custom code by hritik -->
                        <div class="box-footer" style="border-top: unset">
                            <button type="submit" id="submitbutton" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

 <!-- Modal -->
 <div class="modal fade" id="add_modal_data" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <form action="javascript:void(0);" id="data_form" method="post">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add <span class="additional_details"></span></h4>
        </div>
        <div class="modal-body">
          
        
   
  <div class="form-group">
    <label for="exampleInputEmail1"><span class="additional_details"></span></label>
    <input type="text" class="form-control jahgfjhdsg" name="info" id="exampleInputEmail11" required>
    <input type="hidden" class="form-control" name="type" id="typeadd" required>
  </div>
  <span id="appendalready">

</span>



        </div>
        <div class="modal-footer">
        <a class="btn btn-primary" id="subitdrop">Submit</a>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>





 <!-- Modal -->
 <div class="modal fade" id="check_duplicate" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <form action="<?php echo site_url('admin/book/dropdowndataa') ?>" id="data_form" method="post">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Similar Data Found</span></h4>
        </div>
        <div class="modal-body">
          
        
   

  <span id="appendalreadyy">

</span>



        </div>
        <div class="modal-footer">
       
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>





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
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" crossorigin="anonymous"></script>
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
            });
    });


    $(document).on('click', '.add_row', function () {

        $('.add_row').prop('disabled',true);
    $inid = $(this).attr('data-id');
    $new = parseInt($inid) + 1;
    $.ajax({
                type: 'POST',
                url: base_url + "admin/book/book_append",
                data: {'data': $inid},
                success: function (data) {
                    $("#dynamicAddRemove").append(data);
                    $('.add_row').attr('data-id',$new);
                    $('.add_row').prop('disabled',false);
                },
            });

     
    });
</script>

<script>
    
$(document).on('click', '.remove-input-field', function () {

if (confirm('Are you sure, you want to delete this')) {
    $stat = $(this).attr('data-id');
    $('#remove_row'+$stat).remove();
} 



});
</script>
<script>
    $(document).on('click', '.add_modal_data', function () {
        $('#exampleInputEmail11').val('');
        $('#appendalready').empty();
        $hidden = $(this).attr('data-id');
        if($hidden == 1){
             $info = 'Publisher';
        }else if ($hidden == 2){

            $info = 'Author';
        }else if ($hidden == 3){
            $info = 'Subject';

}else if ($hidden == 4){
            $info = 'Language';

}else{
             $info = 'Department';

}

$('.additional_details').text($info);
$('#typeadd').val($hidden);

        $('#add_modal_data').modal('show');
});
</script>
<script>
        $(document).on('click', '#subitdrop', function () {

            $jahgfjhdsg = $('.jahgfjhdsg').val();
    if($jahgfjhdsg.replace(/^\s+|\s+$/g, "").length != 0){
        
    }else{
        alert('Data can`t be submitted as It is blank');
        return false;
    }




            var $select = $('select').select2();
//console.log($select);
$select.each(function(i,item){
  //console.log(item);
  $(item).select2("destroy");
});


            if($('#subitdrop').hasClass('flip')){
                if (confirm('Are you sure you add this Data?')) {

                   
                    
                    $hidden =   $('#typeadd').val();
                 if($hidden == 1){
                    $('#publisher').empty();
        }else if ($hidden == 2){
            $('#author').empty();
        }else if ($hidden == 3){
            $('#subject').empty();
}else if ($hidden == 4){
    $('#book_language').empty();

}else{
    $('#department').empty();
}
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('admin/book/dropdowndataa') ?>',
                data: $("#data_form").serialize(), // serializes the form's elements.
                success: function (response)
                {      
                    var arr = response.split('~');
                 $hidden =   $('#typeadd').val();
                 if($hidden == 1){
                    $('#publisher').append(arr[0]);
        }else if ($hidden == 2){
            $('#author').append(arr[1]);
        }else if ($hidden == 3){
            $('#subject').append(arr[2]);
}else if ($hidden == 4){
    $('#book_language').append(arr[3]);
}else{
    $('#department').append(arr[4]);
}
$('#add_modal_data').modal('hide');
                }
            });


        
           




        }else{
            return false;
        }
            }else{

                
            $hidden =   $('#typeadd').val();
                 if($hidden == 1){
                    $('#publisher').empty();
        }else if ($hidden == 2){
            $('#author').empty();
        }else if ($hidden == 3){
            $('#subject').empty();
}else if ($hidden == 4){
    $('#book_language').empty();
}else{
    $('#department').empty();

}
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('admin/book/dropdowndataa') ?>',
                data: $("#data_form").serialize(), // serializes the form's elements.
                success: function (response)
                {           
                    var arr = response.split('~');
                 $hidden =   $('#typeadd').val();
                 if($hidden == 1){
                    $('#publisher').append(arr[0]);
        }else if ($hidden == 2){
            $('#author').append(arr[1]);
        }else if ($hidden == 3){
            $('#subject').append(arr[2]);
}else if ($hidden == 4){
    $('#book_language').append(arr[3]);
}else{
    $('#department').append(arr[4]);
}            
$('#add_modal_data').modal('hide');
                }
            });





        }

        setTimeout(function () {
                $('select').select2({
          sortField: 'text'
      });
      $('#languageSwitcher').select2("destroy");
      $('#currencySwitcher').select2("destroy");
                 }, 500);

    });


 </script>   


<script>
$(document).on('keyup', '#exampleInputEmail11', function () {
    var $value = $(this).val();
    var $id = $('#typeadd').val();
if($value.length > 2){
    $('#appendalready').empty();
    $.ajax({
            url: '<?php echo site_url("admin/book/check_dropdata") ?>',
            type: 'POST',
            data: { id : $id,value : $value},
            success: function (response) {
                $('#appendalready').empty();
                // alert(response);
                $('#appendalready').append(response);


                if(response == ''){
                    $('#subitdrop').removeClass('flip')
                }else{
                    $('#subitdrop').addClass('flip')
                }



            }
        });
    }


});
</script>

<script>
$(document).on('click', '#submitbutton', function () {

    
   
    var values = $("input[name='onlyforcheck[]']").map(function(){
        return $(this).val();
    }).get();


     if(jQuery.inArray('1', values) !== -1) {
        alert('Data can`t be submitted as  accession No. is repeated');
        return false;
} else {
  
    

    if($('#submitbutton').hasClass('flipp')){
    if (confirm('Are you sure to add this book as similar data was found?')) {
        return true;
    }else{
return false;
    }
}else{
    return true;
}


}


 




});
</script>
<script>
    $(document).on('change', '#book_title', function () {
        var $value = $(this).val();
        if($value.length > 2){
    $('#appendalreadyy').empty();
    $.ajax({
            url: '<?php echo site_url("admin/book/check_duplicytitle") ?>',
            type: 'POST',
            data: { value : $value},
            success: function (response) {
                $('#appendalreadyy').empty();
                // alert(response);
                $('#appendalreadyy').append(response);
                if(response == ''){
                    $('#submitbutton').removeClass('flipp')
                }else{
                    $('#submitbutton').addClass('flipp')
                }
                if(response == ''){
                $('#check_duplicate').modal('hide');
                }else{
                    $('#check_duplicate').modal('show');
                }

            }
        });
    }
    });
        </script>
<script>
    $(document).on('change', '.check_book', function () {
        var $check_value = $(this).val();
        $stat = $(this).attr('data-id');
        if($check_value.length > 0){
            $.ajax({
            url: '<?php echo site_url("admin/book/check_duplicybookcode") ?>',
            type: 'POST',
            data: { value : $check_value},
            success: function (response) {
                if(response == 0){
                    $('.check_book_found'+$stat).html('');
                    $('.onlyforcheck'+$stat).val('0');
                }else{
                    $('.check_book_found'+$stat).html(' Accession No. Already Used');
                    $('.onlyforcheck'+$stat).val('1');
                }
            }
        });
        }
    });
    </script>
 <script>
$(document).on('click', '#writeoff', function () {
    // if (confirm('Are you sure ! This book is writeoff')) {

        if(!$('#writeoff').is(':checked')){
            $('#showhide').hide();
        }else{
         
            $('#showhide').show();
        }
    // }

});
</script>
