<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    div.dt-buttons { display: none;}

    /* width */
::-webkit-scrollbar {
  width: 8px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i> <?php //echo $this->lang->line('library'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Language/Department/Subject/Publisher/Author Data</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('books', 'can_add')) {
                                ?>
                                
                            

                                    <!-- <button class="btn btn-primary btn-sm" autocomplete="off"><i class="fa fa-plus"></i></button> -->
                              
                            <?php }
                            ?>
                        </div><!-- /.pull-right -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
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
                        </div>
                        <div class="">
                      
                           
    <div style="margin-bottom: 20px;">
        <table style="width:100%" class="table table-striped table-bordered table-hover" data-export-title="Book Dropdown List">
            <thead>
                <tr>
                    <th class="text-left">Language <a class="btn btn-xs btn-success add_modal_data" data-id="4">+</a><span class="autofill" id="lang"><span></th>
                    <th class="text-left">Department <a class="btn btn-xs btn-success add_modal_data" data-id="5">+</a><span class="autofill" id="dep"><span></th>
                    <th class="text-left">Subject <a class="btn btn-xs btn-success add_modal_data" data-id="3">+</a><span class="autofill" id="sub"><span></th>
                </tr>                              
            </thead>
            
            <tbody>
                           
                    <tr>
                        <td  class="text-center" style="width:20%;">
                            <table style="width:100%;height:254px !important;overflow-y: scroll; display:block;" class="table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer" data-export-title="Book Dropdown List">
                            <thead>
                                         <tr>
                                         <th width='5%' >Sr</th>
                                         <th class="text-center">name</th>
                                         <th class="text-center"  width='5%'></th>
                                         </tr>
                                         </thead>
                                         <tbody style="">
                                         <?php $count=0; foreach($dropdowndata as $drop){
                                      if($drop->type == 4){ $count++ ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$drop->name?></td>
<td style="display:ruby-text"><a style="cursor:pointer" class="btn btn-warning btn-xs edi_t" id="<?=$drop->name?>"  data-id="<?=$drop->id?>"><i class="fa fa-pencil"></i></a><a style="cursor:pointer" class="btn btn-danger btn-xs del"  data-id="<?=$drop->id?>"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                            <?php } } $count1 = $count  ?>

                                         </tbody>
                                         </table>
                                        </td>
                                        <td  class="text-center" style="width:20%">
                                        <table style="width:100%;height:254px !important;overflow-y: scroll; display:block;" class="table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer" data-export-title="Book Dropdown List">
                                        <thead>
                                         <tr>
                                         <th width='5%' >Sr</th>
                                         <th class="text-center">name</th>
                                         <th class="text-center"  width='5%'></th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         <?php $count=0; foreach($dropdowndata as $drop){
                                      if($drop->type == 5){ $count++ ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$drop->name?></td>
<td style="display:ruby-text"><a style="cursor:pointer" class="btn btn-warning btn-xs edi_t" id="<?=$drop->name?>"  data-id="<?=$drop->id?>"><i class="fa fa-pencil"></i></a><a style="cursor:pointer" class="btn btn-danger btn-xs del"  data-id="<?=$drop->id?>"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                            <?php } } $count2 = $count ?>

                                         </tbody>
                                         </table>
                                        </td>
                                        <td  class="text-center" style="width:20%">
                                        <table style="width:100%;height:254px !important;overflow-y: scroll; display:block;" class="table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer" data-export-title="Book Dropdown List">
                                        <thead>
                                         <tr>
                                         <th width='5%' >Sr</th>
                                         <th class="text-center">name</th>
                                         <th class="text-center"  width='5%'></th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         <?php $count=0; foreach($dropdowndata as $drop){
                                      if($drop->type == 3){ $count++ ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$drop->name?></td>
<td style="display:ruby-text"><a style="cursor:pointer" class="btn btn-warning btn-xs edi_t" id="<?=$drop->name?>"  data-id="<?=$drop->id?>"><i class="fa fa-pencil"></i></a><a style="cursor:pointer" class="btn btn-danger btn-xs del"  data-id="<?=$drop->id?>"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                            <?php } } $count3 = $count ?>

                                         </tbody>
                                         </table>
                                         
                                        </td>
                                      
                                   </tr>
                                </tbody>
                                 </form>
                            </table><!-- /.table -->
                            </div>

                            <div style="">
                            <table style="width:100%" class="table table-striped table-bordered table-hover" data-export-title="Book Dropdown List">
                                <thead>
                                    <tr>

                                    <th class="text-left">Publisher <a class="btn btn-xs btn-success add_modal_data" data-id="1">+</a><span class="autofill" id="pub"><span></th>
                                  
                                    
                                        <th class="text-left">Author <a class="btn btn-xs btn-success add_modal_data" data-id="2">+</a><span class="autofill" id="auth"><span></th>
                                     
                                        
                                   </tr>
                                   
                                    
                                </thead>
                               
                                <tbody>
                                <tr>

                                        <td  class="text-center" style="width:20%"> 
                                        <table style="width:100%;height:254px !important;overflow-y: scroll; display:block;" class="table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer" data-export-title="Book Dropdown List">
                                        <thead>
                                        
                                        
                                      </tr>  
                                         <tr>
                                          <th width='5%' >Sr</th>
                                         <th class="text-center">name</th>
                                         <th class="text-center"  width='5%'></th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         <?php $count=0; foreach($dropdowndata as $drop){
                                      if($drop->type == 1){ $count++ ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$drop->name?></td>
<td style="display:ruby-text"><a style="cursor:pointer" class="btn btn-warning btn-xs edi_t" id="<?=$drop->name?>"  data-id="<?=$drop->id?>"><i class="fa fa-pencil"></i></a><a style="cursor:pointer" class="btn btn-danger btn-xs del"  data-id="<?=$drop->id?>"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                            <?php } }  $count4 = $count?>

                                         </tbody>
                                         </table>
                                         </td>
                                        <td  class="text-center" style="width:20%">
                                            
                                        <table style="width:100%;height:254px !important;overflow-y: scroll; display:block;" class="table table-striped table-bordered table-hover example table-fixed-header dataTable no-footer" data-export-title="Book Dropdown List">
                                     
                                        <thead>
                                        

                                         <tr>
                                         <th width='5%' >Sr</th>
                                         <th class="text-center">name</th>
                                         <th class="text-center"  width='5%'></th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         <?php $count=0; foreach($dropdowndata as $drop){
                                      if($drop->type == 2){ $count++ ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$drop->name?></td>
<td style="display:ruby-text"><a style="cursor:pointer" class="btn btn-warning btn-xs edi_t" id="<?=$drop->name?>"  data-id="<?=$drop->id?>"><i class="fa fa-pencil"></i></a><a style="cursor:pointer" class="btn btn-danger btn-xs del"  data-id="<?=$drop->id?>"><i class="fa fa-trash"></i></a></td>

                                            </tr>
                                            <?php } }  $count5 = $count?>

                                         </tbody>
                                         </table>
                                        </td>

                                        <td  class="text-center" style="width:20%">

                                         </td>

                                        </tr>
                                      </tbody>
                                      </table>
                                      </div>
                                       









                     
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <!-- general form elements disabled -->
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
        <form action="<?php echo site_url('admin/book/dropdowndatamaster') ?>" method="post">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add <span class="additional_details"></span></h4>
        </div>
        <div class="modal-body">
          
        
   
  <div class="form-group">
    <label for="exampleInputEmail1"><span class="additional_details"></span></label>
    <input type="text" class="form-control" name="info" id="exampleInputEmail1" required>
    <input type="hidden" class="form-control" name="type" id="typeadd" required>
  </div>
  <span id="appendalready">

   </span>



        </div>
        <div class="modal-footer">
        <button type="submit" id="ab" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>


  <script>
    $(document).on('click', '.add_modal_data', function () {


        
        $('#exampleInputEmail1').val('');
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


$(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: true,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });
</script>


<script>
      $(document).on('click', '.del', function () {
if (confirm('Are you sure you want to delete these?')) {
   var $id = $(this).attr('data-id');
       
        $.ajax({
            url: '<?php echo site_url("admin/book/dropdata_del") ?>',
            type: 'POST',
            data: { id : $id},
            success: function (data) {
            
                   if(data == 1){
                    alert('Data Deleted Successfully');
                    location.reload();
                   }else{
                    alert('Data Can`t be deleted as it is linked with books');
                 
                   }
              
            }
        });
    }

});   
    </script>


<script>
$(document).on('keyup', '#exampleInputEmail1', function () {
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
                    $('#ab').removeClass('flip')
                }else{
                    $('#ab').addClass('flip')
                }



            }
        });
    }


});
</script>

<script>
    $(document).on('click', '.flip', function () {
   alert('This Data Cant Be Added as it is already present');
//         if (confirm('Are you sure you add this Data?')) {
// return true;
//         }else{
            return false;
        // }


});
</script>


<script>
        $(document).on('click', '.edi_t', function () {
            var $id = $(this).attr('data-id');
            var $name = $(this).attr('id');
            var a = prompt("Enter new name", $name);  
if (a != null) {  

    
    $.ajax({
            url: '<?php echo site_url("admin/book/updatedropdown") ?>',
            type: 'POST',
            data: { id : $id,value : a},
            success: function (response) {
                alert('Data Updated Successfully');
                location.reload();


            }
        });



}  
});
</script>

<script>
$(document).ready(function () {
  
    $('#lang').text('  Total : '+ <?= $count1 ?>);
    $('#dep').text('  Total : '+ <?= $count2 ?>);  $('#sub').text('  Total : '+ <?= $count3 ?>);
    $('#pub').text('  Total : '+ <?= $count4 ?>);  $('#auth').text('  Total : '+ <?= $count5 ?>);

});
</script>