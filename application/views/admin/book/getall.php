<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<?php

                    $role = $this->customlib->getStaffRole();
           $ide = json_decode($role)->id;


if($ide != 4){
?>


<script>
        $(document).ready(function () {
            $('select').select2();
            $('#languageSwitcher').select2("destroy");
            $('#currencySwitcher').select2("destroy");
  });
  </script>

  
<?php }else{?>
    <script>
        $(document).ready(function () {
    $('select').select2();
});
    </script>
    <?php 
}
?>
  <style>
    #DataTables_Table_0_filter{

        display:none;
    }
    .modal-body {
    max-height: calc(100vh - 212px);
    overflow-y: auto;
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('books', 'can_add')) {
                                ?>
                                <a href="<?php echo base_url() ?>admin/book">

                                    <button class="btn btn-primary btn-sm" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_book'); ?></button>
                                </a>
                            <?php }
                            ?>
                        </div><!-- /.pull-right -->
                    </div><!-- /.box-header -->
                    <div class="box-body">





<!-- new filters -->
<?php if ($this->session->flashdata('msg')) {?>   <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> <?php }?>



<?php if($ide){ ?>
<div class="row">





<form role="form" action="getallfilters/0" method="post" id="class_search_form">
                            <div class="col-md-12">
                                <div class="row">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Book Title</label> <small class="req">  </small>
                                                <select autofocus="" id="book_title" name="book_title" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                        $count = 0;
                                                        foreach ($titldata as $dat) {
                                                    ?>
                                                        <option value="<?php echo $dat->book_title ?>" <?php if (set_value('book_title') == $dat->book_title) {
                                                            echo "selected=selected";
                                                        }
                                                        ?>><?php echo $dat->book_title ?></option>
                                                    <?php
                                                        $count++;
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger" id="error_class_id"></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Book Category</label>
                                                <select id="book_category" name="book_category" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                        $count = 0;
                                                        foreach ($book_category as $dat) {
                                                    ?>
                                                        <option value="<?php echo $dat->id ?>" <?php if (set_value('book_category') == $dat->book_category) {
                                                            echo "selected=selected";
                                                        }
                                                        ?>><?php echo $dat->book_category ?></option>
                                                    <?php
                                                        $count++;
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('book_category'); ?></span>
                                            </div>
                                        </div>
									
									
									<!-- custom filter  by me -->


                                <div class="col-sm-2">
                                    <div class="form-group">   
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
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
                                <span class="text-danger"><?php echo form_error('department'); ?></span>
                                    </div>   
                                </div>
                          
                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
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
                                </div>  
                            </div>
                         
                              

<!-- custom filter by me -->
									
<div class="col-sm-3">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Subject</label>
                                <select id="Avaiblity" name="Avaiblity" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 3){  ?>
                                    <option value="<?=$drop->id?>" <?php
if (set_value('publisher') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                </div>  
                            </div>
                               
						
                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created By</label>
                                <select id="created_by" name="created_by" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($created_bylist as $key => $drop){
                                        if( $drop->created_by != ''){
                                ?>
                                    <option value="<?=$drop->created_by?>" <?php
if (set_value('created_by') == $drop->created_by) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->created_by?></option>

                                        <?php } }?>
                                </select>  
                                </div>  
                            </div>
                                    


                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created From</label>
                                <div class="input-group">
                            <div class="input-group-addon" id="created_from_eraser" style="cursor:pointer" title="clear date">
                                <i class="fa fa-eraser"></i>
                            </div>
                                <input id="created_from" name="created_from" placeholder="" type="text" class="form-control datee"  value="<?php echo set_value('created_from'); ?>" readonly="readonly"/>
                                </div>  
                                </div>  
                            </div>



    


                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created To</label>
                                <div class="input-group">
                            <div class="input-group-addon" id="created_to_eraser" style="cursor:pointer" title="clear date">
                                <i class="fa fa-eraser"></i>
                            </div>
                                <input id="created_to" name="created_to" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('created_to'); ?>" readonly="readonly"/>
                                </div>
                                </div>  
                            </div>

                            <div class="col-sm-1">
                            <label for="writeoff">Write Off</label>
                            <label style="cursor:pointer" class="btn btn-warning btn-sm " for="writeoff"><input type="checkbox" name="writeoff" value="1" id="writeoff">Write Off</i></label>									
                            </div>

                            <div class="col-sm-4 row" style="background-color: cornsilk;">
                            <div class="col-sm-3">
                            <label for="pub">Publisher</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="pub"><input type="checkbox" name="pub" value="1" id="pub">Publisher</i></label>									
                            </div>

                            <div class="col-sm-3">
                            <label for="auth">Author</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="auth"><input type="checkbox" name="auth" value="1" id="auth">Author</i></label>									
                            </div>


                            <div class="col-sm-3">
                            <label for="sub">Subject</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="sub"><input type="checkbox" name="sub" value="1" id="sub">Subject</i></label>									
                            </div>




                            <div class="col-sm-3">
                            <label for="dep">Department</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="dep"><input type="checkbox" name="dep" value="1" id="dep">Department</i></label>									
                            </div>


                            <div class="col-sm-12 text-center">
                                Check For Null Values
                            </div>



                            </div>




                            


                                        <div class="col-sm-1">
                                            <div class="form-group">
                                            <label for="exampleInputEmail1" style="color: white;">abcd</label>
                                                <button type="submit" name="search" value="search_filter" id="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> 
                                                    <?php echo $this->lang->line('search'); ?>
                                                </button>
                                            </div>
                                        </div>
                                        </div>


                            </div><!--./col-md-6-->
                            </div>
                            </form>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>
   <!-- new  code -->     

   <?php } ?>

   <!-- search book  code -->   

   <div class="row">
   <div class="col-md-12">
   <form role="form" action="<?php echo site_url('admin/book/searchbybookcode') ?>" method="post" id="">
   <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Search Book By Accession No.</label>
                                                <input type ="text" required class="form-control" name="book_code" />
                                                
                                                <span class="text-danger"><?php echo form_error('book_category'); ?></span>
                                            </div>
                                            </div>


                                            <div class="col-sm-1">
                                            <div class="form-group">
                                            <label for="exampleInputEmail1" style="color: white;">abcd</label>
                                                <button type="submit" name="search_bookcode" value="search_bookcode" id="search_bookcode" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> 
                                                    <?php echo $this->lang->line('search'); ?>
                                                </button>
                                            </div>
                                        </div>



    </form>

    <!-- <div class="col-sm-7 pull-right">

    <a  class="btn btn-primary btn-sm pull-right checkbox-toggle" href="<?php echo site_url('admin/book/searchbookduplicacy') ?>" style="background-color:#349ba5d6;margin-top: 24px;"><i class="fa fa-search"></i> 
                                                    <?php echo $this->lang->line('search'); ?> Book Duplicacy
    </a>
   </div>  -->


   </div> 



</div>
   <!-- search book  code -->   

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
                        <div class="mailbox-messages table-responsive overflow-visible-1">
                        <form id="bulkdlete">
                      

                        <span id="newlyaappw" style="font-size: 20px;
    background-color: #72727226;
    padding: 5px;
    border-radius: 6%;"></span>

                        <span style="margin-left: 20%;font-size:25px"><b>Total Accession Numbers</b> :<span style="color:green" class="totolbookcopies"><?= $totalcopies ?></span> </span>
                        <div class="col-md-12" style="padding: 15px;"><div class="form-group">
                                <label>Type Any One About Book For Search -<span style="color:blue">(Title ,Author ,Publisher ,Department ,Subject ,Tag ,Isbn_no)</span></label>
                                <input id="serach_filter" name="serach_filter"  type="text" class="form-control" />
                                <span class="text-danger"><?php echo form_error('return_date'); ?></span>
                            </div>    </div>

                            <div>
                            <a style="cursor:pointer" class="btn btn-danger btn-sm bulkdel" ><i class="fa fa-trash"></i></a>
                        <label style="cursor:pointer" class="btn btn-success btn-sm " for="lef"><input type="checkbox" class="checkkdel" id="lef"> Check All</i></label>
                        </div>
                        <span id="afterfiltertable">
                            <table width="100%" class="table table-striped table-bordered table-hover book-list" data-export-title="<?php echo $this->lang->line('book_list'); ?>">
                                <thead>
                                    <tr>

                                 <?php   if ($this->rbac->hasPrivilege('books', 'can_delete')) { ?>
                                        <th>#</th>
                                        <?php    }?>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_category'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('description'); ?></th> -->
                                        <!-- <th><?php echo $this->lang->line('isbn_number'); ?></th> -->
                                        <th><?php echo $this->lang->line('publisher'); ?></th>
                                        <th><?php echo $this->lang->line('author'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th>Department</th> -->
                                        <th>Total Accession No.</th>
                                        <th><?php echo $this->lang->line('available'); ?> Accession No.</th>
                                        <th><?php echo $this->lang->line('issued'); ?></th>
                                        <th ><?php echo $this->lang->line('book_price'); ?></th>
                                        <th >Publishing Year</th>
                                        <th>Created Date</th>
                                        <th class="no-print text text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                               
                                <tbody>
                                </tbody>
                                 </form>
                            </table>
                            </span><!-- /.table -->
                            </form>
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




<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">All Accession No. List</h4>
        </div>
        <div class="modal-body">
            <span id="forappend">
             </span> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="writeoffmodal" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Write Off</h4>
        </div>
        <div class="modal-body">
            <form id="writeoffsubmit" action="#" >
            <div class="form-group">
                 <label>Write Off Year</label>
                    <input id="writeoffyear" name="writeoffyear" type="number" min="1900" max="2099" step="1" value="<?= date('Y') ?>"  class="form-control" required>
                    <input id="writeoffid" name="writeoffid" type="hidden" value="" class="form-control" required>
                    <span class="text-danger"></span>
                            </div>
          
             <div class="form-group">
                                           
                                                <button type="submit" name="writeoffsubmit" value="writeoffsubmit" id="" class="btn btn-primary btn-sm pull-right checkbox-toggle" autocomplete="off">
                                                    Submit                                                </button>
                                            </div>
                                 </form>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>






<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);

        return true;
    }

    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });   
