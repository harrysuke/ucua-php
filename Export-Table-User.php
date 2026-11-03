<?php
include('testFunctions.php');

$sql = "SELECT * FROM user ORDER BY staffId ASC";
$result = mysqli_query($db,$sql);
while($row = mysqli_fetch_array($result)){
    echo '"'.$row['staffId'].'",';
    echo '"'.md5($row['Password']).'",';
    echo '"'.md5($row['Password1']).'",';
    echo '"'.$row['Name'].'",';
    echo '"'.$row['PhoneNo'].'",';
    echo '"'.$row['Department'].'",';
    echo '"'.$row['Designation'].'",';
    echo '"'.$row['AccessLevel'].'",';
    echo '"'.$row['Email'].'",';
    echo '"'.$row['Status'].'",';
    echo '"'.$row['createby'].'",';
    echo '"'.$row['createdate'].'",';
    echo '"'.$row['editby'].'",';
    echo '"'.$row['editdate'].'",';
    echo '"'.$row['isdelete'].'",';
    echo '"'.$row['deleteby'].'",';
    echo '"'.$row['deletedate'].'"';
	echo '<br/>';
}
?>