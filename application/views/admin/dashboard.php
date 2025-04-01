<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<style type="text/css">
    .borderwhite{border-top-color: #fff !important;}
    .box-header>.box-tools {display: none;}
    .sidebar-collapse #barChart{height: 100% !important;}
    .sidebar-collapse #lineChart{height: 100% !important;}
    /*.fc-day-grid-container{overflow: visible !important;}*/
    .tooltip-inner {max-width: 135px;}
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="">
            
            <?php if (ENVIRONMENT != 'production') { ?>
                <div class="alert alert-danger">
                    Environment set to <?php echo ENVIRONMENT ;?>! <br>
                    Don't forget to set back to production in the main index.php file after finishing your tests or <?php echo ENVIRONMENT ;?>. <br>
                    Please be aware that in <?php echo ENVIRONMENT ;?> mode you may see some errors and deprecation warnings, for this reason, it's always recommended to set the environment to "production" if you are not actually developing some features/modules or trying to test some code.
                </div>
            <?php } ?>
                
            <?php if ($mysqlVersion && $sqlMode && strpos($sqlMode->mode, 'ONLY_FULL_GROUP_BY') !== false) {?>
                <div class="alert alert-danger">
                    Smart School may not work properly because ONLY_FULL_GROUP_BY is enabled, consult with your hosting provider to disable ONLY_FULL_GROUP_BY in sql_mode configuration.
                </div>
            <?php }?>

            <?php
$show    = false;
$role    = $this->customlib->getStaffRole();
$role_id = json_decode($role)->id;
foreach ($notifications as $notice_key => $notice_value) {

    if ($role_id == 7) {
        $show = true;
    } elseif (date($this->customlib->getSchoolDateFormat()) >= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($notice_value->publish_date))) {
        $show = true;
    }
    if ($show) {
        ?>
                    <div class="dashalert alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="alertclose close close_notice" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button>
                        <a href="<?php echo site_url('admin/notification') ?>"><?php echo $notice_value->title; ?></a>
                    </div>
                    <?php
}
}
?>

        </div>
        <div class="row">
            <?php
if ($this->module_lib->hasActive('fees_collection')) {
    if ($this->rbac->hasPrivilege('fees_awaiting_payment_widegts', 'can_view')) {
        ?>
                    <div class="<?php echo $std_graphclass; ?>">
                        <div class="topprograssstart">
                            <p class="text-uppercase mt5 clearfix"><i class="fa fa-money ftlayer"></i><?php echo $this->lang->line('fees_awaiting_payment'); ?><span class="pull-right"><?php echo $total_paid; ?>/<?php echo $total_fees ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $fessprogressbar; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                    <?php
}
}
?>

            <?php
if ($this->module_lib->hasActive('front_office')) {
    if ($this->rbac->hasPrivilege('conveted_leads_widegts', 'can_view')) {
        ?>
                    <div class="<?php echo $std_graphclass; ?>">
                        <div class="topprograssstart">
                            <p class="text-uppercase mt5 clearfix"><i class="fa fa-ioxhost ftlayer"></i> <?php echo $this->lang->line('converted_leads'); ?><span class="pull-right"><?php echo $total_complete + 0; ?>/<?php echo $total_enquiry; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-red" style="width: <?php echo $fenquiryprogressbar; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                    <?php
}
}
if ($this->rbac->hasPrivilege('staff_present_today_widegts', 'can_view')) {
    ?>
                <div class="<?php echo $std_graphclass; ?>">
                    <div class="topprograssstart">
                        <p class="text-uppercase mt5 clearfix"><i class="fa fa-calendar-check-o ftlayer"></i><?php echo $this->lang->line('staff_present_today'); ?><span class="pull-right"><?php echo $Staffattendence_data + 0; ?>/<?php echo $getTotalStaff_data; ?></span>
                        </p>
                        <div class="progress-group">
                            <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo $percentTotalStaff_data; ?>%"></div>
                            </div>
                        </div>
                    </div><!--./topprograssstart-->
                </div><!--./col-md-3-->
                <?php
}
if ($this->module_lib->hasActive('student_attendance') && $sch_setting->attendence_type == 0) {
    if ($this->rbac->hasPrivilege('student_present_today_widegts', 'can_view')) {
        ?>
                    <div class="<?php echo $std_graphclass; ?>">
                        <div class="topprograssstart">
                            <p class="text-uppercase mt5 clearfix"><i class="fa fa-calendar-check-o ftlayer"></i><?php echo $this->lang->line('student_present_today'); ?><span class="pull-right"> <?php echo 0 + $attendence_data['total_half_day'] + $attendence_data['total_late'] + $attendence_data['total_present']; ?>/<?php echo $total_students; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php if ($total_students > 0) {echo (0 + $attendence_data['total_half_day'] + $attendence_data['total_late'] + $attendence_data['total_present'] / $total_students * 100);}?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                <?php }
}
?>
        </div><!--./row-->
        <div class="row">
            <?php
$bar_chart = true;

if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense'))) {
    if ($this->rbac->hasPrivilege('fees_collection_and_expense_monthly_chart', 'can_view')) {

        $div_rol  = 3;
        $userdata = $this->customlib->getUserData();
        ?>
                    <div class="col-lg-7 col-md-7 col-sm-12 col60">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('fees_collection_expenses_for'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . " " . date('Y');

        ?></h3>
                                
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <canvas id="barChart" height="95"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-7-->
                <?php }
}
?>
            <?php
