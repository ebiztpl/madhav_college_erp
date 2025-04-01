<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

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
<style>
.bootstrap-tagsinput {
 width:100%;
}
</style>
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
                        <h3 class="box-title">Edit Book Details</h3>
                        <!-- <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import_book'); ?></a> 
                            <?php }
                            ?>
                        </div> -->
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/book/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                    <input  type="hidden" name="id" value="<?php echo set_value('id', $editbook['id']); ?>" >
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
                                <input autofocus=""  id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title',$editbook['book_title']); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book category</label><small class="req"> *</small>


                                <select autofocus="" id="book_category" name="book_category" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($book_category as $bookcategory) {
        ?>
                                         <option value="<?php echo $bookcategory->id ?>"<?php
if ($editbook['book_category'] == $bookcategory->id) {
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
                                <input id="book_no" name="book_edition" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_edition',$editbook['book_edition']); ?>" />
                                <span class="text-danger"><?php echo form_error('book_edition'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Book Format</label>
                                <select id="book_format" name="book_format" class="form-control" >
                                <option value="Hard Copy" <?php if($editbook['book_format'] == 'Hard Copy'){echo "selected";}?>>Hard Copy</option>
                                <option value="Soft Copy" <?php if($editbook['book_format'] == 'Soft Copy'){echo "selected";}?>>Soft Copy</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('book_format'); ?></span>
                            </div>



                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_number'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('isbn_no',$editbook['isbn_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                       
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?> </label>
                                <select id="publisher" name="publisher" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 1){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($editbook['publish'] == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                           
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?> </label>
                                <!-- <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" /> -->
                                
                                <select id="author" name="author" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 2){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($editbook['author'] == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>    
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Generation</label>
                                <input id="author" name="generation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('generation',$editbook['generation']); ?>" />
                                <span class="text-danger"><?php echo form_error('generation'); ?></span>
                            </div>
                         
                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?> </label>
                                <select id="subject" name="subject" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 3){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($editbook['subject'] == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>                                  <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                         
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Language </label>
                                <select id="book_language" name="book_language" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 4){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($editbook['book_language'] == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>                                   <span class="text-danger"><?php echo form_error('book_language'); ?></span>
                            </div>
                           <!--  <div class="clearfix"></div> -->
                            <!-- <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty',$editbook['qty']); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div> -->
                           
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_price'); ?> (<?php echo $currency_symbol; ?>)</label>
                                <input id="perunitcost" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('perunitcost',$editbook['perunitcost']); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>

                            <?php
                             if($editbook['writeoff'] == 1){
                                  $check = "checked";
                                  $dis = "";
                             }else{
                                $check = "";
                                $dis = "display:none";
                             } ?>

                            <div class="form-group col-md-1">
                            <label for="writeoff">Writeoff</label>
                            <input id="writeoff" name="writeoff" type="checkbox" value="1" <?= $check ?> style="display: block;">
                            </div>


                   
                            
                            <div class="form-group col-md-2" style="<?= $dis ?>" id="showhide">
                                <label for="exampleInputEmail1">Writeoff Year</label>
                                <input id="writeoffyear" name="writeoffyear" type="number" min="1900" max="2099" step="1" value="<?php echo set_value('writeoffyear',$editbook['writeoffyear']); ?>"  class="form-control" >
                                <span class="text-danger"><?php echo form_error('writeoffyear'); ?></span>
                            </div>



                            <div class="clearfix"></div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Publishing Year</label>
                                <input type="number" min="1900" max="<?= date('Y') ?>" name="publishing_year" step="1" value="<?= $editbook['publishing_year']?>"   class="form-control"  />
                                <span class="text-danger"><?php echo form_error('publishing_year'); ?></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">Department</label>
                                <!-- <input  name="department" placeholder="" type="text" class="form-control"   /> -->
                                <select id="department" name="department" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 5){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($editbook['department'] == $drop->id) {
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
                                <input id="perunitcost" name="pages_count" placeholder="" type="number" class="form-control"  value="<?php echo set_value('pages_count', $editbook['pages_count']); ?>" />
                                <span class="text-danger"><?php echo form_error('pages_count'); ?></span>
                            </div>






                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('post_date'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', $this->customlib->dateformat($editbook['postdate'])); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>



                            <div class="form-group col-md-12">
   
   <div class="col-sm-12 ">
   <label for="exampleInputEmail1">Enter Tags</label>
     <input type="text" class="form-control col-sm-12" name="tags" value="<?php echo set_value('tags',$editbook['tags']); ?>" data-role="tagsinput" id="exampleInputEmail1">
   </div>
 </div>




                            <div class="clearfix"></div>


                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description', $editbook['description']); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                        </div><!-- /.box-body -->



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
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
<!-- <script>
    $(document).ready(function () {
        $(document).on('change', '.location1', function () {
                 $location1 = $(this).val();
                 alert($location1);
            });
    });
</script> -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" crossorigin="anonymous"></script>

<script>
$(document).on('click', '#submitbutton', function () {

    if($('#submitbutton').hasClass('flipp')){
    if (confirm('Are you sure to add this book as similar data was found?')) {
        return true;
    }else{
return false;
    }
}else{
    return true;
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