</script>

<script>
$(document).ready(function() {
    emptyDatatable('book-list','data');


   

});

    ( function ( $ ) {
    'use strict';
    $(window).load(function() {
        initDatatable('book-list','admin/book/getbooklist',[],[],15,
            [
                { "bSortable": false, "aTargets": [ -3 ] ,'sClass': 'dt-body-right'}
                
            ]);


    });
    } ( jQuery ) )
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    $(".bulkdel").on('click', function (e) {   
       if($(".ids:checked").length == 0){
        alert('Kindly check the checkboxes first');
        return false;
       }else{
        if (confirm('Are you sure you want to delete these?')) {
   var $this = $(this);
        $.ajax({
            url: '<?php echo site_url("admin/book/bulk_dlete") ?>',
            type: 'POST',
            data: $('.ids:checked').serialize(),
            dataType: 'json',
            success: function (data) {
                alert('Data Deleted Successfully');
                location.reload();
            }
        });
    }
       }
    });
</script>
<script>
$(".checkkdel").on('change', function (e) {   
  if($(this).prop('checked') == true){
    $('.ids').prop('checked',true);   
  } else {
    $('.ids').prop('checked',false);   
  }  
});
</script>
<script>
$(document).on('click', '.viewbooks', function () {
    $masterid = $(this).attr('data-id');
    $.ajax({
    url: '<?php echo site_url("admin/book/viewbooks") ?>',
    type: 'POST',
    data:  {'masterid': $masterid},
    success: function (response) {
    $("#forappend").empty();
    $("#forappend").append(response);
    $('#myModal').modal('show');
    }
    });
});
</script>
<!-- <script>
$(document).on('click', '#search_filter', function () {
    var fd = $('form#class_search_form').serialize() ;    
    $.ajax({
    url: '<?php echo site_url("admin/book/getbooklist") ?>',
    type: 'POST',
    data:  fd,
    success: function (response) {
    alert(response);
    }
    });
});
</script> -->

