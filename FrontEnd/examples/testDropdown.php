<?php


echo "<br>Students<br>";
include("config.php");
//$query = "SELECT movieId,movie_name FROM movies";
$query = "SELECT DISTINCT(movie_name) as movie_name, movieId FROM movies";

if($r_set=$con->query($query)){
  echo "<SELECT id=s1 onChange='reload()' name=class class='form-control' style='width:200px;'>";

  while($row=$r_set->fetch_assoc()){
    echo "<option value=$row[movieId]>($row[movieId]) $row[movie_name]</option>";
  }

  echo "</select>";
}else{
  echo $con->error;
}
//-------------------------------------------------------
echo "</div><div class='col-3'><br>Students<br>";

$cat=$_GET['cat'];

$query2 = "SELECT movieId,movie_genre,movie_name FROM movies WHERE movie_genre=?";

if($stmt=$con->prepare($query2)){

  $stmt->bind_param('s',$cat);
  $stmt->execute();
  $r_set=$stmt->get_result();

  echo "<SELECT id=s2 name=student class='form-control' style='width:200px;'>";

  while($row=$r_set->fetch_assoc()){
    echo "<option value=$row[movieId]>$row[movie_genre]</option>";
  }

  echo "</select>";
}else{
  echo $con->error;
}

?>

</div></div></div>
<script>
  function reload(){
    var v1 = document.getElementById('s1').value;
    //document.write(v1);
    self.location='testDropdown.php?cat='+v1;
  }
</script>
