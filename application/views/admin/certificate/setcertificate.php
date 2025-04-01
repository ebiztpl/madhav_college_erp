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
                            <h3 class="box-title">Add Certificates Feilds</h3>
                        </div><!-- /.box-header -->
                            <div class="box-body" style="height: ;">
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
                
                



                <?php
                 $stack = array();
                if($certificatedata){
                    
$certificatedata = json_decode($certificatedata->data);

foreach($certificatedata  as $feild){ 
    
   
$stack[] = $feild->name;
    
    ?>



    <div id="<?=  $feild->name; ?>" class="certicate_feilds" style="<?=  $feild->style; ?>"><span style="display: ruby-text;"><?=  $feild->name; ?><i class="fa fa-refresh" style="margin-left:2px;cursor:pointer"></i></span></div>

<?php
}}
?>





                
                               
<div class="row">                     

                          <?php      foreach($feilds as $key => $value){ 
              if (!in_array($key, $stack))
              {
             
                     
                            
                            ?>



           <div id="<?=  $key; ?>" class="" style="
  cursor: move;z-index:2147483647"><span style="display: ruby-text;"><?=  $key; ?><i class="fa fa-refresh" style="margin-left:2px;cursor:pointer"></i></span></div>



         
          
          <?php       }       }  ?>
          </div>
         

                            </div><!-- /.box-body -->
                         
                        <!-- </form> -->
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
                        <h3 class="box-title titlefix">Certificate Design</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                       

                    <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/'.$certificate->background_image); ?>" style="width: -webkit-fill-available !important;" id="imgsize">

                    </div><!-- /.box-body -->
                    <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right save_layout"><?php echo $this->lang->line('save'); ?></button>
                            </div>
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

<script type="text/javascript">
    $(document).ready(function () {
var selected = null, // Object of the element to be moved
  x_pos = 0,
  y_pos = 0, // Stores x & y coordinates of the mouse pointer
  x_elem = 0,
  y_elem = 0; // Stores top, left values (edge) of the element

// Will be called when user starts dragging an element
function _drag_init(elem) {
  // Store the object of the element which needs to be moved
  selected = elem;
  x_elem = x_pos - selected.offsetLeft;
  y_elem = y_pos - selected.offsetTop;
}

// Will be called when user dragging an element
function _move_elem(e) {
  x_pos = document.all ? window.event.clientX : e.pageX;
  y_pos = document.all ? window.event.clientY : e.pageY;
  if (selected !== null) {
    selected.style.left = (x_pos - x_elem) + 'px';
    selected.style.top = (y_pos - y_elem) + 'px';
     selected.style.position = 'absolute';

    
    selected.classList.add("certicate_feilds");
  }
}

// Destroy the object when we are done
function _destroy() {
  selected = null;
}

// Bind the functions...


<?php      foreach($feilds as $key => $value){  ?>

document.getElementById('<?= $key ?>').onmousedown = function() {



_drag_init(this);
return false;
};

<?php   } ?>



document.onmousemove = _move_elem;
document.onmouseup = _destroy;
    });
</script>

<script>
$(document).on('click', '.save_layout', function () {
    var arr = [];

    $(".certicate_feilds").each(function() {
    $id = $(this).attr('id');

    $style = $(this).attr('style');


    arr.push({
        name : $id, 
        style : $style, 
        });

   
});


$imgwidth = $('#imgsize').width();
$imgheight = $('#imgsize').height();


    $masterid = $(this).attr('data-id');
   
    if(arr.length > 0){
       
    $.ajax({
    url: '<?php echo site_url("admin/certificate/savecertificatelayout") ?>',
    type: 'POST',
    data:  {'certificateid': <?=$certificate->id ?>,'data': arr,'width':$imgwidth,'height':$imgheight},
    success: function (response) {
        alert('Basic Feild Layout Created Successfully');
        window.location.href = "<?php echo site_url("admin/generatecertificate/generatemultiplenew") ?>"+"/<?=$certificate->id ?>";

    }
    });}else{
        alert('Feilds Can`t be null');
    }
});
</script>


