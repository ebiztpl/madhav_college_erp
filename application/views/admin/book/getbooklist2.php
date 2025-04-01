<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<?php

                    $role = $this->customlib->getStaffRole();
           $ide = json_decode($role)->id;


if($ide != 4){
?>


<script>
        $(document).ready(function () {
            $('select').select2();
            $('#languageSwitcher').select2("destroy");
            $('#currencySwitcher').select2("destroy");
  });
  </script>

  
<?php }else{?>
    <script>
        $(document).ready(function () {
    $('select').select2();
});
    </script>
    <?php 
}
?>
  <style>
    #DataTables_Table_0_filter {

        display:none;
    }
    .dataTables_info{

display:none;
}
#DataTables_Table_0_paginate{

display:none;
}



    .modal-body {
    max-height: calc(100vh - 212px);
    overflow-y: auto;
}
    </style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i> <?php //echo $this->lang->line('library'); ?></h1>
    </section>
    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('books', 'can_add')) {
                                ?>
                                <a href="<?php echo base_url() ?>admin/book">

                                    <button class="btn btn-primary btn-sm" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_book'); ?></button>
                                </a>
                            <?php }
                            ?>
                        </div><!-- /.pull-right -->
                    </div><!-- /.box-header -->
                    <div class="box-body">


<!-- new filters -->


  







