<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->


  



    <section class="content">
        <div class="row">

      
        <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header w-xs-100">Budget Report For Session : <?php echo $session_name; ?></li>
                        <form action="<?php echo base_url() ?>admin/updater/budgetreport" method="post">
                        <li class="pull-left header w-xs-100">
                        <div>
                        <select name="quarter" id="quarter" class="form-control">
                        <option value="1/4/<?= $filter1 ?> - 30/6/<?= $filter1 ?> - 1st Quarter" <?php if($reselect == '1/4/'.$filter1.' - 30/6/'.$filter1.' - 1st Quarter'){ echo "selected";} ?>>1st Quarter <?php echo $session_name; ?></option>
                        <option value="1/7/<?= $filter1 ?> - 30/9/<?= $filter1 ?> - 2nd Quarter" <?php if($reselect == '1/7/'.$filter1.' - 30/9/'.$filter1.' - 2nd Quarter'){ echo "selected";} ?>>2nd Quarter <?php echo $session_name; ?></option>
                        <option value="1/10/<?= $filter1 ?> - 30/12/<?= $filter1 ?> - 3rd Quarter" <?php if($reselect == '1/10/'.$filter1.' - 30/12/'.$filter1.' - 3rd Quarter'){ echo "selected";} ?>>3rd Quarter <?php echo $session_name; ?></option>
                        <option value="1/1/<?= $filter2 ?> - 30/3/<?= $filter2 ?> - 4th Quarter" <?php if($reselect == '1/1/'.$filter2.' - 30/3/'.$filter2.' - 4th Quarter'){ echo "selected";} ?>>4th Quarter <?php echo $session_name; ?></option>
                        </select>   
                        
                        <div>
                       </li>
                       <li class="pull-left header w-xs-100"><input type="submit" class="btn btn-sm btn-success" name="submit" value="Go">
                       </li>
                       </form>
                        <li class=""><a href="#tab_class" data-toggle="tab" data-original-title="" title="" aria-expanded="false">Expense</a></li>
                        <li class="active"><a href="#tab_birthday" data-toggle="tab" data-original-title="" title="" aria-expanded="false">Income</a></li>
                    </ul>
                    <div class="tab-content ">
                            <div class="tab-pane active" id="tab_birthday">
                                <!-- Income -->
                                <div class="row">
                            
      
            <div class="col-md-12"<?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    echo '8';
} else {
    echo '12';
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Budget <?php echo $this->lang->line('income_list'); ?> Report</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                            <?php $lastsession = intval($session_id) - 1;   
                            $nextsession = intval($session_id) + 1;   
                            $nxtses =  $this->session_model->get($nextsession); 
                             $lstses =  $this->session_model->get($lastsession); ?>
                             <?php $da = explode("-",$lstses['session']) ; ?>
                                                            <thead>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Income Head</th>
                                        <th class="white-space-nowrap text-center">Actual Amount On<br>(31-03-<?=$da[0] ?>)</th>
                                        <th class="white-space-nowrap text-center">Budget Amount <br> (<?php echo $session_name ?>)</th>
                                        <th class="white-space-nowrap text-center">Actual Amount <br>(<?php echo $session_name; ?>)</th>
                                        <th class="white-space-nowrap text-center">Actual Amount On<br>(<?php echo $endDate; ?>)</th>
                                        <th class="white-space-nowrap text-center">Budget Amount <br>(<?php echo $nxtses['session']; ?>)</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                      <?php  foreach($income as $key => $val){   ?>

 
 <!-- <tr style="background-color: #959da542;">
        <td colspan="5" style="text-align: center !important;"><?= $key ?></td>
                      </tr> -->
                      <tr style="background-color:#8d888857">
                                        <th></th>
                                        <th><?= $key ?></th>
                                        <th class="white-space-nowrap text-center"></th>
                                        <th class="white-space-nowrap text-center"></th>
                                        <th class="white-space-nowrap"></th>
                                        <th class="white-space-nowrap"></th>
                                        <th class="white-space-nowrap"></th>
                                    </tr>
    

                     
                                     <?php $i = 0 ;foreach($val as $dat){  $i++;
                                        
                                        $this->db->select()->from('income_head');
                                        $this->db->where('income_head.id', $dat->id);
                                        $query = $this->db->get();
                                        $income = $query->row();


                                        $this->db->select_sum('amount')->from('income');
                                        $this->db->where('income.income_head_id', $dat->id);
                                        $this->db->where('income.is_deleted', 'no');
                                        $this->db->where('income.is_active', 'yes');
                                        $this->db->where('income.session', $session_id);
                                        $queryy = $this->db->get();
                                        $amount =  $queryy->result_array();


                                        $this->db->select_sum('amount')->from('income');
                                        $this->db->where('income.income_head_id', $dat->id);
                                        $this->db->where('income.is_deleted', 'no');
                                        $this->db->where('income.is_active', 'yes');
                                        $this->db->where('income.session', $lastsession);
                                        $queryy = $this->db->get();
                                        $preamount =  $queryy->result_array();
      
                                        
                                        $this->db->select()->from('session_budget');
                                        $this->db->where('session_budget.head', $dat->id);
                                        $this->db->where('session_budget.expense_income','income');
                                        $this->db->where('session_budget.session', $session_id);
                                        $query = $this->db->get();
                                        $sess_bud = $query->row();


                                        $this->db->select()->from('session_budget');
                                        $this->db->where('session_budget.head', $dat->id);
                                        $this->db->where('session_budget.expense_income','income');
                                        $this->db->where('session_budget.session', $nextsession);
                                        $query = $this->db->get();
                                        $nxtsess_bud = $query->row();

                                   
                                           $ab = $amount[0]['amount']??0;

                                           $ba = $preamount[0]['amount']??0;
                                      

                                           $this->db->select_sum('amount')->from('income');
                                           $this->db->where('income.income_head_id', $dat->id);
                                           $this->db->where('income.is_deleted', 'no');
                                           $this->db->where('income.is_active', 'yes');
                                           $this->db->where('income.session', $session_id);
                                           $this->db->where('DATE(date) >=', date('Y-m-d',strtotime($startDate)));
                                           $this->db->where('DATE(date) <=', date('Y-m-d',strtotime($endDate)));
                                           $queryy = $this->db->get();
                                           $quateramount =  $queryy->result_array();
                                           $qb = $quateramount[0]['amount']??0;

                                        if((intval($sess_bud->amount??'0') < $ab) &&  ($ab !== 0) && ($sess_bud->amount??0 !== 0)){
                                            ?>
                                        <tr style="background-color:green;color:white">
                                            <?php }else{ ?>
                                                <tr>
                                                <?php } ?>
                                        <td><?= $i ?></td>
                                        <td ><?= $income->income_category ?></td>
                                        <td class="white-space-nowrap text-center"><?= $ba ?></td>
                                        <td class="white-space-nowrap text-center"><?= $sess_bud->amount??'NA' ?></td>
                                        <td class="white-space-nowrap text-center"><?= $ab ?></td>
                                        <td class="white-space-nowrap text-center"><?= $qb ?></td>
                                        <td class="white-space-nowrap text-center"><?= $nxtsess_bud->amount??'NA' ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                          


                       

                            
                            <?php } ?>
                            </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>
                         
                             <!-- Income -->
                            </div>
                            <div class="tab-pane" id="tab_class">
                                 <!-- Expense -->
                           
                                 <div class="row">
                                <?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    ?>
        
                <!-- left column -->
            <?php }?>
            <div class="col-md-12<?php
if ($this->rbac->hasPrivilege('income', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Budget <?php echo $this->lang->line('expense_list'); ?> Report</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                      

                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                                            <?php $lastsession = intval($session_id) - 1;   
                             $lstses =  $this->session_model->get($lastsession); 
                             $nextsession = intval($session_id) + 1;   
                             $nxtses =  $this->session_model->get($nextsession); ?>
                             <?php $da = explode("-",$lstses['session']) ; ?>
                                    <tr>
                                        <th>S. No</th>
                                        <th>Expense Head</th>
                                        <th class="white-space-nowrap text-center">Actual Amount On<br>(31-03-<?=$da[0] ?>)</th>
                                        <th class="white-space-nowrap text-center">Budget Amount <br> (<?php echo $session_name ?>)</th>
                                        <th class="white-space-nowrap text-center">Actual Amount <br>(<?php echo $session_name; ?>)</th>
                                        <th class="white-space-nowrap text-center">Actual Amount On<br>(<?php echo $endDate; ?>)</th>
                                        <th class="white-space-nowrap text-center">Budget Amount <br>(<?php echo $nxtses['session']; ?>)</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                      <?php  foreach($expense as $key => $val){   ?>

 
 <!-- <tr style="background-color: #959da542;">
        <td colspan="5" style="text-align: center !important;"><?= $key ?></td>
                      </tr> -->
                      <tr style="background-color:#8d888857">
                                        <th></th>
                                        <th><?= $key ?></th>
                                        <th class="white-space-nowrap text-center"></th>
                                        <th class="white-space-nowrap text-center"></th>
                                        <th class="white-space-nowrap"></th>
                                          <th class="white-space-nowrap"></th>
                                          <th class="white-space-nowrap"></th>
                                    </tr>
    

                     
                                     <?php $i = 0 ;foreach($val as $dat){  $i++;
                                        
                                     

                                        $this->db->select()->from('expense_head');
                                        $this->db->where('expense_head.id', $dat->id);
                                        $query = $this->db->get();
                                        $income = $query->row();


                                        $this->db->select_sum('amount')->from('expenses');
                                        $this->db->where('expenses.exp_head_id', $dat->id);
                                        $this->db->where('expenses.is_deleted', 'no');
                                        $this->db->where('expenses.is_active', 'yes');
                                        $this->db->where('expenses.session', $session_id);
                                        $queryy = $this->db->get();
                                        $amount =  $queryy->result_array();
      

                                        $this->db->select_sum('amount')->from('expenses');
                                        $this->db->where('expenses.exp_head_id', $dat->id);
                                        $this->db->where('expenses.is_deleted', 'no');
                                        $this->db->where('expenses.is_active', 'yes');
                                        $this->db->where('expenses.session', $lastsession);
                                        $queryy = $this->db->get();
                                        $preamount =  $queryy->result_array();
                                        $ba = $preamount[0]['amount']??0;

                                        
                                        $this->db->select()->from('session_budget');
                                        $this->db->where('session_budget.head', $dat->id);
                                        $this->db->where('session_budget.expense_income','expense');
                                        $this->db->where('session_budget.session', $session_id);
                                        $query = $this->db->get();
                                        $sess_bud = $query->row();

                                   
                                        $this->db->select()->from('session_budget');
                                        $this->db->where('session_budget.head', $dat->id);
                                        $this->db->where('session_budget.expense_income','expense');
                                        $this->db->where('session_budget.session', $nextsession);
                                        $query = $this->db->get();
                                        $nxtsess_bud = $query->row();


                                           $ab = $amount[0]['amount']??0;
                                      



                                           $this->db->select_sum('amount')->from('expenses');
                                           $this->db->where('expenses.exp_head_id', $dat->id);
                                           $this->db->where('expenses.is_deleted', 'no');
                                           $this->db->where('expenses.is_active', 'yes');
                                           $this->db->where('expenses.session', $lastsession);
                                           $this->db->where('DATE(date) >=', date('Y-m-d',strtotime($startDate)));
                                           $this->db->where('DATE(date) <=', date('Y-m-d',strtotime($endDate)));
                                           $queryy = $this->db->get();
                                           $quateramount =  $queryy->result_array();
                                           $qb = $quateramount[0]['amount']??0;

                      



                                        if((intval($sess_bud->amount??'') < $ab) &&  ($ab !== 0) && ($sess_bud->amount??0 !== 0)){
                                            ?>
                                            <tr style="background-color:red;color:white">
                                                <?php }else{ ?>
                                                    <tr>
                                                    <?php } ?>
                                        <td><?= $i ?></td>
                                        <td ><?= $income->exp_category ?></td>
                                         <td class="white-space-nowrap text-center"><?= $ba ?></td>
                                        <td class="white-space-nowrap text-center"><?= $sess_bud->amount??'NA' ?></td>
                                        <td class="white-space-nowrap text-center"><?= $ab ?></td>
                                        <td class="white-space-nowrap text-center"><?= $qb ?></td>
                                        <td class="white-space-nowrap text-center"><?= $nxtsess_bud->amount??'NA' ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                          


                       

                            
                            <?php } ?>
                            </tbody>
                            </table><!-- /.table -->





                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>

                            <!-- Expense -->
                            </div>
                    </div>
                </div>





  
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- <script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('income-list','admin/income/getincomelist',[],[],100,
            [
                { "bSortable": true, "aTargets": [ -2 ] ,'sClass': 'dt-body-right'},
                 { "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}
            ]);
    });
} ( jQuery ) )
</script> -->

<script>
 $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });


    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>
