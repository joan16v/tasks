<?php

$link = mysqli_connect(HOST, MYSQL_USER, MYSQL_PASSWORD) or
   die("Could not connect: " . mysqli_error());
mysqli_select_db($link, MYDB);

?>