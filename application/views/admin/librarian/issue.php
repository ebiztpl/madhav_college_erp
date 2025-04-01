<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<!-- <script>
        $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
  </script> -->
<style type="text/css">
    .qty_error{
        display: none;
    }
</style>
<style type="text/css">

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px !important; border-radius: 0 !important; padding-left: 0 !important;}
    .input-group-addon .glyphicon{font-size: 12px;}

    .show{
        display : block;
        z-index: 100;
        background-image : url('../../backend/images/timeloader.gif');
        opacity : 0.6;
        background-repeat : no-repeat;
        background-position : center;
    }
    /* .tab-pane{min-height: 200px;}*/
    .commentForm .input-group {position: relative;display: block;border-collapse: separate;}
    .commentForm .input-group-addon{
        position: absolute;
        right: 26px;
        top: 0px;
        z-index: 3;
    }
    .relative{position: relative;}
    .commentForm .input-group-addon i,
    .commentForm .input-group-addon span{padding-left: 13px;}
    .commentForm .relative label.text-danger{position: absolute; bottom: 5px;}
    .addbtnright{ position: absolute;right: 0;top: -46px;}

    @media(max-width:767px){
        .timeresponsive{overflow-x: auto;     overflow-y: hidden;}
        .timeresponsive .dropdown-menu{z-index: 1060;    bottom: 0 !important; height: 250px; padding: 20px;}
        .tablewidthRS{width: 690px;}
    }

   
</style>

<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <?php
if ($memberList->member_type == "student") {
    $this->load->view('admin/librarian/_student');
} elseif($memberList->member_type == "guest") {
    $this->load->view('admin/librarian/_guest');
}


else {
    $this->load->view('admin/librarian/_teacher');
}
?>

            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <!-- <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('issue_book'); ?></h3>
                    </div> -->


                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('issue_book'); ?></h3>
                        <div class="box-tools pull-right">



                        <input type="radio" id="html" name="fav_language" class="types" value="name" checked>
<label for="html">Issue By Book Name</label> &nbsp
<input type="radio" id="css"  class="types" name="fav_language" value="number">
<label for="css">Search By Accession No.</label>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
<!-- book report -->

<div class="box-body">
    <div class="col-lg-12 col-md-12 col-sm-12">
            <?php 
 $per = ($already_book_issued/$maxbook_book_count) * 100;

?>
    <div class="topprograssstart">
                            <p class="text-uppercase mt5 clearfix"><i class="fa fa-ioxhost ftlayer"></i>Book Issue Limit<span class="pull-right">Book Issued : <?= $already_book_issued ?> <b>/</b> Issue Limit : <?= $maxbook_book_count ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-red" style="width: <?= $per ?>%"></div>
                                </div>
                            </div>
                        </div>



                    </div>
                    </div>


<!-- book report -->

        <?php if($issue_count_check == 1){ ?>


                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" style="display:">

                        <div class="box-body">
                            <?php
if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}
?> 

                            <?php echo $this->customlib->getCSRF(); ?>

                            <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />


                            <div class="form-group">
                                <label>Type Any One About Book For Search -<span style="color:blue">(Title ,Author ,Publisher ,Department ,Subject ,Tag ,Isbn_no)</span></label>
                                <input id="serach_filter" name="serach_filter"  type="text" class="form-control" />
                                <span class="text-danger"><?php echo form_error('return_date'); ?></span>
                            </div>



                            <div class="form-group">
                                <label for="exampleInputEmail1">Books As Per Search<small class="req"> *</small></label>
                                <select autofocus="" id="books_search" name="books" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Available Accession No. <small class="req"> *</small></label>
                                <select autofocus="" id="book_id" name="book_id" class="form-control ">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                  
                                </select>
                                <!-- <span class="text-danger" id="book_already_issued"><?php echo form_error('book_id'); ?></span>
                                <span class="text text-danger qty_error"><b><?php echo $this->lang->line('available_quantity'); ?></b>: <span class="ava_quantity">0</span><span class="bbok_nmae text text-info "></span></span> -->
                            </div>
                            <div class="row">
                            <div class="form-group col-md-6">
                                <label>Issue Date<small class="req"> *</small></label>
                                <input id="datefor" name="issue_date"  type="text" class="form-control datee"  value="<?php echo set_value('issue_date', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label><?php echo $this->lang->line('due_return_date'); ?> <small class="req"> *</small></label>
                                <input id="dateto" name="return_date"  type="text" class="form-control date"  value="<?php echo set_value('return_date', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('return_date'); ?></span>
                            </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right gggg" disabled><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>


<!-- form 2 for issue by direct book number -->
<form id="form2" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" style="display:none">

