<?php

include "config.php";

//select query
$sql = "DELETE FROM videos WHERE ID='$_GET[id]'";

//execute the query
if(mysqli_query($con,$sql))
    header("refresh:1; url=videos.php");
else
    echo "Not Deleted";

?>