if ($this->module_lib->hasActive('income')) {
    if ($this->rbac->hasPrivilege('income_donut_graph', 'can_view')) {
        ?>
                    <div class="col-lg-5 col-md-5 col-sm-12 col40">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line('income') . " - " . $this->lang->line(strtolower(date('F'))) . " " . date('Y');  ?></h3></div>
                            <div class="box-body">
                                <div class="chart-responsive">
                                    <canvas id="doughnut-chart" class="" height="148"></canvas>
                                </div>
                            </div>
                        </div><!--./col-md-6-->
                    </div><!--./col-lg-5-->
    <?php
}
}
?>
        </div><!--./row-->
        <div class="row">
            <?php
$line_chart = true;
if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense'))) {
    if ($this->rbac->hasPrivilege('fees_collection_and_expense_yearly_chart', 'can_view')) {
        $div_rol = 3;
        ?>
                    <div class="col-lg-7 col-md-7 col-sm-12 col60">
                        <div class="box box-info borderwhite">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('fees_collection_expenses_for_session'); ?> <?php echo $this->setting_model->getCurrentSessionName(); ?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="chart">
                                    <canvas id="lineChart" height="95"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-7-->
                    <?php
}
}
if ($this->module_lib->hasActive('expense')) {
    ?>
    <?php if ($this->rbac->hasPrivilege('expense_donut_graph', 'can_view')) {
        ?>
                    <div class="col-lg-5 col-md-5 col-sm-12 col40">
                        <div class="box box-primary borderwhite">
                            <div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line('expense') . " - " . $this->lang->line(strtolower(date('F'))) . " " . date('Y');  ?></h3>
                            </div><!--./info-box-->
                            <div class="box-body">
                                <div class="chart-responsive">
                                    <canvas id="doughnut-chart1" class="" height="148"></canvas>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-lg-5-->
    <?php }
}
?>
        </div><!--./row-->
        <div class="row">

<?php
if ($this->module_lib->hasActive('fees_collection')) {
    if ($this->rbac->hasPrivilege('fees_overview_widegts', 'can_view')) {
        ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"><?php echo $this->lang->line('fees_overview'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_unpaid']; ?> <?php echo $this->lang->line('unpaid'); ?><span class="pull-right"><?php echo round($fees_overview['unpaid_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $fees_overview['unpaid_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_partial']; ?> <?php echo $this->lang->line('partial'); ?><span class="pull-right"><?php echo round($fees_overview['partial_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $fees_overview['partial_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $fees_overview['total_paid']; ?> <?php echo $this->lang->line('paid'); ?><span class="pull-right"><?php echo round($fees_overview['paid_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-aqua" style="width: <?php echo $fees_overview['paid_progress']; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
        <?php
}
}
if ($this->module_lib->hasActive('front_office')) {
    if ($this->rbac->hasPrivilege('enquiry_overview_widegts', 'can_view')) {
        ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"> <?php echo $this->lang->line('enquiry_overview'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $enquiry_overview['active']; ?> <?php echo $this->lang->line('active') ?><span class="pull-right"><?php echo round($enquiry_overview['active_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-red" style="width: <?php echo $enquiry_overview['active_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $enquiry_overview['won']; ?> <?php echo $this->lang->line('won') ?><span class="pull-right"><?php echo round($enquiry_overview['won_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo $enquiry_overview['won_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $enquiry_overview['passive']; ?> <?php echo $this->lang->line('passive') ?><span class="pull-right"><?php echo round($enquiry_overview['passive_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo $enquiry_overview['passive_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $enquiry_overview['lost']; ?> <?php echo $this->lang->line('lost') ?><span class="pull-right"><?php echo round($enquiry_overview['lost_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo $enquiry_overview['lost_progress']; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $enquiry_overview['dead']; ?> <?php echo $this->lang->line('dead') ?><span class="pull-right"><?php echo round($enquiry_overview['dead_progress'], 2); ?>%</span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo $enquiry_overview['dead_progress']; ?>%"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
        <?php
}
}

if ($this->module_lib->hasActive('student_attendance')) {
    if ($this->rbac->hasPrivilege('today_attendance_widegts', 'can_view')) {
        ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"> <?php echo $this->lang->line('student_today_attendance'); ?></h5>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_present']; ?> <?php echo $this->lang->line('present'); ?><span class="pull-right"><?php echo $attendence_data['present']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['present']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_late']; ?> <?php echo $this->lang->line('late') ?><span class="pull-right"><?php echo $attendence_data['late']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['late']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_absent']; ?> <?php echo $this->lang->line('absent'); ?><span class="pull-right"><?php echo $attendence_data['absent']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['absent']; ?>"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $attendence_data['total_half_day']; ?> <?php echo $this->lang->line('half_day'); ?><span class="pull-right"><?php echo $attendence_data['half_day']; ?></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar" style="width: <?php echo $attendence_data['half_day']; ?>"></div>
                                </div>
                            </div>
                        </div><!--./topprograssstart-->
                    </div><!--./col-md-3-->
                    <?php
}
}


