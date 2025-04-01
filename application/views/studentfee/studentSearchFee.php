<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form class="studentsearchfee" action="<?php echo site_url('studentfee/feesearch') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">

<!-- <?php echo validation_errors(); ?> -->

                                

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                        <select  id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                        <?php

}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" id="search_filter" class="btn btn-sm btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php


$studentscal = $this->student_model->calculateByClassSection(set_value('class_id'), set_value('section_id'));
                        $total_bulk_discount = $this->studentfeemaster_model->getStudent_bulkdiscounts_Sum($studentscal);

if (isset($student_remain_fees)) {
    ?>
                        <div class="" id="duefee">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull row">
                                <div class="col-md-2">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
</div>
<div class="col-md-10">
                                <div class="row">
                                <div class="col-md-3" style="font-size:22px">Total Amount:<span style="color:green" id="total_amount"></span></div>
                                <div class="col-md-3" style="font-size:22px">Total Paid:<span style="color:green" id="total_paid"></span></div>
                                <div class="col-md-3" style="font-size:22px">Total Discount:<span style="color:green" id=""><?= $total_bulk_discount->discount??0 ?></span></div>
                                <div class="col-md-3" style="font-size:22px">Total Balance:<span style="color:green" id="total_balance"></span></div>
</div>      </div>
                            </div>
                            <div class="box-body table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr><th>SR</th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                          
                                            <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <!-- <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th> -->
                                            <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                          $final_paid = 0;
                                          $final_amount = 0;
                                          $final_balance = 0;
                                        if (empty($student_remain_fees)) {
        ?>

                                            <?php
} else {
    
        $count = 0;
        foreach ($student_remain_fees as $student) {
           
            $student_due_fee       = $this->studentfeemaster_model->getStudentFees3([$student['student_session_id']]);
      
              
            $total_amount = 0;
               $total_deposite_amount = 0;
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
                                    // $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                    // $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                }
                            }
                            // if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                            //     $fees_fine_amount       = $fee_value->fine_amount;
                            //     $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                            // }
                 
            
                    $total_amount += $fee_value->amount;
                 //   $total_discount_amount += $fee_discount;
                    $total_deposite_amount += $fee_paid;
             //       $total_fine_amount += $fee_fine;
                    // $feetype_balance = $fee_value->amount - ($fee_paid);
                    // $total_balance_amount += $feetype_balance;
                // }
            





            }

         
            }
            $final_paid += $total_deposite_amount;
            $final_amount += $total_amount;
            $total_balance_amount =  $total_amount - $total_deposite_amount;
            $final_balance += $total_balance_amount;
             
         
            if($total_balance_amount){
            $count++;
            $tot_discount = $this->studentfeemaster_model->getStudent_bulkdiscounts_Sum([$student['student_session_id']]);

            ?>
                                                <tr>
                                                    <td><?= $count ?></td>
                                                    <td><?php echo $student['class'] . "-" . $student['section']; ?></td>
                                                    <td><?php echo $student['admission_no']; ?></td>
                                                    <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                    <td class="text text-right"><?php echo $total_amount??0; ?></td>
                                                    <td class="text text-right"><?php echo $total_deposite_amount??0; ?></td>
                                                    <td class="text text-right"><?= $tot_discount->discount??0 ?></td>
                                                    <td class="text text-right"><?php echo $total_balance_amount??0; ?></td>
                                                    <td class="text text-right">
                                                        <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) {?><a href="<?php echo base_url(); ?>studentfee/addfee/<?php echo $student['student_session_id'] ?>" class="btn btn-info btn-xs">
                                                            <?php echo $currency_symbol; ?> <?php echo $this->lang->line('add_fees'); ?>
                                                            </a>
                                                <?php }?>
                                                    </td>
                                                </tr>
        <?php  }
}

    }
    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
} else {

}
?>

    </section>
</div>
<script type="text/javascript">
    $(document).on('submit','.studentsearchfee',function(e){
         document.getElementById("search_filter").disabled = true;
    });

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
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
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#dob,#admission_date').datepicker({
            format: date_format,
            autoclose: true
        });
    });

    function getSectionByClass(class_id, section_id) {
            console.log((section_id));
        if (class_id != "") {
            $('#section_id').html("");
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
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        var fcat = $("#feecategory_id option:selected").text();
        var ftype = $("#feetype_id option:selected").text();
        var cls = $("#class_id option:selected").text();
        var sec = $("#section_id option:selected").text();
        $('.fcat').html(fcat);
        $('.ftype').html(ftype);
        $('.cls').html(cls + '(' + sec + ')');
        Popup(jQuery(elem).html());
    }

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
</script>

<script>
    $("#custom-select").on("click",function(){
        $("#custom-select-option-box").toggle();
    });

    $(".custom-select-option").on("click", function(e) {
        var checkboxObj = $(this).children("input");
        if($(e.target).attr("class") != "custom-select-option-checkbox") {
                if($(checkboxObj).prop('checked') == true) {
                    $(checkboxObj).prop('checked',false)
                } else {
                    $(checkboxObj).prop("checked",true);
                }
        }
    });


$(document).on('click', function(event) {
  if (event.target.id != "custom-select" && !$(event.target).closest('div').hasClass("custom-select-option")  ) {
          $("#custom-select-option-box").hide();
     }
});

$(document).on('change','#select_all',function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
    //    $('.checkbox').not(this).prop('checked', this.checked);
});



$(document).on('change','input[name="newcheckbox"]',function(){
    $id = $(this).attr('id');
   $('.'+$id+'checkbox').not(this).prop('checked', this.checked);
});





</script>
<?php
if (isset($student_remain_fees)) {
    ?>
<script>
$(window).load(function() {

    $('#total_amount').html('<?= $final_amount  ?>');
     $('#total_paid').html('<?= $final_paid  ?>');
     $('#total_balance').html('<?= $final_balance  ?>');
});

</script>
<?php } ?>
