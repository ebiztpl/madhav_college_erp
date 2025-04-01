<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

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
                        <h3 class="box-title titlefix"><?= $langname ?> <?php echo $this->lang->line('book_list'); ?></h3>
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




<?php if ($this->session->flashdata('msg')) {?>   <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> <?php }?>




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
                        <a style="cursor:pointer" class="btn btn-danger btn-sm bulkdel" ><i class="fa fa-trash"></i></a>
                        <label style="cursor:pointer" class="btn btn-success btn-sm " for="lef"><input type="checkbox" class="checkkdel" id="lef"> Check All</i></label>


                        <span id="newlyaappw" style="font-size: 20px;
    background-color: #72727226;
    padding: 5px;
    border-radius: 6%;"></span>

                        <span style="margin-left: 20%;font-size:25px"><b>Total Accession Numbers</b> :<span style="color:green" class="totolbookcopies"><?= $totalcopies ?></span> </span>
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
                            </table><!-- /.table -->
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


        if('<?=$langname?>'  == 'English'){

        initDatatable('book-list','admin/book/getenglishbooklist/<?= $langid ?>',[],[],15,
            [
                { "bSortable": false, "aTargets": [ -3 ] ,'sClass': 'dt-body-right'}
                
            ]);
        }else{
            initDatatable('book-list','admin/book/getenglishbooklist/<?= $langid ?>',[],[],15,
            [
                { "bSortable": false, "aTargets": [ -3 ] ,'sClass': 'dt-body-right'}
                
            ]);


        }

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