<div class="box-body">
    <?php
if ($this->session->flashdata('msg')) {
echo $this->session->flashdata('msg');
$this->session->unset_userdata('msg');
}
?> 

    <?php echo $this->customlib->getCSRF(); ?>

    <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />







    <div class="form-group">
        <label for="exampleInputEmail1">Accession No. <small class="req"> *</small></label>
        <input id="book_idd" name="book_idd"  type="text" class="form-control"  value="<?php echo set_value('book_idd'); ?>" />
    
        <input id="book_ide" name="book_id"  type="hidden" class="form-control"  value="" />
    
      <span class="text-danger" id="book_already_issued"><?php echo form_error('book_id'); ?></span>
      <span class="text text-danger qty_error"><b><?php echo $this->lang->line('available_quantity'); ?></b>: <span class="ava_quantity">0</span><span class="bbok_nmae text text-info "></span></span>
    
    
    
    
    
    
        <!-- <span class="text-danger"><?php echo form_error('book_id'); ?></span>
        <span class="text text-danger qty_error"></span> -->
    </div>


    <div class="row">
    <div class="form-group col-md-6">
        <label>Issue Date<small class="req"> *</small></label>
        <input id="datefor" name="issue_date"  type="text" class="form-control datee"  value="<?php echo set_value('issue_date', date($this->customlib->getSchoolDateFormat())); ?>" />
        <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
    </div>
    <div class="form-group col-md-6">
        <label><?php echo $this->lang->line('due_return_date'); ?> <small class="req"> *</small></label>
        <input id="dateto" name="return_date"  type="text" class="form-control date"  value="<?php echo set_value('return_date', date($this->customlib->getSchoolDateFormat())); ?>" />
        <span class="text-danger"><?php echo form_error('return_date'); ?></span>
    </div>
    </div>
</div><!-- /.box-body -->
<div class="box-footer">
    <button type="submit" class="btn btn-info pull-right" id="enbdis" disabled><?php echo $this->lang->line('save'); ?></button>
</div>
</form>
<!-- form 2 for issue duirect by book number -->


<?php }else{ ?>
    <div class="box-body">
    <div class="col-lg-12 col-md-12 col-sm-12">

    <div class="alert alert-danger text-left">Book Issue Limit Exceeded</div>

    </div>  </div>

    <?php  } ?>
                </div>
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_issued'); ?></h3>
                        <div class="box-tools pull-right">   
                                <span class="badge badge-success success" style="background-color:#0000ff38;color:#0000ff38">Blue</span> <b>Indicates Book Lost By Member</b>
                                </div>
                    </div><!-- /.box-header -->
           
                    <!-- form start -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('book_issued'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                    <th>Sno.</th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_number'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due_return_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>
                                        <th>Fine</th>
                                        <th>Fine Status</th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($issued_books)) {
    ?>
                                        <?php
} else {

    $this->db->select()->from('library_setting');
    $this->db->order_by('id', 'desc');
    $query = $this->db->get();
    $FINE = $query->result();
 




    $finee= 0;
    $count = 1;
    foreach ($issued_books as $book) {

        ?>
<?php
if($book['is_returned'] == 2){  ?>
                                            <tr style="background-color:#0000ff38">
                                            <?php        }else{                ?>

                                                <tr>

                                                <?php        }           ?>
                                            <td class="mailbox-name">
                                                    <?php echo $count ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_no'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['duereturn_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php
if ($book['return_date'] != '') {
            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['return_date']));
        }
        ?></td>
                                   
                                 <td><?php 
                                  $now = time(); // or your date as well
                                 $your_date = strtotime(date('Y-m-d', $this->customlib->dateyyyymmddTodateformat($book['duereturn_date'])));
                                 $datediff = $now - $your_date;
                                 $DIFF = round($datediff / (60 * 60 * 24)) - 2;
                                //  echo  "hritk".$DIFF;die; 
                               
                           //      if(($this->lang->line($memberList->member_type) == 'Student') && $book['fine_paid'] !== '1') {

                                 if(($this->lang->line($memberList->member_type) == 'Student')) {
                                    $acc_fine = $FINE[2]->late_fine * $DIFF;
                                 $tot = $acc_fine;
                                 if($tot < 0){
                                    $tot = 0;
                                 }

                                if($book['is_returned'] == 1){
                                    $tot = $book['late_fine']??0 ;
                                }
                                if($book['fine_paid'] !== '1'){
                                 $finee = $finee + $tot;
                                  }
                               
                                 }elseif(($this->lang->line($memberList->member_type) == 'Teacher')){
                                    $acc_fine = $FINE[1]->late_fine * $DIFF;
                                 $tot = $acc_fine;
                                 if($tot < 0){
                                    $tot = 0;
                                 }
                                 if($book['is_returned'] == 1){
                                    $tot = $book['late_fine']??0 ;
                                }
if($book['fine_paid'] !== '1'){
  $finee = $finee + $tot;
}
                            
                                 }else{

                                    $acc_fine = $FINE[0]->late_fine * $DIFF;
                                    $tot = $acc_fine;
                                    if($tot < 0){
                                       $tot = 0;
                                    }
                                    if($book['is_returned'] == 1){
                                        $tot = $book['late_fine']??0 ;
                                    }
   if($book['fine_paid'] !== '1'){
     $finee = $finee + $tot;
   }



                                 }
                             
                                 if($book['is_returned'] == 2){
                                    $tot = $book['late_fine'] ;
                                }
                                 
                                 echo "Rs."." ".$tot;?></td>  
                                   
                                   <td>
                                    <?php


