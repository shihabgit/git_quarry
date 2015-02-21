<?php

$servername = "160.153.95.104";
$username = "dbkuppy";
$password = "dB!@123";
$dbname = "the_quarry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error)
{
   die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
   // output data of each row
   while ($row = $result->fetch_assoc())
   {
      echo "id: " . $row["tsk_id"] . " - Name: " . $row["tsk_name"] . "<br>";
   }
}
else
{
   echo "0 results";
}
$conn->close();
?> 