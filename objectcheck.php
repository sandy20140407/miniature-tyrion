<!DOCTYPE HTML> 
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>demo</title>
        </head>
<body> 


<?php

// 定义变量并设置为空值
$nameErr = "";
$name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["name"])) {
     $nameErr = "objectname是必填的";
   } else {
     $name = test_input($_POST["name"]);
     // 检查姓名是否包含字母
     if (!preg_match("/^[a-zA-Z]*$/",$name)) {
       $nameErr = "只允许字母"; 
     }
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2>Object Check Page</h2>
<p><span class="error">* only  letter</span> input.</p>
<p>* check if the object which you design is accurate.</p>
<p>* support by 12081428. </p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <p><strong>Objectname:</strong>
    <input type="text" name="name">
    <span class="error">* <?php echo $nameErr;?></span>
    <input type="submit" name="submit" value="check">
    <br>
    </br>
    </p>
  
</form>


<?php 

$colname = $name;

if($colname !=""){
$con = mysql_connect("localhost","onlyread","onlyread");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

// some code
mysql_select_db("test", $con);
 
mysql_query('SET NAMES UTF8'); 

$result_deptsys = mysql_query("SELECT d.dept_id as dept_id,d.dept_name as dept_name, s.system_id as system_id, s.system_name as system_name, s.system_user as system_user, s.DBTYPE as dbtype FROM dept_info d, system_info s WHERE d.dept_id = s.DEPT_ID AND s.system_id IN (SELECT DISTINCT system_id FROM column_info WHERE colname LIKE '%$colname%')");

echo "<table border='1'>
<tr>
<th>dept_id</th>
<th>dept_name</th>
<th>system_id</th>
<th>system_name</th>
<th>system_user</th>
<th>dbtype</th>
</tr>";

while($row = mysql_fetch_array($result_deptsys))
  {
  echo "<tr>";
  echo "<td>" . $row['dept_id'] . "</td>";
  echo "<td>" . $row['dept_name'] . "</td>";
  echo "<td>" . $row['system_id'] . "</td>";
  echo "<td>" . $row['system_name'] . "</td>";
  echo "<td>" . $row['system_user'] . "</td>";
  echo "<td>" . $row['dbtype'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
echo "<br></br>";

$result_col = mysql_query("SELECT system_id,tablename,tablecomment,colname,colcomment,coltype,collength,remark FROM column_info where colname like '%$colname%'");

echo "<table border='1'>
<tr>
<th>system_id</th>
<th>tablename</th>
<th>tablecomment</th>
<th>colname</th>
<th>colcomment</th>
<th>coltype</th>
<th>collength</th>
<th>remark</th>
</tr>";

while($row = mysql_fetch_array($result_col))
  {
  echo "<tr>";
  echo "<td>" . $row['system_id'] . "</td>";  
  echo "<td>" . $row['tablename'] . "</td>";
  echo "<td>" . $row['tablecomment'] . "</td>";
  echo "<td>" . $row['colname'] . "</td>";
  echo "<td>" . $row['colcomment'] . "</td>";
  echo "<td>" . $row['coltype'] . "</td>";
  echo "<td>" . $row['collength'] . "</td>";
  echo "<td>" . $row['remark'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
echo "<br></br>";

mysql_close($con);
}
?>    


