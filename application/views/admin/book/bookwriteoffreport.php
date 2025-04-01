<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_library') ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <!-- <div class="box-header ptbnull"></div> -->
                    <!-- <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <div class="box-body row">
                            <div class="">
                                
                            </div>
                        </div> -->
                    <div class="">
                        <div class="box-header ptbnull"></div>  
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> Book Lost Report</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('book_due_report').' '.$this->customlib->get_postmessage();
                                            ?> </div>

<table width="100%" class="table table-striped table-bordered table-hover example  " data-export-title="Lost <?php echo $this->lang->line('book_list'); ?>">
                                <thead>
                                    <tr>

                                 <?php   if ($this->rbac->hasPrivilege('books', 'can_delete')) { ?>
                                        <th>#</th>
                                        <?php    }?>
                                        
                                     
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_category'); ?></th>
                                        
                                        <th><?php echo $this->lang->line('publisher'); ?></th>
                                        <th><?php echo $this->lang->line('author'); ?></th>
                                   
                                        <th ><?php echo $this->lang->line('book_price'); ?></th>
                                        <th >Publishing Year</th>
                                        <th>Created Date</th>
                                        <th>Write Off Year</th>
                                        <th class="no-print text text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                               
                                <tbody>


<?php
 $ai = 0;
 $book_array = array();
                                foreach ($bookbycode as $value) {




 $ai++;
echo  "<tr>";

$editbtn   = '';
$deletebtn = '';



if ($this->rbac->hasPrivilege('books', 'can_delete')) {
    //$bulkdel = '<span style="display: flex;"><input name="checkbox[]" class="ids" type="checkbox" value="'.$value->id.'" style="margin-right:5px"> </span>';
    $bulkdel = ')';

}

if ($this->rbac->hasPrivilege('books', 'can_edit')) {
    $viewbtn = "<a data-id='". $value->id . "'   class='btn btn-default btn-xs viewbooks'  data-toggle='tooltip' title='View Books'><i class='fa fa-eye'></i></a>";

    $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
}

if ($this->rbac->hasPrivilege('books', 'can_delete')) {
    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
}


echo "<td style='display: flex;'>".$ai.'&nbsp'.$bulkdel."</td>";


echo "<td>".$value->book_title."</td>";


$abc =$value->book_category;
$this->db->select()->from('book_category');
$this->db->where('book_category.id', $abc);
$query = $this->db->get();
$book_category = $query->row();




if($book_category){
    echo "<td>".$book_category->book_category."</td>";
}else{
    echo "<td>NA</td>";
}




$this->db->select()->from('library_dropdown_data');
$this->db->where('library_dropdown_data.id', $value->publish);
$query = $this->db->get();
$val = $query->row();
if($val){
    echo "<td>".$val->name."</td>";
}else{
    echo "<td>NA</td>";
}


$this->db->select()->from('library_dropdown_data');
$this->db->where('library_dropdown_data.id', $value->author);
$query = $this->db->get();
$val = $query->row();

if($val){
    echo "<td>".$val->name."</td>";
}else{
    echo "<td>NA</td>";
}

echo "<td>".$currency_symbol . amountFormat($value->perunitcost)."</td>";
echo "<td>".$value->publishing_year."</td>";
echo "<td>".$this->customlib->dateformat($value->created_at)."</td>";
echo "<td>".$value->writeoffyear."</td>";

echo '<td><span>'.$viewbtn.'</span><span><a data-id="'. $value->id.'" class="btn btn-danger btn-xs removewriteoff" data-toggle="tooltip" title="" data-original-title="Remove Book Write Off">Remove Write Off</a></span></td>';
echo "</tr>";
}


?>



                                </tbody>
                          
                            </table><!-- /.table -->


                      
                        </div>
                    </div>
                </div>
            </div>
        </div>   
</div>  
</section>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">All Books Copies List</h4>
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

$(document).on('click', '.removewriteoff', function () {
    if (confirm('Are you sure ! This book was not writeoffed')) {
 $book = $(this).attr('data-id');

 $.ajax({
   type: "POST",
   url: "<?= base_url() ?>" + "admin/book/removewriteoffbook",
   data: {'book' : $book},
   success: function (response) {
alert(response);
location.reload();
   }
   });

    }
});

</script>