<script>
$(document).on('click', '.bulkdelcopy', function () {

    if (confirm('Are you sure you want to delete these?')) {
        return true;
    }else{

        return false;

    }




});



$(window).load(function() {
    setTimeout(function () {
            $cou =  $('.dataTables_info').html();  
     
            var arr = $cou.split('of');
             $('#newlyaappw').html('Total Books: '+arr[1]); 
        }, 1000);
      
            
        });
      
 
</script>
<script>
$(document).ready(function() {

$('.datee').datepicker({

format: "dd/mm/yyyy",
weekStart: 1,
todayBtn: "linked",
endDate: new Date(),
autoclose: true,
todayHighlight: true
});
});
</script>

<script>
    $(document).on('click', '#created_from_eraser', function () {
      
     $('#created_from').val('');

});
$(document).on('click', '#created_to_eraser', function () {
     $('#created_to').val('');

});
</script>
<script>
       $(document).on('keyup', '#serach_filter', function () {
        if($(this).val().length > 2){
             $cval = $(this).val();
             $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/searchbookgetall/",
        data: {'book' : $cval},
        success: function (response) {
            // $(".selectize-control.form-control.single").hide();
            // $("#books_search").empty();
            
            // $("#books_search").append(response).select2();
            // $('#books_search').select2('refresh');
            afterfiltertable
            $("#afterfiltertable").empty();
            $("#afterfiltertable").append(response);
            var table = $('.examplee').DataTable({
    
    "aaSorting": [],           
    rowReorder: {
    selector: 'td:nth-child(2)'
    },
    //responsive: 'false',
    
    dom: '<"top"f><Bl>r<t>ip',
    lengthMenu: [[15, 100,-1], [15, 100,"All"]],
          buttons: [

        {
            extend: 'copyHtml5',
            text: '<i class="fa fa-files-o"></i>',
            titleAttr: 'Copy',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel-o"></i>',
            titleAttr: 'Excel',
           
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'csvHtml5',
            text: '<i class="fa fa-file-text-o"></i>',
            titleAttr: 'CSV',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

      {
        extend:    'pdfHtml5',
        text:      '<i class="fa fa-file-pdf-o"></i>',
        orientation: 'landscape',
        pageSize: 'LEGAL',
        titleAttr: 'PDF',
        className: "btn-pdf",
        title: $('.download_label').html(),
          exportOptions: {
            columns: ["thead th:not(.noExport)"],
          
          },
          customize: function(doc) {
            doc.defaultStyle.font = "nikosh";
        }

         

    },

        {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            title: $('.download_label').html(),
         customize: function ( win ) {

            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
            $(win.document.body).find('table').addClass('display').css('font-size', '14px');
            // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
            $(win.document.body).find('h1').css('text-align', 'center');
        },
             exportOptions: {
              stripHtml:false,
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'colvis',
            text: '<i class="fa fa-columns"></i>',
            titleAttr: 'Columns',
            title: $('.download_label').html(),
            postfixButtons: ['colvisRestore']
        },
    ],

    // "scrollY":        "320px",
"pageLength": 15,
      "lengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]

    
});
        }
        });
    }else{
       if(($('#serach_filter').val().length) == 0){
        location.reload();
       }
    }

});
    </script>
    <script>
 $(document).on('submit', '#writeoffsubmit', function () {
 
      $writeoffid = $('#writeoffyear').val();
      $book_id = $('#writeoffid').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/writeoffbook",
        data: {'book' : $book_id,'writeoffyear' : $writeoffid},
        success: function (response) {
alert(response);
location.reload();
        }
        });
    

});



$(document).on('click', '.writeoff', function () {
    if (confirm('Are you sure ! This book is writeoff')) {

    $book_id = $(this).attr('data-id');
    $('#writeoffid').val($book_id);
    $('#writeoffmodal').modal('show');
    }

});

</script>