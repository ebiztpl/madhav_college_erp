<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->


  



    <section class="content">
        <div class="row">


     
                
                            
                                <!-- Income -->
                                <div class="row">
                            
      
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Assests Depreciation Report</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <?php 
                        $lastsession =  intval($this->setting_model->getCurrentSession()) - 1;
                                 $lstses =  $this->session_model->get($lastsession);
          


                        ?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                        <table class="table table-hover table-striped table-bordered example">
                                                            <thead>
                                    <tr>
                                        <th colspan="4" class="text-center" style="background-color:#0000001a">Assets</th>
                                        <th colspan="5" class="text-center"  style="background-color:#00000057;color:">Gross Block</th>
                                        <th colspan="4" class="text-center"  style="background-color:#0000001a">Accumulated Depreciation Amortisation</th>
                                        <th  colspan="2" class="text-center" style="background-color:#00000057;color:">Net Block</th>
                                    </tr>
                                    <tr>
                                    <th  class="text-center" style="background-color:#0000001a">S No.</th>
                                        <th  class="text-center" style="background-color:#0000001a">Tangling Assests / Own Assests</th>
                                        <th  class="text-center" style="background-color:#0000001a">Depreciation %</th>
                                        <th  class="text-center" style="background-color:#0000001a">Useful Life(In Years)</th>


                                        <th class="text-center" style="background-color:#00000057;color:">Balance As At <?php echo $lstses['session']; ?></th>
                                        <th  class="text-center" style="background-color:#00000057;color:">Additions During The Year</th>
                                        <th  class="text-center" style="background-color:#00000057;color:">Additions On Accounts On Bussiness Acquisition</th>
                                        <th  class="text-center" style="background-color:#00000057;color:">Deletion During The Year</th>
                                        <th class="text-center" style="background-color:#00000057;color:">Balance As At <?php echo $this->setting_model->getCurrentSessionName(); ?></th>


                                        <th class="text-center" style="background-color:#0000001a">Balance As At <?php echo $lstses['session']; ?></th>
                                        <th  class="text-center" style="background-color:#0000001a">Provided During The Year</th>
                                        <th  class="text-center" style="background-color:#0000001a">Deletion/Adjustment During The Year</th>
                                        <th class="text-center" style="background-color:#0000001a">Balance As At <?php echo $this->setting_model->getCurrentSessionName(); ?></th>


                                        <th class="text-center" style="background-color:#00000057;color:">Balance As At <?php echo $this->setting_model->getCurrentSessionName();; ?></th>
                                        <th class="text-center" style="background-color:#00000057;color:">Balance As At <?php echo $lstses['session']; ?></th>

                                    </tr>
                              
                                </thead>
                            <?php $count=0;?>
                                <tbody>
