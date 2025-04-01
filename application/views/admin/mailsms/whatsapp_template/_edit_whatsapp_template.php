<!-- <script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script> -->
<div class="row">
    <input name="id" type="hidden" class="form-control"  value="<?php echo $sms_template_list->id; ?>" />
    <div class="col-lg-12 col-md-12 col-sm-12">


        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                    <input name="title" type="text" class="form-control"  value="<?php echo $sms_template_list->title; ?>" />
                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                    <label>Template Name</label><small class="req"> *</small>
                    <input name="message" class="form-control" value="<?php echo $sms_template_list->template_name; ?>"></textarea>
                    <span class="text-danger"><?php echo form_error('message'); ?></span>
                </div>
            </div>
        </div>


        <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                    <label>Image</label>
                    <input id="checkk" class="check" type="checkbox" <?php if($sms_template_list->image){ ?> checked <?php } ?>>
                    </div>
                </div>


                 <div class="col-sm-10" id="imaglinkk" style="display:none">
                    <div class="form-group">
                        <label>Image Link</label>
                        <input name="image_link" class="form-control" type="text" id="link2"  value="">
                    </div>
                </div>

        </div>






    </div>
</div>



<script>
   $('#checkk').click(function(){
if ($(this).is(":checked")) {
  $("#imaglinkk").show();
}else{
    $("#imaglinkk").hide();
}
});

$( document ).ready(function() {
    if ($("#checkk").is(":checked")) {
  $("#imaglinkk").show();
  $("#link2").val('<?php echo $sms_template_list->image; ?>');
}else{
    $("#imaglinkk").hide();
    $("#link2").val('');
}
});


</script>
