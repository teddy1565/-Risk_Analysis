<style type="text/css">
body{
	margin:0px auto;
	text-align:center;
	background:pink;
}
</style>
<html>
<form action="read.php" method="get">
<p>模擬貸款資料URI:<input type="text" name="url"/></p><br />
<p>貸款風險門檻:<input type="text" name="dang"></p><br />
<p>顯示前<input type="text" name="display">筆超過風險門檻值之貸款資料</p><br />
<input type="submit" value="送出"/>
<input type="reset">
</form>
</html>