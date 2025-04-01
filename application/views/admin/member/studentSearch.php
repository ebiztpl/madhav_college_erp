<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i> <?php //echo $this->lang->line('library'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
    ?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?> </div> <?php }?>
                        <div class="row">
                            <div class="">
                                <form role="form" action="<?php echo site_url('admin/member/student') ?>" method="post" class="">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                            <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
foreach ($classlist as $class) {
    ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                    <?php
$count++;
}
?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('section'); ?></label>
                                            <select  id="section_id" name="section_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
if (isset($resultlist)) {
    ?>
                        <div class="" id="tachelist">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><?php echo $this->lang->line('student_members_list'); ?></h3>


                                <span style="margin-left: 10%;font-size:25px"><b>Total Members/Students</b> :<span style="color:green" class="totolbookcopies"><span id="totalmembers"></span>/<?php echo count($resultlist)  ?></span> </span>


                                <div class="box-tools pull-right">







                                
                                <span class="badge badge-success success" style="background-color:#dff0d8;color:#dff0d8">Green</span> <b>Indicates Already A Libaray Member</b>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="mailbox-controls">
                                </div>
                                <div class="table-responsive mailbox-messages overflow-visible-lg">
                                    <div class="download_label"><?php echo $this->lang->line('student_members_list'); ?>
                                
                                    
                                
                                </div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <!-- <th><?php echo $this->lang->line('member_id'); ?></th> -->
                                                <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                                <th>Card Issue Date</th>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th>Roll Number</th>
                                                <th><?php echo $this->lang->line('class'); ?></th>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
     
                                                <th><?php echo $this->lang->line('gender'); ?></th>
                                                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                <th>Total Fess</th>
                                                <th>Fess Submitted</th>
                                                <th>Balance Amount</th>
                                                <th>Created At</th>
                                                <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        if (empty($resultlist)) {
                                                ?>

                                                                                        <?php
                                        } else {
                                                $count = 1;
                                               $gdh = 0;

                                                foreach ($resultlist as $student) {
                                                    $clsactive       = "a";
                                                    $member_id       = "";
                                                    $library_card_no = "";
                                                    if ($student['libarary_member_id'] != 0) {
                                                        $gdh++;
                                                        $clsactive       = "success";
                                                        $member_id       = $student['libarary_member_id'];
                                                        $library_card_no = $student['library_card_no'];
                                                    }
                                                    ?>
                                                    <tr class="<?php echo $clsactive; ?>">
                                                        <!-- <td><?php echo $member_id; ?></td> -->
                                                        <td><?php echo $library_card_no; ?></td>
                                                    
                                                        <td><?php  if($student['libaray_card_date']??''){ echo ""; echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['libaray_card_date']??''));} ?></td>

                                                    
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td>
                                                            <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>

                                                        </td>
                                                        <td>  <?= $student['roll_no'] ?> </td>
                                                        <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        
                                                        <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                                        <td><?php echo $student['mobileno']; ?></td>
<!-- custom code -->
 <?php

$total_amount = 0;
//   $total_discount_amount += $fee_discount;
   $total_deposite_amount = 0;
//       $total_fine_amount += $fee_fine;
   $feetype_balance = 0;
   $total_balance_amount = 0;
$student_due_fee       = $this->studentfeemaster_model->getStudentFees3([$student['student_session_id']]);
////
foreach ($student_due_fee as $key => $fee) {

    foreach ($fee->fees as $fee_key => $fee_value) {



        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;

        // echo "<pre>";
        // print_r($fee);die;
        
            // $this->db->select()->from('student_fees_master_head');
            // $this->db->where('student_fees_master_head.fee_master_id', $fee_value->id);
            // $this->db->where('student_fees_master_head.head_id', $fee_value->fee_groups_feetype_id);
            // $queryy = $this->db->get();
            // if ($queryy->num_rows() > 0) {


                if (!empty($fee_value->amount_detail)) {
                    $fee_deposits = json_decode(($fee_value->amount_detail));
        
                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                        $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                        $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                        $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                    }
                }
                if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                    $fees_fine_amount       = $fee_value->fine_amount;
                    $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                }
     

        $total_amount += $fee_value->amount;
     //   $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
 //       $total_fine_amount += $fee_fine;
        $feetype_balance = $fee_value->amount - ($fee_paid);
        $total_balance_amount += $feetype_balance;
    // }

}

}
?>
<!-- custom end -->   
 <td><?php echo $total_amount??0; ?></td>
                                                        <td><?php echo $total_deposite_amount??0; ?></td>
                                                        <td><?php echo $total_balance_amount??0; ?></td>
                                                        <td><?php if($student['created_at']){ echo date("d-m-Y", strtotime($student['created_at'])); } ?></td>
                                                        <td class="text text-right">
                                                            <?php
                                                            if ($student['libarary_member_id'] == 0) {
                                                                            ?>
                                                                <button  data-stdid="<?php echo $student['id'] ?>" class="btn btn-default btn-xs add-student"  data-toggle="tooltip" title="<?php echo $this->lang->line('add'); ?>" >
                                                                    <i class="fa fa-plus"></i>
                                                                </button>

                                                                <?php
                                                                    } else {
                                                                                    ?>
                                                                <button type="button" class="btn btn-default btn-xs surrender-student" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" data-toggle="tooltip" data-memberid="<?php echo $member_id; ?>" title="<?php echo $this->lang->line('surrender_membership'); ?>"><i class="fa fa-mail-reply"></i></button>

                                                                <?php
                                                                    }
                                                                                ?>
                                                        </td>
                                                    </tr>
                                                    <?php
