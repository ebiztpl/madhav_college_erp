<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php //echo $this->lang->line('certificate'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('student_certificate', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_student_certificate'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" enctype="multipart/form-data" action="<?php echo site_url('admin/certificate/index') ?>"  id="certificateform" name="certificateform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php 
                                        echo $this->session->flashdata('msg'); 
                                        $this->session->unset_userdata('msg'); 
                                    ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('certificate_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="certificate_name" name="certificate_name"  type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('certificate_name'); ?></span>
                                </div>
                               
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('background_image'); ?></label><small class="req"> *</small>
                                    <input id="documents" type="file" class="filestyle form-control" data-height="40" name="background_image">
                                    <span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('student_certificate', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('student_certificate_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('student_certificate_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('certificate_name'); ?></th>
                                        <th><?php echo $this->lang->line('background_image'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($certificateList)) {
    ?>
                                        <?php
} else {
    $count = 1;
    foreach ($certificateList as $certificate) {
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a style="cursor: pointer;" class="view_data" id="<?php echo $certificate->id ?>" data-toggle="popover" class="detail_popover" ><?php echo $certificate->certificate_name; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($certificate->background_image != '' && !is_null($certificate->background_image)) {?>
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/'.$certificate->background_image); ?>" width="40" height="40" class="object-fit-cover fit-image-40">
                                                    <?php } else {?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="mailbox-date text-right no-print white-space-nowrap">
                                                    <!-- <a data-toggle="tooltip" id="<?php echo $certificate->id ?>" class="btn btn-default btn-xs view_data" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a> -->

                                                      <?php 
                                                              $this->db->select()->from('certificate_layout');
                                                              $this->db->where('certificate_id',$certificate->id);
                                                              $query =  $this->db->get();
                                                              $certificatedata = $query->row();

                                                              ?>


                                                    <a <?php if($certificatedata){ ?>href="<?php echo base_url(); ?>admin/certificate/set_positions/<?php echo $certificate->id ?>?gt=1" <?php }else{ ?> href="<?php echo base_url(); ?>admin/certificate/set_positions/<?php echo $certificate->id ?>"  <?php } ?> class="btn btn-default btn-xs"  <?php if($certificatedata){ echo  'style="color:red"  title="Reset Preview"';  }else{ echo  'title="Set Preview"' ;  } ;?>>
                                                        <i class="fa fa-refresh"></i>
                                                    </a> 

                                                     <?php if($certificatedata){  ?>
                                                    <a  href="<?php echo base_url(); ?>admin/generatecertificate/generatemultiplenew/<?php echo $certificate->id ?>" class="btn btn-default btn-xs" title="Reorder Preview" target="blank">
                                                        <i class="fa fa-reorder"></i>
                                                    </a> 
                                                    <?php }  ?>

                                                    <?php
if ($this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/certificate/edit/<?php echo $certificate->id ?>" class="btn btn-default btn-xs" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('student_certificate', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/certificate/delete/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php
}
    $count++;
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" style="width: 100%;" >
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('view_certificate'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
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
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.view_data').click(function () {
            var certificateid = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url('admin/certificate/view') ?>",
                method: "post",
                data: {certificateid: certificateid},
                success: function (data) {
                    $('#certificate_detail').html(data);
                    $('#myModal').modal("show");
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function valueChanged()
    {
        if ($('#enable_student_img').is(":checked"))
            $("#enableImageDiv").show();
        else
            $("#enableImageDiv").hide();
    }
</script>
<script>
    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>