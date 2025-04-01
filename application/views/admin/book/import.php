<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
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
/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: 70vh;
    overflow-y: auto;
}
.location_refrence{
    display:none;
}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('import_book') ?></h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('admin/book/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_sample_import_file'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?> <div>  <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> </div> <?php }?>
                        <br/>
                        1. <?php echo $this->lang->line('book_instruction_one'); ?><br/>
                        2. <?php echo $this->lang->line('book_instruction_two'); ?><br/>
                        <hr/></div>
                    <div class="box-body table-responsive overflow-visible">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                                <tr>
                                    <?php

foreach ($fields as $key => $value) {

    if ($value == 'book_title') {
        $value = "book_title";
    }
    if ($value == 'book_no') {
        $value = "book_number";
    }
    if ($value == 'isbn_no') {
        $value = "isbn_number";
    }
    if ($value == 'subject') {
        $value = "subject";
    }
    if ($value == 'rack_no') {
        $value = "rack_number";
    }
    if ($value == 'publish') {
        $value = "publisher";
    }
    if ($value == 'author') {
        $value = "author";
    }
    if ($value == 'qty') {
        $value = "qty";
    }
    if ($value == 'perunitcost') {
        $value = "book_price";
    }
    if ($value == 'postdate') {
        $value = "post_date";
    }
    if ($value == 'description') {
        $value = "description";
    }
    if ($value == 'available') {
        $value = "available";
    }
    if ($value == 'is_active') {
        $value = "is_active";
    }
    $add = "";
    if (($value == "book_title")) {
        $add = "<span class='text-red d-inline'>* </span>";
    }
    ?>
                                        <th><?php echo $add . "<span>" . $this->lang->line($value) . "</span>"; ?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($fields as $key => $value) {
    ?>
                                        <td><?php echo "Sample Data" ?></td>
                                    <?php }?>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group col-md-12">
                              
        
                                 <a id="chngecolor0" class="form-control btn btn-success book_location" data-id="0" style=""  >View Locations</a>
                              
                            </div>






                    <hr/>
                    <form action="<?php echo site_url('admin/book/import') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">


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
        ?> ><?=$drop->name?> - [<?=$drop->id?>]</option>

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
        ?> ><?=$drop->name?> - [<?=$drop->id?>]</option>

                                        <?php } }?>
                                </select>    
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>




                     
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?> <a class="btn btn-xs btn-success add_modal_data" data-id="3">+</a></label>
                                <select id="subject" name="subject" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 3){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('subject') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?> - [<?=$drop->id?>]</option>

                                        <?php } }?>
                                </select>                                  <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>



                            <div class="clearfix"></div>


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
        ?> ><?=$drop->name?> - [<?=$drop->id?>]</option>

                                        <?php } }?>
                                </select>                                   <span class="text-danger"><?php echo form_error('book_language'); ?></span>
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
        ?> ><?=$drop->name?> - [<?=$drop->id?>]</option>

                                        <?php } }?>
                                </select>  
                                <span class="text-danger"><?php echo form_error('department'); ?></span>
                            </div>




                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-12 pt20">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('import_book'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                    </div>
                </div>
                </section>
            </div>




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
    <input type="text" class="form-control" name="info" id="exampleInputEmail11" required>
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


<?php
            $this->db->select()->from('book_location');
            $this->db->order_by('book_location.id', 'desc');
            $this->db->where('book_location.refrence_location', 0);
            $query = $this->db->get();
            $book_location = $query->result();
          

?>
                 
<!-- Modal for Location -->
<div class="modal fade" id="book_modal0" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">View Book Location</h4>
        </div>
        <div class="modal-body">
          <span class="">
            <ol>
          <?php foreach($book_location as $location1){ ?>
                 
            <li><label class="vertical-middle line-h-18">
                 <input value="<?php echo  $location1->id; ?>"
                  type="radio"
                 name="location" class="location_refrence" data-id="0"> 
                 <?php echo $location1->location." [".$location1->id."]" ; ?>
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
                            <?php echo $location2->location." [".$location2->id."]" ; ?>
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
                            <?php echo $location3->location." [".$location3->id."]" ; ?>
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
                            <?php echo $location4->location." [".$location4->id."]" ; ?>
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
          $fj =  $('#exampleInputEmail11').val();
if($.trim($fj) == ''){

    alert('This Data Cant Be Empty');
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
//new false to submit
                    alert('This Data Cant Be Added as it is already present');
                    return false;
         //new false to submit           
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

                 location.reload();

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

$(document).ready(function () {
        $(document).on('click', '.book_location', function () {

            
          

            $('#book_modal0').modal('show');

            });
    });
</script>