<?php if ($this->session->flashdata('msg')) {?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> </div> <?php }?>
<div class="row">
<form role="form" action="<?php echo site_url('admin/book/getallfilters') ?>/0" method="post" id="class_search_form">
                            <div class="col-md-12">
                                <div class="row">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Book Title</label> <small class="req"> </small>
                                                <select autofocus="" id="book_title" name="book_title" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                        $count = 0;
                                                        foreach ($titldata as $dat) {
                                                    ?>
                                                        <option value="<?php echo $dat->book_title ?>" <?php if ($this->session->userdata('book_title') == $dat->book_title) {
                                                            echo "selected=selected";
                                                        }
                                                        ?>><?php echo $dat->book_title ?></option>
                                                    <?php
                                                        $count++;
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger" id="error_class_id"></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Book Category</label>
                                                <select id="book_category" name="book_category" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                        $count = 0;
                                                        foreach ($book_category as $dat) {
                                                    ?>
                                                        <option value="<?php echo $dat->id ?>" <?php if ($this->session->userdata('bookcategory') == $dat->id) {
                                                            echo "selected=selected";
                                                        }
                                                        ?>><?php echo $dat->book_category ?></option>
                                                    <?php
                                                        $count++;
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('book_category'); ?></span>
                                            </div>
                                        </div>
									
									
									<!-- custom filter  by me -->


                                <div class="col-sm-2">
                                    <div class="form-group">   
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <!-- <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" /> -->
                                
                                <select id="author" name="author" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 2){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($this->session->userdata('author') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>    
                                <span class="text-danger"><?php echo form_error('department'); ?></span>
                                    </div>   
                                </div>
                          
                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <select id="publisher" name="publisher" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 1){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($this->session->userdata('publisher') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                </div>  
                            </div>
                         
                              

<!-- custom filter by me -->
									
<div class="col-sm-3">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Subject</label>
                                <select id="Avaiblity" name="Avaiblity" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($dropdowndata as $drop){
                                      if($drop->type == 3){  ?>
                                    <option value="<?=$drop->id?>" <?php
if ($this->session->userdata('avaiblity') == $drop->id) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->name?></option>

                                        <?php } }?>
                                </select>  
                                </div>  
                            </div>
									


                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created By</label>
                                <select id="created_by" name="created_by" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach($created_bylist as $key => $drop){
                                        if( $drop->created_by != ''){
                                ?>
                                    <option value="<?=$drop->created_by?>" <?php
if ($this->session->userdata('created_by') == $drop->created_by) {
            echo "selected = selected";
        } 
        ?> ><?=$drop->created_by?></option>

                                        <?php } }?>
                                </select>  
                                </div>  
                            </div>
                                    


                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created From</label>
                                <div class="input-group">
                            <div class="input-group-addon" id="created_from_eraser" style="cursor:pointer" title="clear date">
                                <i class="fa fa-eraser"></i>
                            </div>
                                <input id="created_from" name="created_from" placeholder="" type="text" class="form-control datee"  value="<?php if($this->session->userdata('created_from')){ echo date('d/m/Y', strtotime(str_replace('-', '/',$this->session->userdata('created_from')))) ; }  ?>" readonly="readonly"/>
    </div>
                                </div>  
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">  
                                <label for="exampleInputEmail1">Created To</label>
                                <div class="input-group">
                            <div class="input-group-addon" id="created_to_eraser" style="cursor:pointer" title="clear date">
                                <i class="fa fa-eraser"></i>
                            </div>
                                <input id="created_to" name="created_to" placeholder="" type="text" class="form-control date"  value="<?php if($this->session->userdata('created_to')){ echo date('d/m/Y', strtotime(str_replace('-', '/',$this->session->userdata('created_to')))) ; }  ?>" readonly="readonly"/>
    </div>
                                </div>  
                            </div>





                            <div class="col-sm-1">
                            <label for="writeoff">Write Off</label>
                            <label style="cursor:pointer" class="btn btn-warning btn-sm " for="writeoff"><input type="checkbox" name="writeoff" <?php if($this->session->userdata('writeoff')){ echo 'checked' ; }  ?> value="1" id="writeoff">Write Off</i></label>									
                            </div>


                            <div class="col-sm-4 row" style="background-color: cornsilk;">
                            <div class="col-sm-3">
                            <label for="pub">Publisher</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="pub"><input type="checkbox" name="pub" value="1" <?php if($this->session->userdata('pub')){ echo 'checked' ; }  ?>  id="pub">Publisher</i></label>									
                            </div>

                            <div class="col-sm-3">
                            <label for="auth">Author</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="auth"><input type="checkbox" name="auth" value="1" <?php if($this->session->userdata('auth')){ echo 'checked' ; }  ?> id="auth">Author</i></label>									
                            </div>


                            <div class="col-sm-3">
                            <label for="sub">Subject</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="sub"><input type="checkbox" name="sub" value="1" <?php if($this->session->userdata('sub')){ echo 'checked' ; }  ?> id="sub">Subject</i></label>									
                            </div>




                            <div class="col-sm-3">
                            <label for="dep">Department</label>
                            <label style="cursor:pointer" class="btn btn-success btn-sm " for="dep"><input type="checkbox" name="dep" <?php if($this->session->userdata('dep')){ echo 'checked' ; }  ?> value="1" id="dep">Department</i></label>									
                            </div>


                            <div class="col-sm-12 text-center">
                                Check For Null Values
                            </div>



                            </div>

									
									
									
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                            <label for="exampleInputEmail1" style="color: white;">abcd</label><label for="exampleInputEmail1" style="color: white;">abcd</label>
                                                <button type="submit" name="search" value="search_filter" id="search_filter" class="btn btn-primary btn-sm checkbox-toggle"><i class="fa fa-search"></i></button>
                                                <a  href="<?php echo site_url('admin/book/getall') ?>" class="btn btn-warning btn-sm  checkbox-toggle"><i class="fa fa-refresh"></i></a>
                                            </div>
                                        </div>


                                     
                                
                            </div><!--./col-md-6-->
                            </div>
                            </form>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>
   <!-- new  code -->     


 <!-- search book  code -->   

 <div class="row">
   <div class="col-md-12">
   <form role="form" action="<?php echo site_url('admin/book/searchbybookcode') ?>" method="post" id="">
   <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Search Book By Accession No.</label>
                                                <input type ="text" required class="form-control" name="book_code" />
                                                
                                                <span class="text-danger"><?php echo form_error('book_category'); ?></span>
                                            </div>
                                            </div>
                                            <div class="col-sm-1">
                                            <div class="form-group">
                                            <label for="exampleInputEmail1" style="color: white;">abcd</label>
                                                <button type="submit" name="search_bookcode" value="search_bookcode" id="search_bookcode" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> 
                                                    <?php echo $this->lang->line('search'); ?>
                                                </button>
                                            </div>
                                        </div>


                                        



    </form>
   </div>   </div>
   <!-- search book  code -->   


                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?> 
                        </div>
                        <div class="mailbox-messages table-responsive overflow-visible-1">
                        <form id="bulkdlete">
                        <a style="cursor:pointer" class="btn btn-danger btn-sm bulkdel" ><i class="fa fa-trash"></i></a>
                        <label style="cursor:pointer" class="btn btn-success btn-sm " for="lef"><input type="checkbox" class="checkkdel" id="lef"> Check All</i></label>
               <div style="display: flex;justify-content: space-between;align-items: flex-end;"> <span><b>Total Books :</b> <?= $countt ?> <?php if($countt > 0){ ?> / <b> Showing Records From</b> <?= $val2 - 14 ?><b> To </b> <?php if($countt > $val2){echo  $val2 ; }else{echo  $countt; } } ?> </span>    
               <span  style="margin-left: 20%;font-size:25px"><b>Total Accession No.</b> :<span style="color:green" class="totolbookcopies"><?= $totalcopies ?> </span> </span>
               
               <div class="model-pager btn-group btn-group-sm" ><a class="btn btn-default <?php if($val1 < 0){ echo "disabled" ;}  ?>" href="<?php echo base_url() ?>admin/book/getallpagination/<?= $val1 ?>" title="Prev"><i class="fa fa-backward no-margin"></i></a><a class="btn btn-default
               <?php
$val1z = $val1;


                if($countt < $val2 && ($countt < intval($val2) - 16)){

           echo "disabled";
                }elseif($countt < (intval($val2))){
                    echo "disabled";
                }else{

                }
               
               ?>
               " href="<?php echo base_url() ?>admin/book/getallpagination/<?= $val2 ?>" title="Next"><i class="fa fa-forward no-margin"></i></a></div> </div> 
                            <table width="100%" class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('book_list'); ?>">
                                <thead>
                                    <tr>

                                 <?php   if ($this->rbac->hasPrivilege('books', 'can_delete')) { ?>
                                        <th>#</th>
                                        <?php    }?>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('book_category'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('description'); ?></th> -->
                                        <!-- <th><?php echo $this->lang->line('isbn_number'); ?></th> -->
                                        <th><?php echo $this->lang->line('publisher'); ?></th>
                                        <th><?php echo $this->lang->line('author'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th>Department</th> -->
                                        <th>Total Accession No.</th>
                                        <th><?php echo $this->lang->line('available'); ?> Accession No.</th>
                                        <th><?php echo $this->lang->line('issued'); ?></th>
                                        <th ><?php echo $this->lang->line('book_price'); ?></th>
                                        <th >Publishing Year</th>
                                        <th>Created Date</th>
                                        <th class="no-print text text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                               
                                <tbody>


<?php

                                foreach ($listbook as $value) {

 $ai++;
echo  "<tr>";

$editbtn   = '';
$deletebtn = '';



if ($this->rbac->hasPrivilege('books', 'can_delete')) {
    $bulkdel = '<span style="display: flex;"><input name="checkbox[]" class="ids" type="checkbox" value="'.$value->id.'" style="margin-right:5px"> </span>';
}

if ($this->rbac->hasPrivilege('books', 'can_edit')) {
    $viewbtn = "<a href='" . base_url() . "admin/book/importcopies/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='Add Copies'><i class='fa fa-plus'></i></a>"."<a data-id='". $value->id . "'   class='btn btn-default btn-xs viewbooks'  data-toggle='tooltip' title='View Books'><i class='fa fa-eye'></i></a>";

    $editbtn = "<a href='" . base_url() . "admin/book/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
}

if ($this->rbac->hasPrivilege('books', 'can_delete')) {
    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/book/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
}


echo "<td style='display: flex;'>".$ai.'&nbsp'.$bulkdel."</td>";
echo "<td>".$value->book_title."</td>";


$abc =$value->book_category;
$this->db->select()->from('book_category');
$this->db->where('book_category.id', $abc);
$query = $this->db->get();
$book_category = $query->row();




if($book_category){
    echo "<td>".$book_category->book_category."</td>";
}else{
    echo "<td>NA</td>";
}




$this->db->select()->from('library_dropdown_data');
$this->db->where('library_dropdown_data.id', $value->publish);
$query = $this->db->get();
$val = $query->row();
if($val){
    echo "<td>".$val->name."</td>";
}else{
    echo "<td>NA</td>";
}


$this->db->select()->from('library_dropdown_data');
$this->db->where('library_dropdown_data.id', $value->author);
$query = $this->db->get();
$val = $query->row();

if($val){
    echo "<td>".$val->name."</td>";
}else{
    echo "<td>NA</td>";
}



$this->db->select()->from('books_list');
$this->db->where('book_id', $value->id);
$this->db->where('lost',0);
$query = $this->db->get();
$valcount = $query->num_rows();


echo "<td>".$valcount."</td>";



$this->db->select('id')->from('books_list');
$this->db->where('lost',0);
$this->db->where('book_id', $value->id);
$check2 = $this->db->get();
$check2 = $check2->result_array();
$arr2 = array_map (function($value){
    return $value['id'];
} , $check2);

if(count($arr2)> 0){
$this->db->select()->from('book_issues');
$this->db->where_in('book_id', $arr2);
$this->db->where('is_returned',0);
$query = $this->db->get();
$val = $query->num_rows();
}else{
$val = 0;

}

$val1 = $valcount -$val;



echo "<td>".$val1."</td>";
echo "<td>".$val."</td>";

if($value->perunitcost){
    echo "<td>".$currency_symbol . amountFormat($value->perunitcost)."</td>";

}else{
    echo "<td></td>";

}




echo "<td>".$value->publishing_year."</td>";
echo "<td>".$this->customlib->dateformat($value->created_at)."</td>";
$xf = '<td><span>'.$viewbtn. ' ' .$editbtn . ' ' . $deletebtn.'</span>';
   if($value->writeoff == 1){
    $xf .=     '<span><a data-id="'. $value->id.'" class="btn btn-danger btn-xs removewriteoff" data-toggle="tooltip" title="" data-original-title="Remove Book Write Off">Remove Write Off</a></span>';
   }else{
    $xf .=  '<span><a data-id="'. $value->id.'" class="btn btn-danger btn-xs writeoff" data-toggle="tooltip" title="" data-original-title="Book Write Off">Book Write Off</a></span></td>';

   }
   echo $xf;
echo "</tr>";
}


?>



                                </tbody>
                                 </form>
                            </table><!-- /.table -->
                            <div style="display: flex;justify-content: space-between;align-items: flex-end;"> <span><b>Total Books :</b> <?= $countt ?> <?php if($countt > 0){ ?>  / <b> Showing Records From</b> <?= $val2 - 14 ?><b> To </b>  <?php if($countt > $val2){echo  $val2 ; }else{echo  $countt; } } ?> </span>  
                            
                            
                             <span><b>Total Accession Numbers</b> :<span style="color:green" class="totolbookcopies"><?= $totalcopies ?></span> </span>
                            
                            <div class="model-pager btn-group btn-group-sm" ><a class="btn btn-default <?php if($val1z < 0){ echo "disabled" ;}  ?>" href="<?php echo base_url() ?>admin/book/getallpagination/<?= $val1z ?>" title="Prev"><i class="fa fa-backward no-margin"></i></a><a class="btn btn-default
               <?php
                if($countt < $val2 && ($countt < intval($val2) - 16)){

           echo "disabled";
                }elseif($countt < (intval($val2))){
                    echo "disabled";
                }else{

                }
               
               ?>
               " href="<?php echo base_url() ?>admin/book/getallpagination/<?= $val2 ?>" title="Next"><i class="fa fa-forward no-margin"></i></a></div> </div> 
                            </form>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <!-- general form elements disabled -->
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->




<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">All Accession No. List</h4>
        </div>
        <div class="modal-body">
            <span id="forappend">
             </span> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>






<div class="modal fade" id="writeoffmodal" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Write Off</h4>
        </div>
        <div class="modal-body">
            <form id="writeoffsubmit" action="#" >
            <div class="form-group">
                 <label>Write Off Year</label>
                 <input id="writeoffyear" name="writeoffyear" type="number" min="1900" max="2099" step="1" value="<?= date('Y') ?>"  class="form-control" required>
                 <input id="writeoffid" name="writeoffid" type="hidden" value="" class="form-control" required>
                    <span class="text-danger"></span>
                            </div>
          
             <div class="form-group">
                                           
                                                <button type="submit" name="writeoffsubmit" value="writeoffsubmit" id="" class="btn btn-primary btn-sm pull-right checkbox-toggle" autocomplete="off">
                                                    Submit                                                </button>
                                            </div>
                                 </form>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>




<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
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

    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });   
</script>

<script>
$(document).ready(function() {
    emptyDatatable('book-list','data');
});

    ( function ( $ ) {
    'use strict';
    $(window).load(function() {
        initDatatable('book-list','admin/book/getbooklist',[],[],15,
            [
                { "bSortable": false, "aTargets": [ -3 ] ,'sClass': 'dt-body-right'}
                
            ]);
    });
    } ( jQuery ) )
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    $(".bulkdel").on('click', function (e) {   
       if($(".ids:checked").length == 0){
        alert('Kindly check the checkboxes first');
        return false;
       }else{
        if (confirm('Are you sure you want to delete these?')) {
   var $this = $(this);
        $.ajax({
            url: '<?php echo site_url("admin/book/bulk_dlete") ?>',
            type: 'POST',
            data: $('.ids:checked').serialize(),
            dataType: 'json',
            success: function (data) {
                alert('Data Deleted Successfully');
                location.reload();
            }
        });
    }
       }
    });
</script>
<script>
$(".checkkdel").on('change', function (e) {   
  if($(this).prop('checked') == true){
    $('.ids').prop('checked',true);   
  } else {
    $('.ids').prop('checked',false);   
  }  
});
</script>
<script>
$(document).on('click', '.viewbooks', function () {
    $masterid = $(this).attr('data-id');
    $.ajax({
    url: '<?php echo site_url("admin/book/viewbooks") ?>',
    type: 'POST',
    data:  {'masterid': $masterid},
    success: function (response) {
    $("#forappend").empty();
    $("#forappend").append(response);
    $('#myModal').modal('show');
    }
    });
});
</script>
<!-- <script>
$(document).on('click', '#search_filter', function () {
    var fd = $('form#class_search_form').serialize() ;    
    $.ajax({
    url: '<?php echo site_url("admin/book/getbooklist") ?>',
    type: 'POST',
    data:  fd,
    success: function (response) {
    alert(response);
    }
    });
});
</script>
 -->
 <script>
$(document).on('click', '.bulkdelcopy', function () {

    if (confirm('Are you sure you want to delete these?')) {
        return true;
    }else{

        return false;

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
    $(document).on('click', '#created_from_eraser', function () {
      
     $('#created_from').val('');

});
$(document).on('click', '#created_to_eraser', function () {
     $('#created_to').val('');

});
</script>
<script>
 $(document).on('submit', '#writeoffsubmit', function () {
 
      $writeoffid = $('#writeoffyear').val();
      $book_id = $('#writeoffid').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url() ?>" + "admin/book/writeoffbook",
        data: {'book' : $book_id,'writeoffyear' : $writeoffid},
        success: function (response) {
alert(response);
location.reload();
        }
        });
    

});



$(document).on('click', '.writeoff', function () {
    if (confirm('Are you sure ! This book is writeoff')) {

    $book_id = $(this).attr('data-id');
    $('#writeoffid').val($book_id);
    $('#writeoffmodal').modal('show');
    }

});

</script>
<script>

$(document).on('click', '.removewriteoff', function () {
    if (confirm('Are you sure ! This book was not writeoffed')) {
 $book = $(this).attr('data-id');

 $.ajax({
   type: "POST",
   url: "<?= base_url() ?>" + "admin/book/removewriteoffbook",
   data: {'book' : $book},
   success: function (response) {
alert(response);
location.reload();
   }
   });

    }
});

</script>