if($book['is_returned'] != 2){

                                    if($tot > 0){
                                    if($book['fine_paid'] == 1){ 
echo "<span style='color:green'>Paid</span>";
                                       
                                       
                                    }else{
                                        echo "<span style='color:red'>Unpaid</span>";
                                    }}else{
                                        echo "<span style='color:blue'>No Due</span>";
                                    } 
                                }else{
                                    
                                    echo "<span style='color:red'>Book Lost</span>";
                                }?>

                                </td>
                                   <td class="mailbox-date pull-right">
                                                    <?php if ($book['is_returned'] == 0) {
            ?>
                                                        <a href="#" class="btn btn-default btn-xs" data-id="<?= $tot ?>"  data-record-id="<?php echo $book['id'] ?>" data-record-member_id="<?php echo $memberList->lib_member_id; ?>" title="<?php echo $this->lang->line('return') . " " . $book['book_title'] ?>" data-toggle="modal" data-target="#confirm-return">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>
                                                        <?php
}if(($book['fine_paid'] == 0) && ($book['is_returned'] == 1)){?>


     <a href="javascript:void(0)" class="btn btn-info btn-xs Remove_Fine"   data-id="<?php echo $book['id'] ?>"  title="Remove Fine">
                                                         Remove Fine
                                                        </a>
 <?php } 
        ?>
                                                </td>
                                            </tr>
                                            <?php
$count++;
    }
}
?>

<!-- <a class="pull-right text-aqua dynamic_calc">Rs. <?= $finee??0 ?></a> -->

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="confirm-return" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <span style="display: flex;justify-content: space-between;align-items: stretch;">
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_return'); ?></h4>


<!-- Lost Book -->
<label style="cursor:pointer" class="btn btn-warning btn-sm " for="lost_book"><input type="checkbox" name="lost_book" class="lost_book" id="lost_book" value="1"> Book Lost</label>
<!-- End Lost Book -->


            </div>
            <form action="<?php echo site_url('admin/member/bookreturn') ?>" method="POST" id="return_book">
                <div class="modal-body issue_retrun_modal-body">
                    <span id="hide_lost_book">
                    <input type="hidden" name="id" id="return_model_id" value="0">
                    
                    <input type="hidden" name="late_fine" id="late_fine" value="">
                    <input type="hidden" name="member_id" id="return_model_member_id" value="0">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('return_date'); ?> <small class="req"> *</small></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datee" id="input-date" name="date" placeholder="<?php echo $this->lang->line('date'); ?>" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                        </div>
                        <div id="error" class="text text-danger"></div>
                    </div>

<span id="plm" style="display:none">
<label for="exampleInputEmail1">Late Fine Status <small class="req"> *</small></label>

                    <div class="row" style="    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;"> 


                    <div class="form-check form-check-inline">
  <input class="form-check-input" name="fine" checked type="radio" id="inlineCheckbox1" value="1">
  <label class="form-check-label" for="inlineCheckbox1">Fine Paid</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" name="fine" type="radio" id="inlineCheckbox2" value="0">
  <label class="form-check-label" for="inlineCheckbox2">Fine Pending</label>
</div>
</div>
</span>

</span>


