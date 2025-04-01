<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> PDF </title>
   <style>

.pfd_report{
    background-image: url("backend/images/Expenses_Voucher.jpg");
    margin:40px;
    height:100%;  
    width:100%;
    background-size: 95%;
    background-repeat: no-repeat;
}

.exp_category{
     margin-top:19%;left:28%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.inv_no{
     margin-top:22.5%;left:25%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.name{
     margin-top:29.5%;left:22%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.date{
     margin-top:19.5%;left:78%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}


.page_no{
     margin-top:23%;left:78%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.amount{
     margin-top:38%;left:75%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.amount1{
     margin-top:74%;left:75%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.amount2{
     margin-top:90%;left:32%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.amount3{
     margin-top:112%;left:32%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.amount_in_word_r{
     margin-top:94%;left:29%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.p_info{
     margin-top:97.5%;left:25%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.p_info2{
      margin-top:115.5%;left:26%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.note{
     margin-top:38%;left:10%;font-size:15px;font-weight: bold;position: absolute;width:45%;line-height:30px;
}

.payment_mode{
     margin-top:112.5%;left:68%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.ab{
     margin-top:74%;left:13%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.pb{
     margin-top:103%;left:72%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

.qr{
     margin-top:15%;left:76%;height:120px;width:120px;position: absolute;
}
.approvedby{
  
     margin-top:124%;left:45%;font-size:15px;font-weight: bold;position: absolute;width:100%;

}
.cashbook_no{
     margin-top:23.5%;left:78%;font-size:15px;font-weight: bold;position: absolute;width:100%;

}



.paid_by_date{
     margin-top:116%;left:63%;font-size:15px;font-weight: bold;position: absolute;width:100%;

}

.approved_by_date{
     margin-top:98%;left:70%;font-size:15px;font-weight: bold;position: absolute;width:100%;
}

</style>


</head>

   <body>

 <?php
 
$number = $data['amount'];
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
   $points = ($point);// ?
//     "" . $words[$point / 10] . " " . 
//           $words[$point = $point % 10] : '';
 

$number = $points;
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $points = implode('', $str);




 ?>
 

      <div class="qr"><img src="<?=$data['qr']?>"></div>
      <div class="exp_category"><?=$data['exp_category']?></div>
      <div class="inv_no"><?=$data['invoice_no']?></div>
      <div class="name"><?=$data['name']?></div>
      <div class="date"><?=date('d-m-Y',strtotime($data['date']))?></div>
      <div class="amount"><?=$data['amount']?></div>
      <div class="amount1"><?=$data['amount']?></div>
      <div class="amount2"><?=$data['amount']?></div>
      <div class="amount3"><?=$data['amount']?></div>
      <div class="page_no"><?=$data['page_no']?></div>
      <div class="p_info"><?=$data['p_info']?></div>
      <div class="p_info2"><?=$data['p_info']?></div>

      <div class="payment_mode"><?=$data['payment_mode']?></div>
      <div class="amount_in_word_r"><?=$result?> Rupees <?=$points;?> <?php if($points > 0){?> Paisa <?php } ?></div>

      <!-- <div class="paid_by"><?=$data['paid_by']?></div>
      <div class="created_by"><?=$data['created_by']?></div>
      <div class="approved_by"><?=$data['approved_by']?></div> -->



      <div class="note"><?=$data['note']?></div>
      <?php 
      
     $kuchbhi = $this->expense_model->staffget($data['created_by']);
 
      ?>
      <div class="ab"><?= $kuchbhi['name'] ?></div>


      <?php 
      
      $paid_by = $this->expense_model->staffget($data['approved_by']);
  
       ?>

      <div class="pb"><?=$paid_by['name']?></div>
      <?php 
      
      $approved_by = $this->expense_model->staffget($data['paid_by']);
  
       ?>
      <div class="approvedby"><?=$approved_by['name']?></div>
      <div class="cashbook_no"><?=$approved_by['page_no']?></div>


      <div class="approved_by_date"><?php if($data['approved_by_date']){ echo date('d-m-Y',strtotime($data['approved_by_date'])); }   ?></div>
      <div class="paid_by_date"><?php if($data['approved_by_date']){ echo date('d-m-Y',strtotime($data['paid_by_date'])); } ?></div>
      
   	<div class="pfd_report" ></div>

   </body>




</html>


