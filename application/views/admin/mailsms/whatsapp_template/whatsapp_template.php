<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Whatsapp Template List</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('sms_template', 'can_add')) {?>
                            <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-backdrop="static" data-target="#myModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                            <?php }?>
                        </div>
                    </div>
                    <?php if ($this->session->flashdata('message') != '') {?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); $this->session->unset_userdata('message'); ?>
                        </div>
                    <?php }?>
                    <div class="box-body">
                        <div class="table-responsive overflow-visible"><div class="download_label">Whatsapp Template List</div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th>Template Name</th>
                                        <th>Image</th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

foreach ($sms_template_list as $key => $sms_template_list_value) {?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $sms_template_list_value['title'] ?></td>
                                            <td class="mailbox-name"> <?php echo $sms_template_list_value['template_name'] ?></td>
                                            <td class="mailbox-name"> <?php echo $sms_template_list_value['image'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                            <?php if ($this->rbac->hasPrivilege('sms_template', 'can_edit')) {?>
                                                <a class="btn btn-default btn-xs editwhatsapptemplate" data-id="<?php echo $sms_template_list_value['id'] ?>"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i>
                                                </a>
                                            <?php }?>
                                            <?php if ($this->rbac->hasPrivilege('sms_template', 'can_delete')) {?>
                                                <a href="<?php echo base_url(); ?>admin/mailsms/delete_whatsapp_template/<?php echo $sms_template_list_value['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');"><i class="fa fa-remove"></i>
                                                </a>
                                            <?php }?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title">Add WhatsApp Template</h4>
            </div>
            <form id="addsmstemplate" action="<?php echo base_url(); ?>admin/mailsms/add_whatsapp_template" method="post" enctype="multipart/form-data">
                <div class="modal-body ptt10 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input id="title" name="title" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                        <label>Template Name</label><small class="req"> *</small>
                                        <input name="message" class="form-control" type="text"  value="<?php echo set_value('message'); ?>">
                                        <span class="text-danger"><?php echo form_error('message'); ?></span>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-sm-2">
                                  <div class="form-group">
                                        <label>Image</label>
                                        <input id="check" class="check" type="checkbox" >
                                       
                                    </div>
                                </div>


                                <div class="col-sm-10" id="imaglink" style="display:none">
                                  <div class="form-group">
                                        <label>Image Link</label>
                                        <input name="image_link" class="form-control" type="text" id="link1"  value="<?php echo set_value('image_link'); ?>">
                                      
                                    </div>
                                </div>


                            </div>



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-info pull-right add_button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editsmstemplatemodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit_sms_template'); ?></h4>
            </div>
            <form id="editsmstemplateform" action="<?php echo base_url(); ?>admin/mailsms/update_whatsapp_template" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div id="editsmstemplatedata"></div>
                </div>
                <div class="box-footer">
                    <div class="col-sm-12">
                       <button type="submit" class="btn btn-info pull-right edit_button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#addsmstemplate").submit(function (event) {
        event.preventDefault();
        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.add_button');
        $this.button('loading');

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            processData: false,

            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {

                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#addsmstemplate')[0].reset();

                    successMsg(data.message);
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $('.editwhatsapptemplate').click(function(){
    $('#editsmstemplatemodal').modal({
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).attr('data-id');

   $.ajax({
       url:'<?php echo site_url("admin/mailsms/edit_whatsapp_template"); ?>',
       type:'post',
       data:{id:id},
       dataType:'json',
       success:function(response){
          $('#editsmstemplatedata').html(response.page);
       }
   });
})

    $("#editsmstemplateform").submit(function (event) {
        event.preventDefault();

        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.edit_button');
        $this.button('loading');

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            processData: false,

            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {

                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#editsmstemplateform')[0].reset();
                    successMsg(data.message);
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });
</script>
<script>
   $('#check').click(function(){
if ($(this).is(":checked")) {
  $("#imaglink").show();
}else{
    $("#imaglink").hide();
    $("#link1").val('');
    
}
});
</script>

