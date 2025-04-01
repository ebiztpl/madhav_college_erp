<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->

    <style>
/* original idea http://www.bootply.com/phf8mnMtpe */

.tree {
    min-height:20px;
 /*   padding:19px;
    margin-bottom:20px; */
    background-color:#fbfbfb;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
 /*  border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)  */
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:30px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}
</style>
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
                ?>
                <div class="col-md-4" style="display:none">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_expense_head'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" action="<?php echo site_url('admin/expensehead/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">                            
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>



                              
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?> Category</label> <small class="req">*</small>

                                            <select autofocus="" id="exp_head_id" name="exp_head_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                           
                                                    foreach ($categorylistt as $exphead) { ?>
                                                    <option value="<?php echo $exphead['id'] ?>" <?php
                                                    if (set_value('exp_head_id') == $exphead['id']) {
                                                                echo "selected =selected";
                                                    } ?>><?php echo $exphead['title'] ?></option>

                                                <?php
                                          
                                                    }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>
                                        </div>




                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="expensehead" name="expensehead" placeholder="" type="text" class="form-control"  value="<?php echo set_value('expensehead'); ?>" />
                                    <span class="text-danger"><?php echo form_error('expensehead'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>            
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('expense_head', 'can_add')) {
                echo "12";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_head_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">



                        <?php
                        $Count = 0 ;
foreach ($categorylistt as $inchead) { $Count++ ;

    $this->db->where('head_category',$inchead['id']);
    $result = $this->db->get('expense_head')->num_rows();



        ?>

 <!-- <div id="accordion" style=" display: flex;  gap: 10px;
    flex-direction: column;">
  <div class="card" style="background-color: #fafafa;">
    <div class="card-header" id="<?php echo $inchead['title'] ?>">
      <h5 class="mb-0" style=" margin-top: 2px;  margin-bottom: 2px;">
        <button class="btn btn-link" data-toggle="collapse" data-target="#<?php echo preg_replace('/[\/\,&%#\s+\$]/', '', $inchead['title']).$inchead['id'] ?>" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;text-align:left">
        <?php echo $inchead['title'] ?> (<?= $result ?>)
        </button>
      </h5>
    </div>


    <div id="<?php echo preg_replace('/[\/\,&%#\s+\$]/', '', $inchead['title']).$inchead['id'] ?>" class="collapse " aria-labelledby="<?php echo $inchead['title'] ?>" data-parent="#accordion">
    <div class="card-body" style="    padding: 10px;
    background-color: #dddddd;">

                            <div class="download_label"><?php echo $this->lang->line('expense_head_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($categorylist)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($categorylist as $category) {


                                            if($inchead['id'] == $category['head_category']){

                                            ?>
                                            <tr>                                               
                                                <td class="mailbox-name"><?php echo $category['exp_category'] ?></td>
                                                <td class="mailbox-name"><?php echo $category['description']; ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('expense', 'can_edit')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/expensehead/edit/<?php echo $category['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('expense', 'can_delete')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/expensehead/delete/<?php echo $category['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            }}
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                           

                            </div>
                            </div>


</div> -->

<!-- custom code -->
  
<div id="collapseDVR3" class="panel-collapse collapse in">
<div class="tree ">
<ul>
<li> <span><i class="fa fa-folder-open"></i> <?php echo $inchead['title'] ?></span> 
<?php
  $sql   = "select * from `income_expense_subgroups` where `relation_id` = ".$inchead['id']." AND  `deleted_at` is null AND  `group_id` = 0";
  $query = $this->account->query($sql);
  $droper1 =  $query->result();
?>
<?php if(count($droper1) > 0){ ?>
<ul >
<?php foreach($droper1 as $drop){?>

    <li style="display:none">
    <span><i class="fa fa-minus-square"></i><?= $drop->name ?></span>


<!-- dropper 2 -->
<?php
  $sql   = "select * from `income_expense_subgroups` where `relation_id` = ".$drop->id." AND `deleted_at` is null AND `group_id` = 1";
  $query = $this->account->query($sql);
  $droper2 =  $query->result();
?>
<?php if(count($droper2) > 0){ ?>
<ul>
    
<?php foreach($droper2 as $drop2){?>

    <li>
    <span><i class="fa fa-minus-square"></i><?= $drop2->name ?></span>

<!-- dropper 3 -->
<?php
  $sql   = "select * from `income_expense_subgroups` where `relation_id` = ".$drop2->id." AND `deleted_at` is null AND `group_id` = 1";
  $query = $this->account->query($sql);
  $droper3 =  $query->result();
?>
<?php if(count($droper3) > 0){ ?>
<ul>
<?php foreach($droper3 as $drop3){?>

    <li>
    <span><i class="fa fa-minus-square"></i><?= $drop3->name ?></span>
<!-- dropper 4 -->
<?php
  $sql   = "select * from `income_expense_subgroups` where `relation_id` = ".$drop3->id." AND `deleted_at` is null AND `group_id` = 1";
  $query = $this->account->query($sql);
  $droper4 =  $query->result();
?>
<?php if(count($droper4) > 0){ ?>
<ul>
<?php foreach($droper4 as $drop4){?>

    <li>
    <span><?= $drop4->name ?></span>
  </li>


  <?php } ?>
</ul>
  <?php } ?>
<!-- dropper 4 -->
  </li>
  <?php } ?>
</ul>
<?php } ?>
<!-- dropper 3 -->

  </li>
<!-- dropper 2 -->
</li>
  <?php } ?>
</ul>
<?php } ?>
<?php } ?>
</ul>
<?php } ?>



</li>
</ul>
</div></div>
<!--custom code -->




<?php
}
        ?>





                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- 
<script type="text/javascript">
    $(document).ready(function() {
      initDatatable('expense-head-list','admin/expensehead/ajaxSearch',[],[],100);
  });
</script> -->
<script> $(function () {
      $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
      $('.tree li.parent_li > span').on('click', function (e) {
          var children = $(this).parent('li.parent_li').find(' > ul > li');
          if (children.is(":visible")) {
              children.hide('fast');
              $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus-square').removeClass('fa-minus-square');
          } else {
              children.show('fast');
              $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus-square').removeClass('fa-plus-square');
          }
          e.stopPropagation();
      });
  });

  </script>