<span id="show_lost_book"> 
</span>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    // $(document).ready(function () {
    //     $('.js-example-basic-single').select2();
    // });
    
    $(document).ready(function () {
        $('#confirm-return').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
    });


    $(document).on('change', '#book_id', function (e) {
        var vale = $(this).val();
         if(vale){
            $('.gggg').prop('disabled', false);
         }else{
            $('.gggg').prop('disabled', true);
         }
        
    });



    $(document).on('keyup', '#book_idd', function (e) {
        var book_id = $(this).val();
        
        availableQuantity(book_id);
    });

    function availableQuantity(book_id) {
        $('#book_already_issued').html('');
        $('.bbok_nmae').html('');
        $('.ava_quantity').html(0);
        if (book_id != "") {
            $.ajax({
                type: "POST",
                url: base_url + "admin/book/getAvailQuantity",
                data: {'book_id': book_id},
                dataType: "json",
                beforeSend: function () {
                    $('.qty_error').show();
                    $('.ava_quantity').empty().html("<?php echo $this->lang->line('loading'); ?>");
                },
                success: function (data) {
                    if(data.status == '1'){
                     
                    
                    if(data.qty > 0){
                        $('.ava_quantity').html(data.qty);
                        $anbc = '  Book Title : '+data.name["book_title"];
                    $('.bbok_nmae').html($anbc);
                    $('#enbdis').prop('disabled', false);
                    $('#book_ide').val(data.id);
                    }else{
                        $('.ava_quantity').html(data.qty);
                        $anbc = '  Book Title : '+data.name["book_title"];
                    $('.bbok_nmae').html($anbc);
                    $('#enbdis').prop('disabled', true);
                    $('#book_ide').val(data.id);


                    }
                    }else{
                        $('.ava_quantity').html('Accession No. Not Available');

                         $('#enbdis').prop('disabled', true);
                         $('#book_ide').val('');
                         
                    }
                  
                    
                },
                complete: function () {
                }
            });
        } else {
            $('.qty_error').hide();
        }
    }

    $('#confirm-return').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data();
        $('#return_model_member_id').val(data.recordMember_id);
        $('#return_model_id').val(data.recordId);
        $('#late_fine').val(data.id);
      if(data.id == 0){
        $('#plm').hide();
      }else{
        $('#plm').show();
      }

        

    });


    $("form#return_book").submit(function (e) {
        var form = $(this);
        var url = form.attr('action');
        console.log(form);
        var $this = $(this);
        var $btn = $this.find("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: 'JSON',
            beforeSend: function () {
                $btn.button('loading');
            },
            success: function (response, textStatus, xhr) {
                if (response.status == 'fail') {
                    $.each(response.error, function (key, value) {
                        $('#input-' + key).parents('.form-group').find('#error').html(value);
                    });
                }
                if (response.status == 'success') {
                    successMsg(response.message);
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                $btn.button('reset');
            },
            complete: function () {
                $btn.button('reset');
            },
        });
        e.preventDefault();
    });
</script>

<script>
      $(document).on('click', '.Remove_Fine', function () {

        $recordid = $(this).attr('data-id');
       
        if (confirm('Are you sure for removing fine')) {

        $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/member/book_fine_paid/"+$recordid,
        data: {},
        success: function (data) {
            alert('Fine Status Updated Successfully');
            window.location.reload('true');
           
        }
        });
    }
    
});
</script>
<script>
$(document).ready(function () {
        $('.dynamic_calc').text('Rs. <?= $finee??0 ?>');
    });
</script>

<script>
       $(document).on('keyup', '#serach_filter', function () {
        if($(this).val().length > 2){
             $cval = $(this).val();
             $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/searchbook/",
        data: {'book' : $cval},
        success: function (response) {
            $(".selectize-control.form-control.single").hide();
            $("#books_search").empty();
            
            $("#books_search").append(response).select2();
            $('#books_search').select2('refresh');
           
           
        }
        });
    }

});
    </script>

<script>    
  $(document).on('change', '#books_search', function () {

$value = $(this).val();
$.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/searchbookno/",
        data: {'book' : $value},
        success: function (response) {
            $(".selectize-control.form-control.single").hide();
            $("#book_id").empty();
            $("#book_id").append("<option value=''>Select Accession  No.</option>").select2();
            $("#book_id").append(response).select2();
            $('#book_id').select2('refresh');
            $('#book_id').trigger('change')
           
        }
        });
});
</script>

<script>

$(document).on('change', '.types', function (e) {
        var abc = $(this).val();
        if(abc == 'number'){
            $('#form1').hide();
            $('#form2').show();
        }else{
            $('#form1').show();
            $('#form2').hide();
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
    $(document).on('change', '#lost_book', function () {

        if ($(this).prop("checked")) {
                         $('#hide_lost_book').hide();
                         $('#show_lost_book').empty();

                         $issue_id =  $('#return_model_id').val();
              $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/searchbookbyissuenumber/",
        data: {'issue_id' : $issue_id},
        success: function (response) {
           
            $('#show_lost_book').append(response);
           
        }
        });


                } else {
                    $('#hide_lost_book').show();
                    $('#show_lost_book').empty();
                }
            
               
            
    });
 </script>   