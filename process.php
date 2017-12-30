<?php

include('dbconnection.php');

$userID = "";
$name = "";
$email = "";
$phonenumber = "";
$msg = "";
// echo "<pre>".print_r($_GET['deleteID'],true)."</pre>";die;
if (isset($_GET['userID']) && ctype_digit($_GET['userID'])) {
    $userID = $_GET['userID'];
}
if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
        // echo "kolii";die;
        $userID = $_GET['deleteID'];
        $query = "Delete from user where ID = ".$userID;
        $result = mysqli_query($conn, $query);
       
        
        // echo "<pre>".print_r($data,true)."</pre>";die;
        if ($result) {
            echo json_encode([ 'error' => false, 'message' => 'Successfully']);
        } else {
            echo json_encode([ 'error' => true, 'message' => mysqli_error($conn) ]);
        }
    }else{
        if ($userID != "") {
            
            // echo "asdfasdf";die;
        	    $query = "Select * from user where ID = ".$userID;
        	    $result = mysqli_query($conn, $query);
        	    $data = mysqli_fetch_assoc($result);
        	    
        	    // echo "<pre>".print_r($data,true)."</pre>";die;
        	    if ($data) {
        	        echo json_encode([ 'error' => false, 'message' => $data]);
        	    } else {
        	        echo json_encode([ 'error' => true, 'message' => 'No Data']);
        	    }
            
        }else{

        	if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if(isset($_POST['ID']) && ctype_digit($_POST['ID'])){
                   $userID =  $_POST['ID'];
                }
        	    if (isset($_POST['Name']) && $_POST['Name'] != "") {
        	        $name = $_POST['Name'];
        	    }
        	    if (isset($_POST['Email']) && $_POST['Email'] != "") {
        	        $email = $_POST['Email'];
        	    }
        	    if (isset($_POST['PhoneNumber']) && $_POST['PhoneNumber'] != "") {
        	        $phonenumber = $_POST['PhoneNumber'];
        	    }
        	    if ($name == "") {
        	        $msg .= "<br>Name Please";
        	    }

        	    if ($email == "") {
        	        $msg .= "<br>Email Please";
        	    }

        	    if ($phonenumber == "") {
        	        $msg .= "<br>Phone Number Please";
        	    }

        	    if ($msg == "") {


                    if(isset($userID) && $userID != ""){
                        $query = "update user set Name ='" . $name . "',Email ='" . $email . "',PhoneNumber ='". $phonenumber . "' where ID = '".$userID."' ";
                    }else{
                        // echo "ins";die;
                        $query = "insert into user set Name ='" . $name . "',Email ='" . $email . "',PhoneNumber ='" . $phonenumber . "'";
                    }

        	        if (mysqli_query($conn, $query)) {
        	            echo json_encode([ 'error' => false, 'message' => 'Successfully']);
        	        } else {
        	            echo json_encode([ 'error' => true, 'message' => mysqli_error($conn)]);
        	        }
        	    } else {
        	        echo json_encode([ 'error' => true, 'message' => $msg]);
        	    }
        	} else {
        	    $query = "Select * from user";
        	    $result = mysqli_query($conn, $query);
        	//    $row = mysqli_fetch_assoc($result);
        	    $data = [];
        	    while ($row = mysqli_fetch_assoc($result)) {
        	        $data[] = $row;
        	    }
        	    if ($data) {
        	        echo json_encode([ 'error' => false, 'message' => $data]);
        	    } else {
        	        echo json_encode([ 'error' => true, 'message' => 'No Data']);
        	    }
        	}
        }
}
?>