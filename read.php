<?php
ini_set('memory_limit', '1024M'); 
if($_GET['url']!=null&&$_GET['dang']!=null&&$_GET['display']!=null){
	$url = $_GET['url'];
	$dang = $_GET['dang'];//風險門檻
	$display = $_GET['display'];
	$url = substr($url,47);
	$rows = checkdata($url);
	if($display>$rows){
		die("顯示資料數請勿大於資料數");
	}
}else{
	die("請輸入資料");
}
$data = getData($url);
$Statement = read_data($data);
$newUserData = Calculation($Statement,$data);
$sortcheck = usort($newUserData,"my_sort");

function read_URL(){ //解析URL * 不可解析URI * 
	print_r(parse_url($url));
	echo parse_url($url, PHP_URL_PATH);
}
function read_file($url){ //讀取txt檔案--小抄
	$rows = -1;
	$file = fopen($url, "r") or die("請確認檔案是否存在或是否輸入正確");
	while(!feof($file)){	//輸出文本中所有的行，直到文件結束為止。
		$rows++;
		echo fgets($file). "<br />";//當讀出文件一行後，就在後面加上 <br> 讓html知道要換行
	}
	fclose($file);
/*
fopen 是開啟檔案的程式
feof 是檢測是否已到文件末尾
fgets 是讀取文字檔的程式，一次讀一行，直到 /n (分行符號)
*/
}
function getData($url){ //取得txt檔案內的資料,並逐行掃描
	$rows = -1;
	$i = 0;
	$file = fopen($url, "r") or die("請確認檔案是否存在或是否輸入正確");
	while(!feof($file)){	//輸出文本中所有的行，直到文件結束為止。
		$rows++;
		$data[$i] = fgets($file). "<br />";//當讀出文件一行後，就在後面加上 <br> 讓html知道要換行
		$i++;
	}
	fclose($file);
	return $data;
}
function checkdata($url){ //確認要顯示的資料是否大於資料
	$rows = -1;
	$file = fopen($url, "r") or die("請確認檔案是否存在或是否輸入正確");
	while(!feof($file)){	//輸出文本中所有的行，直到文件結束為止。
		$rows++;
		fgets($file). "<br />";//當讀出文件一行後，就在後面加上 <br> 讓html知道要換行
	}
	fclose($file);
	return $rows;
}
function read_data($data){ //讀取資料,並轉為陣列
	$o = count($data)-2;
	for($i=0;$i<$o;$i++){
		$Statement[$i] = explode(",",$data[$i]);
	}
	return $Statement;
}
function Calculation($data_array,$data){ //放讀取資料後的陣列,並計算
	$real_length = count($data)-2;
	for($i=0;$i<$real_length;$i++){
		$dang = floor(((($data_array[$i][1]*50)/75)-((50*$data_array[$i][2])/80000)-((($data_array[$i][3]-$data_array[$i][4])*60)/60000)+((40*$data_array[$i][5])/50000)));
		$user[$i]['uid'] = $data_array[$i][0];
		$user[$i]['years'] = $data_array[$i][1];
		$user[$i]['revenue'] = $data_array[$i][2];
		$user[$i]['assets'] = $data_array[$i][3];
		$user[$i]['Liabilities'] = $data_array[$i][4];
		$user[$i]['loan_amont'] = $data_array[$i][5];
		$user[$i]['dang'] = $dang;
	}
	return $user;
}
function my_sort($a,$b){//由大排到小,反之
	if($a['dang']==$b['dang'])return 0;
	return ($a['dang']>$b['dang'])?-1:1; 
}
?>
<html>
<table border="1">
<?php
if($sortcheck == true){
	echo "<tr><td>編號</td><td>年齡</td><td>收入</td><td>資產</td><td>負債</td><td>貸款金額</td><td>風險值</td></tr>";
	for($i=0;$i<(count($data)-2);$i++){
		$uid[$i] = $newUserData[$i]['uid'];
		$years[$i] = $newUserData[$i]['years'];
		$revenue[$i] = $newUserData[$i]['revenue'];
		$assets[$i] = $newUserData[$i]['assets'];
		$Liabilities[$i] = $newUserData[$i]['Liabilities'];
		$loan_amont[$i] = $newUserData[$i]['loan_amont'];
		$thedang[$i] = $newUserData[$i]['dang'];
		//echo "<tr><td>$uid</td><td>$years</td><td>$revenue</td><td>$assets</td><td>$Liabilities</td><td>$loan_amont</td><td>$thedang</td></tr>";
	}
	for($j=0;$j<$display;$j++){
		if($thedang[$j]>$dang){
			echo "<tr><td>$uid[$j]</td><td>$years[$j]</td><td>$revenue[$j]</td><td>$assets[$j]</td><td>$Liabilities[$j]</td><td>$loan_amont[$j]</td><td>$thedang[$j]</td></tr>";
		}
	}
}else{
	die("無法排序,請重新確認檔案或程式碼");
}
?>
</table>
</html>