$count++;
        }
    }
    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
}
?>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"  aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="lineModalLabel"><?php echo $this->lang->line('add_member'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="click_member_id" value="0" id="click_member_id">
                <!-- content goes here -->
                <form action="<?php echo site_url('admin/member/add') ?>" id="add_member" method="post">
                    <input type="hidden" name="member_id" value="0" id="member_id">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('library_card_no'); ?></label><small class="req"> *</small></label>
                        <input type="name" class="form-control" name="library_card_no" required id="library_card_no" >
                        <span class="text-danger" id="library_card_no_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Date</label>
                        <input type="text" value="<?php echo set_value('library_card_date', date($this->customlib->getSchoolDateFormat())); ?>" class="form-control datee" name="library_card_date" id="library_card_date" >
                        <span class="text-danger" id="library_card_date"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm add-member" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><?php echo $this->lang->line('add'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#squarespaceModal").modal({
            show: false,
            backdrop: 'static'
        });
    });

    var base_url = '<?php echo base_url() ?>';
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    $(".surrender-student").click(function () {
        if (confirm('<?php echo $this->lang->line("are_you_sure_you_want_to_surrender_membership"); ?>')) {

            let reason = prompt("Please enter surrender reason");
               if(reason){
            var memberid = $(this).data('memberid');
            var $this = $(this);
            $this.button('loading');
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('admin/member/surrender') ?>',
                data: {'member_id': memberid, 'reason':reason }, // serializes the form's elements.
                dataType: 'JSON',
                success: function (response)
                {
                    if (response.status == "success") {
                        successMsg(response.message);
                        $this.button('reset');
                        if(response.row_affected){
                        window.setTimeout('location.reload()', 3000);

                        }else{
                            
                        }
                    }
                }
            });

        }else{

            alert('Error ! Surrender Reason Is Required')
        }
        }
    });

    $(".add-student").click(function () {
        var student = $(this).data('stdid');
        $('#click_member_id').val(student);
        $('#member_id').val(student);
        $('#squarespaceModal').modal('show');
    });

    $("#add_member").submit(function (e) {
// alert(('#library_card_no').val())
// return false;
        var student = $('#click_member_id').val();
        var $this = $('.add-member');
        $this.button('loading');
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $("#add_member").serialize(), // serializes the form's elements.
            dataType: 'JSON',
            success: function (response)
            {
                if (response.status == "success") {
                    $('#squarespaceModal').modal('hide');
                    $('#add_member')[0].reset();
                    successMsg(response.message);
                    $this.button('reset');
                    $('*[data-stdid="' + student + '"]').closest('tr').find('td:first').text(response.inserted_id);
                    $('*[data-stdid="' + student + '"]').closest('tr').find('td:nth-child(2)').text(response.library_card_no);
                    $('*[data-stdid="' + student + '"]').closest("tr").addClass("success");
                    $('*[data-stdid="' + student + '"]').closest("td").empty();
                         window.setTimeout('location.reload()', 3000);
                } else if (response.status == "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                    $this.button('reset');
                }

            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
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
$(window).load(function() {
    $('#totalmembers').text(<?= $gdh ?>);
    if($('#section_id').val() == ''){
        $('#class_id').trigger('change')

    }
});
</script>