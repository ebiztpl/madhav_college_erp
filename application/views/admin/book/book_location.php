<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }
    
    tr.odd { background-color: #ededed !important; }
    /* tr.odd td { padding-top: 40px;} */
    tr.odd:nth-child(2) { padding-top: 40px !important;}


.accordion {
  width: 100%;
  margin: 20px auto;
}
.accordion h1, h2, h3, h4 {
  cursor: pointer;
}
/* .accordion h2, h3, h4 {
  font-family: "News Cycle";
} */
.accordion h1 {
  padding: 15px 15px;
  background-color: #424242;
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  font-weight: 500;
  color: #ffffff;
}
.accordion h1:hover {
  color: #4afcdc;
}
.accordion h1:first-child {
  border-radius: 10px 10px 0 0;
}
.accordion h1:last-of-type {
  border-radius: 0 0 10px 10px;
}
.accordion h1:not(:last-of-type) {
  border-bottom: 1px dotted #1abc9c;
}
.accordion div, .accordion p {
  display: none;
}
.accordion h2 {
  padding: 5px 25px;
  background-color: #1abc9c;
  font-size: 14px;
  color: #fff;
}
.accordion h2:hover {
  background-color: #09ab8b;
}
.accordion h3 {
  padding: 5px 30px;
  background-color: #b94152;
  font-size: 16px;
  color: #fff; 
}
.accordion h3:hover {
  background-color: #a93142;
}
.accordion h4 {
  padding: 5px 35px;
  background-color: #ffc25a;
  font-size: 16px;
  color: #000; 
}
.accordion h4:hover {
  background-color: #e0b040;
}
.accordion p {
  padding: 15px 35px;
  background-color: #ddd;
  font-family: "Georgia";
  font-size: .8rem;
  color: #333;
  line-height: 1.3rem;
}
.accordion .opened-for-codepen {
  display: block;
}


.h1, .h2, .h3, h1, h2, h3 ,h4{
    margin-top: 0px;
    margin-bottom: 0px;
}
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->


  



    <section class="content">
   
                            
   


                                <!-- Income -->
                                <div class="row">
                        

                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Location</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/book/book_location"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>                     
    
    <input type="hidden" name="id" value="">
    

                                <?php echo $this->customlib->getCSRF(); ?>
                              
                               
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Location<small class="req"> *</small></label>
                                    <input id="location" name="location" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('location'); ?></span>
                                
                                </div>
                           

                             
                           


                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?><span style="color:red"> (Can't be greater than 250 characters)</span></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="" maxlength="250"></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn">Save</button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
           
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Locations List</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        
                        









  <!-- <link href='https://fonts.googleapis.com/css?family=News+Cycle:400,700' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css"> -->
  

<aside class="accordion" >

<?php $i=0 ;foreach($book_location as $dat ){ $i++ ; ?>
	<?php $count = 0; $this->db->select()->from('book_location');
             $this->db->order_by('book_location.id', 'desc');
             $this->db->where('book_location.refrence_location',$dat->id);
             $query = $this->db->get();
             $booksub_location = $query->result();
           ?>
	<h1 style="display: flex;justify-content: space-between;"><?=  $i.') '.$dat->location ?> (<?=count($booksub_location)?>)<?php if($dat->description){ ?> <small style="color:white   ; width: 40%;">(<?= $dat->description ?>) <?php } ?></small>

	<span>
       <a id="<?= $dat->id ?>" class="btn btn-default btn-xs addsublocation" data-toggle="tooltip" title="" data-original-title="Add Sub-location"><i class="fa fa-plus"></i></a>
        <a href="<?php echo base_url() ?>admin/book/booklocationedit/<?= $dat->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
       <a href="<?php echo base_url() ?>admin/book/booklocationdelete/<?= $dat->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                   </span>
</h1>
	<div class="">




	<?php foreach($booksub_location as $sub1 ){  $count++;?>
    <?php $this->db->select()->from('book_location');
           $this->db->order_by('book_location.id', 'desc');
           $this->db->where('book_location.refrence_location',$sub1->id);
           $query = $this->db->get();
           $booksubsub_location = $query->result();
            ?>
		<h2 style="display: flex;justify-content: space-between;"><?= $count.') '.$sub1->location ?>(<?=count($booksubsub_location)?>)
		<span>
       <a id="<?= $sub1->id ?>" class="btn btn-default btn-xs addsublocation" data-toggle="tooltip" title="" data-original-title="Add Sub-location"><i class="fa fa-plus"></i></a>
        <a href="<?php echo base_url() ?>admin/book/booklocationedit/<?= $sub1->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
       <a href="<?php echo base_url() ?>admin/book/booklocationdelete/<?= $sub1->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                   </span>
	
	
	</h2>
		<div class="opened-for-codepen">
		

 	<?php $countt= 0; foreach($booksubsub_location as $sub2 ){   $countt++;?>
    <?php $this->db->select()->from('book_location');
            $this->db->order_by('book_location.id', 'desc');
            $this->db->where('book_location.refrence_location',$sub2->id);
            $query = $this->db->get();
            $booksubsubsub_location = $query->result();
            ?>
			<h3 style="display: flex;justify-content: space-between;"><?= $countt.') '.$sub2->location ?>(<?=count($booksubsubsub_location)?>)
			<span>
       <a id="<?= $sub2->id ?>" class="btn btn-default btn-xs addsublocation" data-toggle="tooltip" title="" data-original-title="Add Sub-location"><i class="fa fa-plus"></i></a>
        <a href="<?php echo base_url() ?>admin/book/booklocationedit/<?= $sub2->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
       <a href="<?php echo base_url() ?>admin/book/booklocationdelete/<?= $sub2->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                   </span>
		</h3>

			<div>

		

  

                <?php $counttt= 0; foreach($booksubsubsub_location as $sub3 ){ $counttt++;?>
				<h4 style="display: flex;justify-content: space-between;"><?= $counttt.') '.$sub3->location ?>
				<span>
       <!-- <a id="<?= $sub3->id ?>" class="btn btn-default btn-xs addsublocation" data-toggle="tooltip" title="" data-original-title="Add Sub-location"><i class="fa fa-plus"></i></a> -->
        <a href="<?php echo base_url() ?>admin/book/booklocationedit/<?= $sub3->id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
       <a href="<?php echo base_url() ?>admin/book/booklocationdelete/<?= $sub3->id ?>" class="btn btn-default btn-xs check_confirm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                   </span>
			
			</h4>
				<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,  quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> -->
				<?php }?>	
			</div>

			<?php }?>	

		</div>
			
	<?php }?>	
	
	</div>
<?php } ?>
</aside>
  
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> -->










                        
                        <!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>
                         
                             <!-- Income -->
                       
                             
                            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->



<script>
    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>
<script>
 $(document).on('click', '.check_confirm', function () {

    if (confirm('Are you sure you want to delete this?')) {
             return true;
    }else{
        return false;
    }

});
</script>
<script>
 $(document).on('click', '.addsublocation', function () {

        $id =  $(this).attr('id');
       var   AjaxURL =   "<?php echo base_url() ?>admin/book/book_sublocation";
    let person = prompt("Please enter Subcategory name");
    if (person) {
             $.ajax({
             type: "POST",
             url: AjaxURL,
             data: {id: $id ,data: person},
             success: function(result) {
                 alert(result);
                 location.reload();
             }
         });
    }else{
       return false;
    }

});
</script>




<script>
    var headers = ["H1","H2","H3","H4","H5","H6"];

$(".accordion").click(function(e) {
  var target = e.target,
      name = target.nodeName.toUpperCase();
  
  if($.inArray(name,headers) > -1) {
    var subItem = $(target).next();
    
    //slideUp all elements (except target) at current depth or greater
    var depth = $(subItem).parents().length;
    var allAtDepth = $(".accordion p, .accordion div").filter(function() {
      if($(this).parents().length >= depth && this !== subItem.get(0)) {
        return true; 
      }
    });
    $(allAtDepth).slideUp("fast");
    
    //slideToggle target content and adjust bottom border if necessary
    subItem.slideToggle("fast",function() {
        $(".accordion :visible:last").css("border-radius","0 0 10px 10px");
    });
    $(target).css({"border-bottom-right-radius":"0", "border-bottom-left-radius":"0"});
  }
});
</script>