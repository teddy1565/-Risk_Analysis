<?php
//ini_set('memory_limit', '4096M'); 
if($_GET['data_volume']!=null&&$_GET['data_name']!=null&&$_GET['display']!=null){
	$data_volume = $_GET['data_volume'];
	$data_name = $_GET['data_name'];
	$display = $_GET['display'];
}else{
	die("請輸入完整資料");
}

if($data_volume>=$display){
	check($data_volume);
	check($display);
}else{
	die("顯示資料數請勿大於產生資料數");
}
$userData = ProduceData($data_volume);
// var_dump($userData[0]);
writeTXT($userData,$data_volume,$data_name);
function check($data){
	$check_number = preg_match("/^[0-9]/",$data);
	if($check_number == false){
		die("請勿於產生資料筆數或顯示資料筆數內輸入非數字或小數");
	}
}
function ProduceData($data_volume){
	for($o=0;$o<$data_volume;$o++){
		$i = $o+1;
		$years = (((47*$i)+$i)%45)+25; //年齡
		$revenue = ((9797*$i)%65000) + 15000; //收入
		$assets = (((797*$i)*$i)%950000)+500;//資產
		$a = gmp_mul("97","$i");
		$a = gmp_mul("$a","$i");
		$Liabilities1 = gmp_mul("$a","$i");
		$a = gmp_mul("97","$i");
		$Liabilities2 = gmp_mul($a,$i);
		$Liabilities3 = gmp_add($Liabilities1,$Liabilities2);
		$Liabilities4 = gmp_mod($Liabilities3,950000);
		$Liabilities_total = gmp_add($Liabilities4,2000); //負債
		$loan_amont = ((((97*$i)*$i)+20000)%35000)+4500; //貸款金額
		$user[$o][0] = $i;
		$user[$o][1] = $years;
		$user[$o][2] = $revenue;
		$user[$o][3] = $assets;
		$user[$o][4] = $Liabilities_total;
		$user[$o][5] = $loan_amont;
	}
	return $user;
}
function writeTXT($userData,$data_volume,$data_name){
	$fileopen = fopen($data_name,"w");
	for($i=0;$i<$data_volume;$i++){
		for($j = 0;$j<6;$j++){
			fwrite($fileopen,$userData[$i][$j]);
			if($j<5){fwrite($fileopen,",");}
		}
		fwrite($fileopen,"\r\n"); //跳下一行(enter);
	}
	fclose($fileopen);
}
?>
<html>
<p>產生模擬資料URI:http://127.0.0.1/MyCode/NTU3(105)-20181028/PHP/<?php echo $data_name;?></p><br />
<?php 
echo"資料前$display";echo"筆:<br />";
for($i=0;$i<$display;$i++){
	for($j=0;$j<6;$j++){
		echo $userData[$i][$j];
		if($j<5){echo",";}
	}
	echo"<br />";
}
?>
</html>