<?php
//$name= "lista_relevancia_1";
$name= "lista_relevancia_2";
$filename = $name.".txt";
$newFile = $name.".sql";

if (false){
    if (($handle = fopen($filename, "r")) !== FALSE )
//    if (($handle = fopen($filename, "r")) !== FALSE && ($sqlFile = fopen($newFile, "w+")) !== FALSE) 
    {
    $sql = "";
       $mysql = new mysqli("172.17.0.1","root","root","picpay","6603") or die (mysqli_connect_error());
        /*
         * ESTRUTURA MYSQL
         */
//        $sql .= "use `picpay`". PHP_EOL;
//        $sql .="DROP TABLE IF EXISTS `".$name."`;" . PHP_EOL;
//        $sql .= "CREATE TABLE `".$name."` (" . PHP_EOL;
//        $sql .= "`id` CHAR(36) NOT NULL" . PHP_EOL;
//        $sql .= ") ENGINE = MYISAM;" . PHP_EOL;
//        $sql .=" flush tables;". PHP_EOL;
//        $sql .="". PHP_EOL;
        
//        fwrite($sqlFile,$sql);
//         $mysql->query($sql);
        
        while (($data = fgets($handle)) !== FALSE)
        {
            $sql = "";
            $sql .= "INSERT INTO `".$name."` VALUES ";
            $sql .="('".addslashes($data)."');". PHP_EOL;
//            fwrite($sqlFile,$sql);
            $mysql->query($sql);
            
        }
        $mysql->query("FLUSH TABLES;");

        $mysql->close();
    }

}

