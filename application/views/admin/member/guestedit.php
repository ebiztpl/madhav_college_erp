<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i>Guest Edit</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Guest Edit</h3>
                    </div>
                    <form action="<?php echo site_url('admin/member/guestedit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
    ?>
                                <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                <input autofocus="" id="category" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $librarian['name']); ?>" />
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                <input id="category" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email', $librarian['email']); ?>" />
                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                                <select class="form-control" name="gender">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
foreach ($genderList as $key => $value) {
    ?>
                                        <option value="<?php echo $key; ?>" <?php if (set_value('gender', $librarian['gender']) == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                                        <?php
}
?>
                                </select>
                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                <input id="dob" name="dob" placeholder="" type="text" class="form-control datee"  value="<?php echo set_value('dob', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($librarian['dob']))); ?>" readonly="readonly"/>
                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                <textarea id="address" name="address" placeholder=""  class="form-control" ><?php echo set_value('address', $librarian['address']); ?></textarea>
                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Contact Number</label><small class="req"> *</small>
                                <input id="phone" name="phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('phone', $librarian['phone']); ?>" />
                                <span class="text-danger"><?php echo form_error('phone'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">ID Proof</label>
                                <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control" value="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?>/Refrence</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description', $librarian['note']); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                <div class="box-header ptbnull">
                                <h3 class="box-title titlefix">Guest List</h3>
                                <span style="margin-left: 3.5%;font-size:20px"><b>Total Members/Guest</b> :<span style="color:green" class="totolbookcopies"><span id="totalmembers"></span></span>/<?php echo count($guestList)  ?> </span>
                                <div class="box-tools pull-right">
                                <span class="badge badge-success success" style="background-color:#dff0d8;color:#dff0d8">Green</span> <b>Indicates Already A Libaray Member</b>
                                </div>
                            </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Guest List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Libaray Card Number</th>
                                        <th>Card Issue Date</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($guestList)) {
    ?>

                                        <?php
} else {
    $gdh = 0;
    $count = 1;
    foreach ($guestList as $librarian) {
        ?>
         <?php



$this->db->select()->from('libarary_members');
$this->db->where('libarary_members.member_type','guest');
$this->db->where('libarary_members.member_id',$librarian['id']);
$querycheck = $this->db->get()->row();
if ($querycheck) { 
    $gdh++;?>
                                            <tr style="background-color:#dff0d8">
                                           <?php }else{    ?>
                                           <tr>
                                           <?php }    ?>
                                           <td class="mailbox-name"><?= $querycheck->library_card_no??'' ?></td>
                                            <td> <?php  if($querycheck->libaray_card_date??''){ echo ""; echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($querycheck->libaray_card_date??''));} ?></td>
                                                                                     <td class="mailbox-name"> <?php echo $librarian['name'] ?></td>
                                                <td class="mailbox-name"> <?php echo $librarian['email'] ?></td>
                                                <td class="mailbox-name"> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($librarian['dob'])); ?></td>
                                                <td class="mailbox-name"> <?php echo $librarian['phone'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                <?php if($librarian['documents']){?>
                                                        <a href="<?php echo base_url(); ?>admin/member/download/<?php echo $librarian['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Download">
                                                        <i class="fa fa-download"></i> </a>

                                                        <?php   }?>
                                                    <a href="<?php echo base_url(); ?>admin/member/guestedit/<?php echo $librarian['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/member/guestdelete/<?php echo $librarian['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
                                                        <i class="fa fa-remove"></i>
                                                    </a>


                                                

                                                       

<?php
                                                    if (! $querycheck) {
                                                        ?>

<button  data-stdid="<?php echo $librarian['id'] ?>" style="width: 79px;" class="btn btn-success btn-xs add-guest"  data-toggle="tooltip" title="<?php echo $this->lang->line('add'); ?>" >
                                                            <i class="fa fa-plus"></i> Member
                                                        </button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" style="width: 79px;" class="btn btn-danger btn-xs surrender-guest" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"  data-toggle="tooltip" data-memberid="<?php echo $querycheck->id; ?>" title="<?php echo $this->lang->line('surrender_membership'); ?>"><i class="fa fa-mail-reply"></i> surrender</button>
                                                        <?php
                                                    }
                                                    ?>





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
    </section>
</div>







<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="window.location.reload
                (true);" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="lineModalLabel"><?php echo $this->lang->line('add_member'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="click_member_id" value="0" id="click_member_id">
                <!-- content goes here -->
                <form action="<?php echo site_url('admin/member/addguestmember') ?>" id="add_member" method="post">
                    <input type="hidden" name="member_id" value="0" id="member_id">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('library_card_no'); ?> <small class="req"> *</small></label>
                        <input required type="name" class="form-control" name="library_card_no" id="library_card_no" >
                        <span class="text-danger" id="library_card_no_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Date</label>
                        <input type="text" value="<?php echo set_value('library_card_date', date($this->customlib->getSchoolDateFormat())); ?>" class="form-control datee" name="library_card_date" id="library_card_date" >
                        <span class="text-danger" id="library_card_date"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm add-member" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('add'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>












<script>
        $(".surrender-guest").click(function () {
        if (confirm('<?php echo $this->lang->line("are_you_sure_you_want_to_surrender_membership"); ?>')) {
            let reason = prompt("Please enter surrender reason");
           
            var memberid = $(this).data('memberid');
            var $this = $('.surrender-guest');
            // $this.button('loading');
            if(reason){
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
                        // window.setTimeout('location.reload()', 3000);
                        location.reload();
                    }
                }
            });
        }else{

alert('Error ! Surrender Reason Is Required');
}
        }
    });
    </script>
    <script>
$(".add-guest").click(function () {
        var student = $(this).data('stdid');
        $('#click_member_id').val(student);
        $('#member_id').val(student);
        $('#squarespaceModal').modal('show');
    });




    $("#add_member").submit(function (e) {
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
                    // $this.button('reset');
                    location.reload();
                    // $('*[data-stdid="' + student + '"]').closest('tr').find('td:first').text(response.inserted_id);
                    // $('*[data-stdid="' + student + '"]').closest('tr').find('td:nth-child(2)').text(response.library_card_no);
                    // $('*[data-stdid="' + student + '"]').closest("tr").addClass("success");
                    // $('*[data-stdid="' + student + '"]').closest("td").empty();
                    
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

});
</script>