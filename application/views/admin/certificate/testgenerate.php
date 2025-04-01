<style type="text/css">
    *{padding: 0; margin:0;}
    body{ font-family: 'arial';}
    .tc-container{width: 100%;position: relative; text-align: center;padding: 2%;}
    .tc-container tr td{vertical-align: bottom;}
    /*.tc-container{
        width: 100%;
        padding: 2%;
        position: relative;
        z-index: 2;
    }*/
    .tcmybg {
        background:top center;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1;
    }
    .tc-container tr td h1, h2 ,h3{margin-top: 0; font-weight: normal;}
    /*@media (max-width:210mm) and (min-width:297mm){
        .tc-container{
            margin-top: 200px;
            margin-bottom: 100px;}
    }*/

    [type=button]:not(:disabled), [type=reset]:not(:disabled), [type=submit]:not(:disabled), button:not(:disabled) {
    cursor: pointer;
}
.btn-info {
    color: #000;
    background-color: #0dcaf0 !important;
    border-color: #0dcaf0 !important;
}

.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    border-radius: .25rem;
    transition: color .15sease-in-out, background-color .15sease-in-out, border-color .15sease-in-out, box-shadow .15sease-in-out;
}
[type=button], [type=reset], [type=submit], button {
    -webkit-appearance: button;
}
button, select {
    text-transform: none;
}
button, input, optgroup, select, textarea {
    margin: 0;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
</style>

<?php

$certificate[0]->certificate_text = str_replace('[name]', '[name]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[present_address]', '[current_address]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[guardian]', '[guardian_name]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[phone]', '[mobileno]', $certificate[0]->certificate_text);

foreach ($students as $student) {
    $certificate_body = "";
    $certificate_body = $certificate[0]->certificate_text;

    foreach ($student as $std_key => $std_value) {

        if ($std_key == "dob") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }
        if ($std_key == "admission_date") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }
        if ($std_key == "created_at") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }       
        
        $certificate_body = $std_value ? str_replace('[' . $std_key . ']', $std_value, $certificate_body) : $certificate_body;
         
    }
    ?>

    <div class="" style="position: relative; text-align: left; font-family: 'arial';">
        <?php if (!empty($certificate[0]->background_image)) { ?>
            <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/' . $certificate[0]->background_image); ?>"  style="width:<?= $certificatedata->width??'' ?>;height:<?= $certificatedata->height??'' ?>" id="imgsize"/>
        <?php } ?>

      

<!-- start new code -->

<?php
if($certificatedata){
    if($certificatedata->data){
$certificatedataa = json_decode($certificatedata->data);

foreach($certificatedataa  as $feild){ 


    
    ?>




    <div id="<?=  $feild->name; ?>" class="certicate_feilds" style="<?=  $feild->style; ?>"><?=  $student[$feild->name]; ?></div>

<?php
}}


}


?>
<!-- end new code -->
    </div>




    <div class="box-footer" style="padding:20px">
                                <button type="submit" class="btn btn-info pull-right save_layout"><?php echo $this->lang->line('save'); ?></button>
                            </div>





    <?php
}
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    // selected.classList.add("certicate_feilds");
  }
}

// Destroy the object when we are done
function _destroy() {
  selected = null;
}

// Bind the functions...


<?php      foreach($certificatedataa  as $feild){  ?>

document.getElementById('<?= $feild->name ?>').onmousedown = function() {



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
    $.ajax({
    url: '<?php echo site_url("admin/certificate/savecertificatelayout") ?>',
    type: 'POST',
    data:  {'certificateid': <?=$certificate[0]->id; ?>,'data': arr,'width':$imgwidth,'height':$imgheight},
    success: function (response) {
        alert('Final Layout Created Successfully');
        window.location.href = "<?php echo site_url("admin/certificate") ?>";

    }
    });
});
</script>


