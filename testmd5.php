<?php
include('testFunctions.php');

function updatePassword($Password, $staffId){
    global $db;
    $sql = "UPDATE user SET Password = '".md5($Password)."', Password1 = '".md5($Password)."' WHERE staffId = '".$staffId."' ";
    mysqli_query($db,$sql);
}

if(isset($_POST['submit'])){
    $counter = $_POST['counter'];

    for($j=0;$j<=$counter;$j++){
        updatePassword($_POST['password'.$j], $_POST['staffid'.$j]);
    }

    header('Location: login');
    
}

echo '<form method="post" action="testmd5.php">';
echo '<table>';

$i = 0;
$sql = "SELECT staffId, Password FROM user ORDER BY staffId";
$result = mysqli_query($db,$sql);
while($row = mysqli_fetch_array($result)){
    echo '<tr>';
    echo '<td><input type="text" name="staffid'.$i.'" value="'.$row['staffId'].'" />';
    echo '<input type="text" name="password'.$i.'" value="'.$row['Password'].'" />';
    echo '</tr>';
    $i++;
}

echo '<input type="text" name="counter" value="'.$i.'" />';
echo '<input type="submit" name="submit" value="Submit"/>';
echo '</table>';
echo '</form>';
?>