if ($this->module_lib->hasActive('library')) {
    if ($this->rbac->hasPrivilege('book_overview_widegts', 'can_view')) {
        ?>
               
               <style type="text/css">
        .library .boxx {
          margin-bottom: 20px;
          display: flex;
          padding: 6px;
          border-radius: 8px;
          gap: 15%;
          align-items: flex-end;
          background-color: #fff;
          box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }
         .library .boxx h3 { margin-bottom: 2px;}
         .library {  padding: 40px 0px;}
    </style>           



<?php if(json_decode($this->customlib->getStaffRole())->name != 'Librarian'){  ?> 
                      <div class="col-md-3 col-sm-6">
                        <div class="topprograssstart">
                            <h5 class="pro-border pb10"> <?php echo $this->lang->line('library_overview'); ?></h5>




                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['total_master']; ?>  Total Book Masters<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>

                            <?php 

$totalfinee =  (explode("~",$book_overview['totalfine']));


?>


                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['total']; ?>  Total Books<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['availble']; ?> Available Books<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['total_issued']; ?> Total Issued <span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['Total_Members']; ?> Total Members<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['Books_demand']; ?> Books Demand<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $book_overview['overdueforreturn']; ?> Overdue Return<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                            <p class="text-uppercase mt10 clearfix"><?php echo $totalfinee[1]; ?> Total Fine Collected<span class="pull-right"></span>
                            </p>
                            <div class="progress-group">
                                <div class="progress progress-minibar">
                                <div class="progress-bar progress-bar-green" style="width: <?php echo 50; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>

  
            <div class="library">
                 <div class="">
                 <?php if(json_decode($this->customlib->getStaffRole())->name == 'Librarian'){  ?> 


<!-- Start Work Here alok s -->

<style type="text/css">
   .issue-book-box-two .boxx { background-color: #5b3ea1 !important; gap: 5%;    margin-bottom: 1px; }
   .issue-book-box-two .info-box-icon { height: unset; width: unset; 
    line-height: unset; box-shadow: unset; background-color: unset !important;}
   .issue-book-box-two .boxx h3 { margin-bottom: 2px; margin-top: 2px; color: #fff;}
   .issue-book-box-two p { margin: 0 0 2px; color: #fff;font-size: 15.75px;}
   .box-two-issue .boxx { background-color: #a73c86 !important;}
   .issue-book-box-one h3 {     margin-top: 5px !important;
    margin-bottom: 18px;
    text-align: center;}
   .box-two-record .boxx { background-color: #019752 !important;}
   .issue-book-box-one .boxx { margin-bottom: 10px; box-shadow: unset;    flex-direction: column;
    align-content: center;    padding: 6px 5px;
    justify-content: center;    align-items: center; display: flex;}
   .box-two-member .boxx { background-color: #387e87 !important;}
   .welcome{ margin-top: 1px !important;}
   h3.main-head {text-decoration: underline;}
   .button-box .btn { font-size: 16px; font-weight: 500; font-family: system-ui;}
   .button-box { display: flex; gap: 10px; justify-content: center;
    align-items: center; align-content: center; margin-bottom: 15px;}
   .inner-box-one { display: flex; flex-direction: column-reverse;}
   .inner-box-one h3 { font-size: 20px;}
   .button-box button.btn.btn-primary { background-color: #4699c9;
    border: #3c8dbc 1px solid;}
   .button-box button.btn.btn-secondary { background-color: #5b3ea1;
    border: #4c328d 1px solid; color: #ffffff;}
   .button-box .btn-info { background-color: #a73c86; border-color: #953076;}
   .button-box .btn-light { background-color: #5f9b1b; border-color: #4f8512; color: #ffffff;}
   .button-box button.btn.btn-dark { background-color: #259bab; border-color: #1b8493; color: #fff;}
   .box-two-record .col-md-3 {width: 16.66%;}
   .issue-book-box-one {margin-bottom: 20px;}
   .box-two-issue .col-md-3 { width: 20%; }
   .issue-book-box-one i.fa.fa-book {border: 2px solid; border-radius: 360px; padding: 5px 7px;
    font-size: 25px; }
</style>

<h2 class="text-center welcome">Welcome to  <?php echo $this->setting_model->getCurrentSchoolName(); ?> ERP</h2>

<div class="button-box" style="padding: 10px;">
    <a href="<?php echo base_url(); ?>report/studentbookissuereport" class="btn btn-success">Issue</a>
    <a href="<?php echo base_url(); ?>admin/book/issue_returnreport" class="btn btn-warning">Return</a>
    <!-- <button type="button" class="btn btn-danger">Success</button> -->
    <a href="<?php echo base_url(); ?>admin/member" class="btn btn-primary">Member History</a>
    <a href="<?php echo base_url(); ?>admin/book/getall" class="btn btn-secondary" style="background-color: #5b3ea1;border: #4c328d 1px solid;color: #ffffff;">Book List</a>
    <!-- <a href="<?php echo base_url(); ?>report/library" class="btn btn-info">Filter Report</a> -->
    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#myModal">Total Fine Collected</button>
    <a href="<?php echo base_url(); ?>admin/book/searchbookduplicacy" class="btn btn-dark" style="background-color: #259bab;border-color: #1b8493;color: #fff;">Lost Books</a>
</div>



<?php 

$totalfinee =  (explode("~",$book_overview['totalfine']));


?>


<div class="modal fade modal-fullscreen" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Total Fine Collected : <span style="color:green"> <?php echo $totalfinee[1]; ?> </span></h4>
  </div>
      <div class="modal-body" id="">
      <?php echo $totalfinee[0]; ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>

</div>



<style>
    .modal-fullscreen {
  padding: 0 !important;
}
.modal-fullscreen .modal-dialog {
  width: 100%;
  height: -webkit-fill-available;
  overflow-y: initial !important;
  margin: 0;
  padding: 0;
}
.modal-fullscreen .modal-content {
  height: -webkit-fill-available;
  min-height: -webkit-fill-available;
  border: 0 none;
  border-radius: 0;
  box-shadow: none;
}
.modal-fullscreen .modal-body{
    height: 70vh;
    overflow-y: auto;
}
a {
    display: unset;
}
.topprograssstart{
cursor:pointer
}
</style>
<div class="modal fade modal-fullscreen" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Respective Related Data</h4>
  </div>
      <div class="modal-body" id="apeendhere">
       
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>

</div>







<div class="issue-book-box-one box-two-record" style="padding: 5px 5px;">
    <h3 class="main-head">Books Record</h3>
    <div class="issue-book-box-two" style="padding: 0px 4px 0px 0px;">
    <!-- <a href="<?php echo base_url(); ?>admin/book/getall"> -->
        <div class="row" style="margin-left:0px;margin-right:0px:">
         
        <a href="<?php echo base_url(); ?>admin/book/getall">
         <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $book_overview['total']; ?> </h3>
                <p>Total Books</p>
            </div>
          </div>
         </div>
</a>

<a href="<?php echo base_url(); ?>admin/book/getall">
         <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $book_overview['availble']; ?> </h3>
                <p>Available Books</p>
            </div>
          </div>
         </div>
         </a>
         <?php if($newbook_overview['englishbook'] > 0){ ?> 
         <a href="<?php echo base_url(); ?>admin/book/language_filters/English">
         <?php   } ;?> 
         <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['englishbook']; ?> </h3>
                <p>English</p>
            </div>
          </div>
         </div>
         <?php if($newbook_overview['englishbook'] > 0){ ?> 
</a>
<?php   } ;?> 

<?php if($newbook_overview['hindibook'] > 0){ ?> 
<a href="<?php echo base_url(); ?>admin/book/language_filters/Hindi">
<?php   } ;?> 
         <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['hindibook']; ?> </h3>
                <p>Hindi</p>
            </div>
          </div>
         </div>
         <?php if($newbook_overview['hindibook'] > 0){ ?> 
</a>
<?php   } ;?> 

         <a href="<?php echo base_url(); ?>admin/book/book_location">
        <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['locationcount']; ?> </h3>
                <p>Location</p>
            </div>
          </div>
         </div>
</a>

<a href="<?php echo base_url(); ?>admin/book/bookcategory">
         <div class="col-md-3 col-sm-6">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['bookcategoriescoint']; ?> </h3>
                <p>Books Categories</p>
            </div>
          </div>
         </div>
              
    </div>
    </a>
<!-- </a> -->
</div>
</div>







<div class="issue-book-box-one issue-books-record" style="padding: 5px 5px;">
    <h3 class="main-head">Issue Books Record</h3>
    <div class="issue-book-box-two" style="padding: 0px 4px 0px 0px;">
        <div class="row" style="margin-left:0px;margin-right:0px:">
         
         <div class="col-md-3 col-sm-6 full_modal" data-id="issue_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['issue_today']; ?> </h3>
                <p>Book Issue (Today)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="last7issue_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['last7issue_today']; ?> </h3>
                <p>Book Issue (1-7 days)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="month_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one ">
            <h3><?php echo $newbook_overview['month_today']; ?> </h3>
                <p>Book Issue Month (<?= date('M-Y') ?>)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="this_yaeris">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['this_yaeris']; ?> </h3>
                <p>Total Book Issue (<?= date('Y') ?>)</p>
            </div>
          </div>
         </div>

        
              
    </div>
</div>
</div>

<div class="issue-book-box-one box-two-issue" style="padding: 5px 5px;">
    <h3 class="main-head">Books Return Record</h3>
    <div class="issue-book-box-two" style="padding: 0px 4px 0px 0px;">
        <div class="row" style="margin-left:0px;margin-right:0px:">
         
         <div class="col-md-3 col-sm-6 full_modal" data-id="returnissue_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['returnissue_today']; ?></h3>
                <p>Book Return (Today)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="returnlast7issue_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['returnlast7issue_today']; ?> </h3>
                <p>Book Return (1-7 days)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="returnmonth_today">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['returnmonth_today']; ?> </h3>
                <p>Book Return  (<?= date('M-Y') ?>)</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal" data-id="returnthis_yaeris">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['returnthis_yaeris']; ?>  </h3>
                <p>Total Book Return  (<?= date('Y') ?>)</p>
            </div>
          </div>
         </div>

          <div class="col-md-3 col-sm-6 full_modal" data-id="overduemonth">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-book"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['overduemonth']; ?> </h3>
                <p>Overdue Return Month</p>
            </div>
          </div>
         </div>
              
    </div>
</div>
</div>


<div class="issue-book-box-one box-two-member" style="padding: 5px 5px;">
    <h3 class="main-head">Member Record</h3>
    <div class="issue-book-box-two" style="padding: 0px 4px 0px 0px;">
        <div class="row" style="margin-left:0px;margin-right:0px:">
         
         <div class="col-md-3 col-sm-6 full_modal2" data-id="Total_Members">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-address-card"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $book_overview['Total_Members']; ?></h3>
                <p>Total Members</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal2" data-id="studentmember">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-address-card"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['studentmember']; ?> </h3>
                <p>Students</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal2" data-id="teachingstaff">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-address-card"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['teachingstaff']; ?> </h3>
                <p>Teaching Staff</p>
            </div>
          </div>
         </div>

         <div class="col-md-3 col-sm-6 full_modal2" data-id="gueststaff">
          <div class="boxx topprograssstart">
            <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
            <i class="fa fa-address-card"></i>
            </div>
            <div class="inner-box-one">
            <h3><?php echo $newbook_overview['gueststaff']; ?> </h3>
                <p>Guest</p>
            </div>
          </div>
         </div>
              
    </div>
</div>
</div>

<!--End Work Here alok s-->

                 <!-- <div class="col-md-12 col-sm-12"> -->
                  <div class="row" style="margin-left:0px;margin-right:0px:">
                    


                  <!-- <div class="col-md-3 col-sm-6">
                      <div class=" boxx topprograssstart">
                        <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
                      <i class="fa fa-book"></i>
                        </div>
                      <div>
                         <h3><?php echo $book_overview['total_master']; ?> </h3>
                         <p>Total Book Masters</p>
                      </div>
                      </div>
                 </div>
             

                     <div class="col-md-3 col-sm-6">
                      <div class=" boxx topprograssstart">
                        <div  class="info-box-icon bg-aqua" style="margin-block: auto;">
                      <i class="fa fa-book"></i>
                        </div>
                      <div>
                         <h3><?php echo $book_overview['total']; ?> </h3>
                         <p>Total Books</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-green" style="margin-block: auto;">
                      <i class="fa fa-spinner"></i>
                        </div>                      <div>
                         <h3><?php echo $book_overview['availble']; ?></h3>
                         <p>Availble Books</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
                      <i class="fa fa-podcast"></i>
                        </div>                      <div>
                      <h3><?php echo $book_overview['total_issued']; ?></h3>
                      <p>Total Issued</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-red" style="margin-block: auto;">
                      <i class="fa fa-user-secret"></i>
                        </div>                      <div>
                      <h3><?php echo $book_overview['overdueforreturn']; ?></h3>
                      <p>Overdue Return</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-aqua" style="margin-block: auto;">
                      <i class="fa fa-address-card"></i>
                        </div>                      <div>
                         <h3><?php echo $book_overview['Total_Members']; ?></h3>
                         <p>Total Members</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-yellow" style="margin-block: auto;">
                      <i class="fa fa-tags"></i>
                        </div>                      <div>
                         <h3><?php echo $book_overview['Books_demand']; ?></h3>
                         <p>Books Demand</p>
                      </div>
                      </div>
                     </div>

                     <div class="col-md-3 col-sm-6">
                      <div class="boxx topprograssstart">
                      <div  class="info-box-icon bg-red" style="margin-block: auto;">
                      <i class="fa fa-line-chart"></i>
                        </div>                      <div>
                         <h3><?php echo $book_overview['totalfine']; ?></h3>
                         <p>Total Fine Collected</p>
                      </div>
                      </div>
                     </div> -->

                     <?php } ?>
                  
                  </div> 
                 </div>
                
            </div>
    <!-- </div> -->
    <?php if(json_decode($this->customlib->getStaffRole())->name == 'Librarian'){ } ?>

        <?php
}
}


$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

$div_col    = 12;
$div_rol    = 12;
$bar_chart  = true;
$line_chart = true;
if ($this->rbac->hasPrivilege('staff_role_count_widget', 'can_view')) {
    $div_col = 9;
    $div_rol = 12;
}

$widget_col = array();
if ($this->rbac->hasPrivilege('Monthly fees_collection_widget', 'can_view')) {
    $widget_col[0] = 1;
    $div_rol       = 3;
}

if ($this->rbac->hasPrivilege('monthly_expense_widget', 'can_view')) {
    $widget_col[1] = 2;
    $div_rol       = 3;
}

if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    $widget_col[2] = 3;
    $div_rol       = 3;
}
$div = sizeof($widget_col);
if (!empty($widget_col)) {
    $widget = 12 / $div;
} else {

    $widget = 12;
}
?>

            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 col80">
                    <div class="row">
<?php
if ($this->module_lib->hasActive('fees_collection')) {
    if ($this->rbac->hasPrivilege('Monthly fees_collection_widget', 'can_view')) {
        ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="info-box">
                                        <a href="<?php echo site_url('studentfee') ?>">
                                            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $this->lang->line('monthly_fees_collection'); ?></span>
                                                <span class="info-box-number"><?php if($month_collection){ echo $currency_symbol . amountFormat($month_collection); } ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
    <?php }
}
?>
<?php
if ($this->module_lib->hasActive('expense')) {
    if ($this->rbac->hasPrivilege('monthly_expense_widget', 'can_view')) {
        ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="info-box">
                                        <a href="<?php echo site_url('admin/expense') ?>">
                                            <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $this->lang->line('monthly_expenses'); ?></span>
                                                <span class="info-box-number"><?php if($month_expense){ echo $currency_symbol . amountFormat($month_expense); } ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
    <?php
}
}

if ($this->rbac->hasPrivilege('student_count_widget', 'can_view')) {
    ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="info-box">
                                    <a href="<?php echo site_url('student/search') ?>">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('student'); ?></span>
                                            <span class="info-box-number"><?php echo $total_students; ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
<?php }?>
                    </div>

<?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        $div_rol = 3;
        ?>
                        <div class="box box-primary borderwhite">
                            <div class="box-body">
                                <!-- THE CALENDAR -->
                                <div id="calendar"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /. box -->
                    <?php }}?>
                </div><!--./col-lg-9-->
<?php
if ($this->rbac->hasPrivilege('staff_role_count_widget', 'can_view')) {
    ?>
                    <div class="col-lg-3 col-md-3 col-sm-12 col20">
    <?php foreach ($roles as $key => $value) {
        ?>
                            <div class="info-box">
                                <a href="#">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-user-secret"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $key; ?></span>
                                        <span class="info-box-number"><?php echo $value; ?></span>
                                    </div>
                                </a>
                            </div>
    <?php }?>
                    </div><!--./col-lg-3-->
<?php }?>
            </div><!--./row-->
        </div><!--./row-->
</div>
<div id="newEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("add_new_event"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="addevent_form" method="post" enctype="multipart/form-data" action="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('event_title'); ?></label><small class="req"> *</small>
                                <input class="form-control" name="title" id="input-field">
                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                            </div>    
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('description'); ?></label>
                                <textarea name="description" class="form-control" id="desc-field"></textarea>
                            </div>    
                        </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">        
                         <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('event_from'); ?><small class="req"> *</small></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" autocomplete="off" name="event_from" class="form-control pull-right event_from">
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('event_to'); ?><small class="req"> *</small></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" autocomplete="off" name="event_to" class="form-control pull-right event_to">
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('event_color'); ?></label>
                                <input type="hidden" name="eventcolor" autocomplete="off" id="eventcolor" class="form-control">
                            </div>    
                        </div>
                        <div class="col-md-12">
                           <div class="form-group"> 
                            <?php
$i      = 0;
$colors = '';
foreach ($event_colors as $color) {
    $color_selected_class = 'cpicker-small';
    if ($i == 0) {
        $color_selected_class = 'cpicker-big';
    }
    $colors .= "<div class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
    $i++;
}
echo '<div class="cpicker-wrapper">';
echo $colors;
echo '</div>';
?>
                           </div> 
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="pt15 displayblock overflow-hidden w-100"><?php echo $this->lang->line('event_type'); ?></label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="public" id="public"><?php echo $this->lang->line('public'); ?>
                                </label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="private" checked id="private"><?php echo $this->lang->line('private'); ?>
                                </label>
                                <label class="radio-inline w-xs-45 ml-xs-0">
                                    <input type="radio" name="event_type" value="sameforall" id="public"><?php echo $this->lang->line('all'); ?> <?php echo json_decode($role)->name; ?>
                                </label>
                                <label class="radio-inline w-xs-45">
                                    <input type="radio" name="event_type" value="protected" id="public"><?php echo $this->lang->line('protected'); ?>
                                </label>
                            </div>    
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="submit" class="btn btn-primary submit_addevent pull-right" value="<?php echo $this->lang->line('save'); ?>"></div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="viewEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_event'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form"   method="post" id="updateevent_form"  enctype="multipart/form-data" action="" >
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_title') ?></label>
                            <input class="form-control" name="title" placeholder="" id="event_title">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description') ?></label>
                            <textarea name="description" class="form-control" placeholder="" id="event_desc"></textarea></div>
                      <div class="row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_from'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" autocomplete="off" name="event_from" class="form-control pull-right event_from">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_to'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" autocomplete="off" name="event_to" class="form-control pull-right event_to">
                            </div>
                        </div>
                            </div>
                        <input type="hidden" name="eventid" id="eventid">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_color') ?></label>
                            <input type="hidden" name="eventcolor" autocomplete="off" placeholder="Event Color" id="event_color" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <?php
$i      = 0;
$colors = '';
foreach ($event_colors as $color) {
    $colorid              = trim($color, "#");
    $color_selected_class = 'cpicker-small';
    if ($i == 0) {
        $color_selected_class = 'cpicker-big';
    }
    $colors .= "<div id=" . $colorid . " class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
    $i++;
}
echo '<div class="cpicker-wrapper selectevent">';
echo $colors;
echo '</div>';
?>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event_type') ?></label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="public" id="public"><?php echo $this->lang->line('public') ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="private" id="private"><?php echo $this->lang->line('private') ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="sameforall" id="public"><?php echo $this->lang->line('all') ?> <?php echo json_decode($role)->name; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="eventtype" value="protected" id="public"><?php echo $this->lang->line('protected') ?>
                            </label>
                        </div>
                        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                            <input type="submit" class="btn btn-primary submit_update pull-right" value="<?php echo $this->lang->line('save'); ?>">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
<?php if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_delete')) {?>
                                <input type="button" id="delete_event" class="btn btn-primary submit_delete pull-right" value="<?php echo $this->lang->line('delete'); ?>">
<?php }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () { 
    $('#viewEventModal,#newEventModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script> 

<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript">
 <?php if ($this->rbac->hasPrivilege('income_donut_graph', 'can_view') && ($this->module_lib->hasActive('income'))) {
    ?>
    new Chart(document.getElementById("doughnut-chart"), {
    type: 'doughnut',
            data: {
            labels: [<?php foreach ($incomegraph as $value) {?>"<?php echo $value['income_category']; ?>", <?php }?> ],
                    datasets: [
                    {
                    label: "Income",
                            backgroundColor: [<?php $s = 1;
    foreach ($incomegraph as $value) {
        ?>"<?php echo incomegraphColors($s++); ?>", <?php
if ($s == 8) {
            $s = 1;
        }
    }
    ?> ],
                            data: [<?php $s = 1;
    foreach ($incomegraph as $value) {
        ?><?php echo $value['total']; ?>, <?php }?>]
                    }
                    ]
            },
            options: {
            responsive: true,
                    circumference: Math.PI,
                    rotation: - Math.PI,
                    legend: {
                    position: 'top',
                    },
                    title: {
                    display: true,
                    },
                    animation: {
                    animateScale: true,
                            animateRotate: true
                    }
            }
    });
   <?php
}if (($this->rbac->hasPrivilege('expense_donut_graph', 'can_view')) && ($this->module_lib->hasActive('expense'))) {
    ?>
    new Chart(document.getElementById("doughnut-chart1"), {
    type: 'doughnut',
            data: {
            labels: [<?php foreach ($expensegraph as $value) {?>"<?php echo $value['exp_category']; ?>", <?php }?>],
                    datasets: [
                    {
                    label: "Population (millions)",
                            backgroundColor: [<?php $ss = 1;
    foreach ($expensegraph as $value) {
        ?>"<?php echo expensegraphColors($ss++); ?>", <?php
if ($ss == 8) {
            $ss = 1;
        }
    }
    ?>],
                            data: [<?php foreach ($expensegraph as $value) {?><?php echo $value['total']; ?>, <?php }?>]
                    }
                    ]
            },
            options: {
            responsive: true,
                    circumference: Math.PI,
                    rotation: - Math.PI,
                    legend: {
                    position: 'top',
                    },
                    title: {
                    display: true,
                    },
                    animation: {
                    animateScale: true,
                            animateRotate: true
                    }
            }
    });
<?php
}
if (($this->module_lib->hasActive('fees_collection')) || ($this->module_lib->hasActive('expense')) || ($this->module_lib->hasActive('income'))) {
    ?>
        $(function () {
        var areaChartOptions = {
        showScale: true,
                scaleShowGridLines: false,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: true,
                bezierCurve: true,
                bezierCurveTension: 0.3,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                maintainAspectRatio: true,
                responsive: true
        };
        var bar_chart = "<?php echo $bar_chart ?>";
        var line_chart = "<?php echo $line_chart ?>";
         <?php
if ($this->rbac->hasPrivilege('fees_collection_and_expense_yearly_chart', 'can_view')) {
        ?>
        if (line_chart) {

        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        var yearly_collection_array = <?php echo json_encode($yearly_collection) ?>;
        var yearly_expense_array = <?php echo json_encode($yearly_expense) ?>;
        var total_month = <?php echo json_encode($total_month) ?>;
        var areaChartData_expense_Income = {
        labels: total_month,
                datasets: [
				<?php if(($this->module_lib->hasActive('expense'))){?>												   
                {
                label: "Expense",
                        fillColor: "rgba(215, 44, 44, 0.7)",
                        strokeColor: "rgba(215, 44, 44, 0.7)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: yearly_expense_array
                },
                <?php } ?>
             <?php if(($this->module_lib->hasActive('income'))){?> 
                {
                label: "Collection",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.9)",
                        pointStrokeColor: "rgba(102, 170, 24, 0.6)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: yearly_collection_array
                }
				 <?php } ?>  
                ]
        };
        lineChart.Line(areaChartData_expense_Income, lineChartOptions);
        }

        var current_month_days = <?php echo json_encode($current_month_days) ?>;
        var days_collection = <?php echo json_encode($days_collection) ?>;
        var days_expense = <?php echo json_encode($days_expense) ?>;
        var areaChartData_classAttendence = {
        labels: current_month_days,
                datasets: [
				 <?php if(($this->module_lib->hasActive('income'))){?>												  
                {
                label: "Electronics",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.6)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: days_collection
                },
				<?php }if(($this->module_lib->hasActive('expense'))){?>											   
                {
                label: "Digital Goods",
                        fillColor: "rgba(233, 30, 99, 0.9)",
                        strokeColor: "rgba(233, 30, 99, 0.9)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "rgba(233, 30, 99, 0.9)",
                        pointHighlightFill: "rgba(233, 30, 99, 0.9)",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: days_expense
                }
				<?php } ?> 
                ]
        };
         
          <?php }if ($this->rbac->hasPrivilege('fees_collection_and_expense_monthly_chart', 'can_view')) {?>
        if (bar_chart) {
            var current_month_days = <?php echo json_encode($current_month_days) ?>;
        var days_collection = <?php echo json_encode($days_collection) ?>;
        var days_expense = <?php echo json_encode($days_expense) ?>;

        var areaChartData_classAttendence = {
        labels: current_month_days,
                datasets: [
				<?php if(($this->module_lib->hasActive('income'))){?>											 
                {
                label: "Electronics",
                        fillColor: "rgba(102, 170, 24, 0.6)",
                        strokeColor: "rgba(102, 170, 24, 0.6)",
                        pointColor: "rgba(102, 170, 24, 0.6)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: days_collection
                },
                    <?php } ?>
                <?php if(($this->module_lib->hasActive('expense'))){ ?>												   
                {
                label: "Digital Goods",
                        fillColor: "rgba(233, 30, 99, 0.9)",
                        strokeColor: "rgba(233, 30, 99, 0.9)",
                        pointColor: "rgba(233, 30, 99, 0.9)",
                        pointStrokeColor: "rgba(233, 30, 99, 0.9)",
                        pointHighlightFill: "rgba(233, 30, 99, 0.9)",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: days_expense
                }
				<?php } ?> 
                ]
        };
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData_classAttendence;
        // barChartData.datasets[1].fillColor = "rgba(233, 30, 99, 0.9)";
        // barChartData.datasets[1].strokeColor = "rgba(233, 30, 99, 0.9)";
        // barChartData.datasets[1].pointColor = "rgba(233, 30, 99, 0.9)";
        var barChartOptions = {
        scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: false,
                scaleShowVerticalLines: false,
                barShowStroke: true,
                barStrokeWidth: 2,
                barValueSpacing: 5,
                barDatasetSpacing: 1,
                responsive: true,
                maintainAspectRatio: true
        };
        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
        }
         <?php }?>
        });
    <?php
}
?>

    $(document).ready(function () {
        $(document).on('click', '.close_notice', function () {
        var data = $(this).data();
        $.ajax({
        type: "POST",
                url: base_url + "admin/notification/read",
                data: {'notice': data.noticeid},
                dataType: "json",
                success: function (data) {
                if (data.status == "fail") {

                errorMsg(data.msg);
                } else {
                successMsg(data.msg);
                }

                }
        });
        });
    });
</script>
<script>
$(document).ready(function() {
    initDatatable('record-list','report/dtbookissuereportlist',[],[],100);
});
</script>
<script>
    
    $(document).on('click', '.full_modal', function () {
        var data = $(this).attr('data-id');

        $('#apeendhere').empty();

        $.ajax({
        type: "POST",
                url: base_url + "admin/book/book_issuedatadisplay",
                data: {'data': data},
                success: function (data) {
                    $('#myModal2').modal('show');
                  $('#apeendhere').append(data);
                  var table = $('.examplee').DataTable({
    
    "aaSorting": [],           
    rowReorder: {
    selector: 'td:nth-child(2)'
    },
    //responsive: 'false',
    
    dom: '<"top"f><Bl>r<t>ip',
    lengthMenu: [[15, 100,-1], [15, 100,"All"]],
          buttons: [

        {
            extend: 'copyHtml5',
            text: '<i class="fa fa-files-o"></i>',
            titleAttr: 'Copy',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel-o"></i>',
            titleAttr: 'Excel',
           
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'csvHtml5',
            text: '<i class="fa fa-file-text-o"></i>',
            titleAttr: 'CSV',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

      {
        extend:    'pdfHtml5',
        text:      '<i class="fa fa-file-pdf-o"></i>',
        orientation: 'landscape',
        pageSize: 'LEGAL',
        titleAttr: 'PDF',
        className: "btn-pdf",
        title: $('.download_label').html(),
          exportOptions: {
            columns: ["thead th:not(.noExport)"],
          
          },
          customize: function(doc) {
            doc.defaultStyle.font = "nikosh";
        }

         

    },

        {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            title: $('.download_label').html(),
         customize: function ( win ) {

            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
            $(win.document.body).find('table').addClass('display').css('font-size', '14px');
            // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
            $(win.document.body).find('h1').css('text-align', 'center');
        },
             exportOptions: {
              stripHtml:false,
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'colvis',
            text: '<i class="fa fa-columns"></i>',
            titleAttr: 'Columns',
            title: $('.download_label').html(),
            postfixButtons: ['colvisRestore']
        },
    ],

    // "scrollY":        "320px",
"pageLength": 15,
      "lengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]

    
});
                }
        });
        });


        
        $(document).on('click', '.full_modal2', function () {
        var data = $(this).attr('data-id');

        $('#apeendhere').empty();

        $.ajax({
        type: "POST",
                url: base_url + "admin/book/memberdatadisplay",
                data: {'data': data},
                success: function (data) {
                    $('#myModal2').modal('show');
                  $('#apeendhere').append(data);
                  var table = $('.examplee').DataTable({
    
    "aaSorting": [],           
    rowReorder: {
    selector: 'td:nth-child(2)'
    },
    //responsive: 'false',
    
    dom: '<"top"f><Bl>r<t>ip',
    lengthMenu: [[15, 100,-1], [15, 100,"All"]],
          buttons: [

        {
            extend: 'copyHtml5',
            text: '<i class="fa fa-files-o"></i>',
            titleAttr: 'Copy',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel-o"></i>',
            titleAttr: 'Excel',
           
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'csvHtml5',
            text: '<i class="fa fa-file-text-o"></i>',
            titleAttr: 'CSV',
            title: $('.download_label').html(),
             exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },

      {
        extend:    'pdfHtml5',
        text:      '<i class="fa fa-file-pdf-o"></i>',
        orientation: 'landscape',
        pageSize: 'LEGAL',
        titleAttr: 'PDF',
        className: "btn-pdf",
        title: $('.download_label').html(),
          exportOptions: {
            columns: ["thead th:not(.noExport)"],
          
          },
          customize: function(doc) {
            doc.defaultStyle.font = "nikosh";
        }

         

    },

        {
            extend: 'print',
            text: '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            title: $('.download_label').html(),
         customize: function ( win ) {

            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
            $(win.document.body).find('table').addClass('display').css('font-size', '14px');
            // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
            $(win.document.body).find('h1').css('text-align', 'center');
        },
             exportOptions: {
              stripHtml:false,
            columns: ["thead th:not(.noExport)"]
          }
        },

        {
            extend: 'colvis',
            text: '<i class="fa fa-columns"></i>',
            titleAttr: 'Columns',
            title: $('.download_label').html(),
            postfixButtons: ['colvisRestore']
        },
    ],

    // "scrollY":        "320px",
"pageLength": 15,
      "lengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]

    
});
                }
        });
        });

</script>    