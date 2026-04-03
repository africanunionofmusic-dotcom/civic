<?php
include "connect.php";

if($conn){
    echo "Connection to database successful!";
} else {
    echo "Connection failed!";
}
?>