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
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <div class="box-body row">
                            <div class="">
                                <form role="form" action="<?php echo site_url('report/surrend_returnreport') ?>" method="post" class="">
                                    <?php echo $this->customlib->getCSRF(); ?>


                                    <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('members_type'); ?></label>
                                    <select class="form-control" id="members_type" name="members_type" >
                                        <?php foreach ($members as $key => $value) { ?>
                                            <option <?php
                                            if (set_value('members_type') == $key) {
                                                echo "selected";
                                            }
                                            ?>  value="<?php echo $key; ?>"><?php echo $value; ?> </option>
                                                <?php
                                            }
                                            ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>

                            <span id="showhide" style="display:none">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> </small>
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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('section'); ?></label>
                                            <select  id="section_id" name="section_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                        </div>
                                    </div>


</span>       




                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <div class="">
                        <div class="box-header ptbnull"></div>  
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> Surrender Membership Report</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('book_due_report').' '.$this->customlib->get_postmessage();
                                            ?> </div>



<table class="table table-striped table-bordered table-hover example" id="members">
                                <thead>
                                    <tr>
                                        <!-- <th><?php echo $this->lang->line('member_id'); ?></th> -->
                                        <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                        <th>Reason</th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('member_type'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="text-right noExport">Surrendered On
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (!empty($memberList)) {
    $count = 1;
// echo "<pre>";
// print_r($memberList);die;
    foreach ($memberList as $member) {

        if ($member['member_type'] == "student") {
            $name  = $this->customlib->getFullName($member['firstname'], $member['middlename'], $member['lastname'], $sch_setting->middlename, $sch_setting->lastname) ;
            $phone = $member['guardian_phone'];
        } else {
            $email = $member['teacher_email'];
            $name  = $member['teacher_name'] . " (" . $member['emp_id'] . ")";
            $sex   = $member['teacher_sex'];
            $phone = $member['teacher_phone'];
        }
        ?>
                                            <tr>
                                                <!-- <td>
                                                    <?php echo $member['lib_member_id']; ?>
                                                </td> -->
                                                <td>
                                                    <?php echo $member['library_card_no']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $member['reason']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $member['admission_no']; ?>
                                                </td>
                                                <td>
                                                <?php if($member['member_type'] == 'student'){ ?>
                                                <a href="<?php echo base_url(); ?>/student/view/<?= $member['stu_id']?>"><?php echo $name; ?></a> 
                                                <?php }else{?>

                                                    <a href="<?php echo base_url(); ?>/admin/staff/profile/<?= $member['staff_id']?>"><?php echo $name; ?></a> 


                                                    <?php } ?>
                                            </td>
                                                <td>
                                                    <?php echo $this->lang->line($member['member_type']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $phone; ?>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                     <?php echo  date("d-m-Y (h:i A)", strtotime($member['created_at'])); ?>
                                                </td>
                                            </tr>
                                            <?php
}
    $count++;
}
?>
                                </tbody>
                            </table>


                      
                        </div>
                    </div>
                </div>
            </div>
        </div>   
</div>  
</section>
</div>
<script>
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

    </script>

<script>
       $(document).on('change', '#members_type', function () {
        var val = $(this).val();
        if(val == "student"){
            $('#showhide').show();
        }else{
            $('#showhide').hide();

        }

});

$(document).ready(function () {

    var val = '<?= set_value('members_type')?>';
        if(val == "student"){
            $('#showhide').show();
        }else{
            $('#showhide').hide();

        }
});
</script>