<?php 
			$val9 = 0; $val10 = 0;	$val11 = 0;	$val12 = 0;	$val13 = 0;	$val14 = 0;	$val15 = 0;	$val16 = 0;	
									
									foreach($cate as $category){ $count++;?>
                                <tr>

<td class="text-center" style="background-color:#0000001a"><?= $count ?></td>
<td class="text-center" style="background-color:#0000001a"><?= $category->item_category ?></td>
<td class="text-center" style="background-color:#0000001a"><?= $category->dep_per ?> %</td>
<td  class="text-center" style="background-color:#0000001a"><?= $category->usefullife ?> Years</td>

<?php


$this->db->select('id')->from('itemm');
$this->db->where('item_category_id',$category->id);
$ite = $this->db->get();
$items = $ite->result();

$value1 = 0;
$value2 = 0;
$value3 = 0;
$value4 = 0;
$value5 = 0;
$value6 = 0;
$value7 = 0;
$value8 = 0;
					
								   
								   
$currentses = $this->setting_model->getCurrentSession();
								  
    foreach($items as $item){ 
		
    $this->db->select_sum('purchase_price');
    $this->db->from('item_stockk');
    $this->db->where('item_id',$item->id);
    $this->db->where('session <',$currentses);
    $query = $this->db->get();
    $data = $query->result_array();
   
    $value1 += $data[0]['purchase_price'];

		
		
    $this->db->select_sum('purchase_price');
    $this->db->from('item_stockk');
    $this->db->where('item_id',$item->id);
    $this->db->where('session',$currentses);
    $query = $this->db->get();
    $data = $query->result_array();

    $value2 += $data[0]['purchase_price'];
    
    $value3 = intval($value1) + intval($value2) ;
		
	

    $this->db->select_sum('depreciation_value');
    $this->db->from('item_stockk');
    $this->db->where('item_id',$item->id);
    $this->db->where('session <',$currentses);
    $query = $this->db->get();
    $data = $query->result_array();

    $value4 += $data[0]['depreciation_value'];


    $this->db->select_sum('depreciation_value');
    $this->db->from('item_stockk');
    $this->db->where('item_id',$item->id);
    $this->db->where('session',$currentses);
    $query = $this->db->get();
    $data = $query->result_array();

    $value5 += $data[0]['depreciation_value'];

		
    $value6 = $value4 + $value5;
	
    $value7 = $value3 - $value6;
	
    $value8 = $value1 - $value4;
	
    }
 $val10 += $value2;
																	$val16 += $value8;
			$val15 += $value7;
		$val14 += $value6;
	$val13 += $value5;
		$val12 += $value4;
			$val9 += $value1; 
$val11 += $value3; 
?>




<td class="text-center" style="background-color:#00000057;color:"><?php echo($value1);  ?></td>
<td  class="text-center" style="background-color:#00000057;color:"><?php echo($value2);  ?></td>
<td  class="text-center"  style="background-color:#00000057;color:">-</td>
<td  class="text-center" style="background-color:#00000057;color:">-</td>
<td class="text-center" style="background-color:#00000057;color:"><?php echo($value3);  ?></td>


<td class="text-center" style="background-color:#0000001a"><?php echo($value4);  ?></td>
<td  class="text-center" style="background-color:#0000001a"><?php echo($value5);  ?></td>
<td  class="text-center" style="background-color:#0000001a">-</td>
<td class="text-center" style="background-color:#0000001a"><?php echo($value6);  ?></td>


<td class="text-center" style="background-color:#00000057;color:"><?php echo($value7);  ?></td>
<td class="text-center" style="background-color:#00000057;color:"><?php echo($value8);  ?></td>

</tr>
<?php  } ?>

                              
                                </tbody>
																 <tfoot> 
<tr style="background-color:#424242;color:white">			
	
	<td class="text-center" style="background-color:"></td>
<td class="text-center" style="background-color:">Total</td>
<td class="text-center" style="background-color:"></td>
<td  class="text-center" style="background-color:"></td>
	
<td class="text-center" style="background-color:;color:"><?php echo $val9 ?></td>
<td  class="text-center" style="background-color:;color:"><?php echo $val10 ?></td>
<td  class="text-center"  style="background-color:;color:">-</td>
<td  class="text-center" style="background-color:;color:">-</td>
<td class="text-center" style="background-color:;color:"><?php echo $val11 ?></td>


<td class="text-center" style="background-color:"><?php echo $val12 ?></td>
<td  class="text-center" style="background-color:"><?php echo $val13 ?></td>
<td  class="text-center" style="background-color:">-</td>
<td class="text-center" style="background-color:"><?php echo $val14 ?></td>


<td class="text-center" style="background-color:;color:"><?php echo $val15 ?></td>
<td class="text-center" style="background-color:;color:"><?php echo $val16 ?></td>

</tr>
 </tfoot> 
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
                                </div>
                         
                             <!-- Income -->
                          
                            
                   





  
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
    $(function(){
        $('#form1'). submit( function() {           
            $("#submitbtn").button('loading');
        });
    })
</script>
