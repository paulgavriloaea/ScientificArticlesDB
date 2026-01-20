
<?php

require "config.php";

try {
$con= new PDO("mysql:host=$host", $username, $password, $options);
$sql = file_get_contents("data/init.sql");
$con->exec($sql);

echo "Database and tables created succesfully.";

} catch(PDOException $error){
echo $sql."<br>".$error->getMessage();
}

