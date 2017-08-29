<?php

$filename = "users.csv";
$newFile = "users.sql";
$row = 0;
if (false){
if (($handle = fopen($filename, "r")) !== FALSE )
//if (($handle = fopen($filename, "r")) !== FALSE && ($sql = fopen($newFile, "w+")) !== FALSE) 
    { 
        //ENDERECO LOCAL UTILIZADO PELO DOCKER 172.17.0.1
        $mysql = new mysqli("172.17.0.1","root","root","picpay","6603") or die (mysqli_connect_error());
//        $mysql->begin_transaction();
        $sql = "";
        //$mysql->query($query);
        /*
         * ESTRUTURA MYSQL
         */
//        fwrite( $sql,"DROP TABLE IF EXISTS 'users';" . PHP_EOL);
//        fwrite( $sql, "CREAT TABLE 'users' (" . PHP_EOL);
//        fwrite( $sql, "'id' CHAR(36) NOT NULL," . PHP_EOL);
//        fwrite( $sql, "'name' VARCHAR(75) NOT NULL," . PHP_EOL);
//        fwrite( $sql, "'username' VARCHAR(50) NOT NULL," . PHP_EOL);
//        fwrite( $sql, ") ENGINE = MYISAM;" . PHP_EOL);
//        fwrite( $sql,"". PHP_EOL);
//        fwrite( $sql,"". PHP_EOL);
         $sql .= "use `picpay`". PHP_EOL;
         $sql .="DROP TABLE IF EXISTS `users`;" . PHP_EOL;
         $sql .= "CREATE TABLE `users` (" . PHP_EOL;
         $sql .= "`id` CHAR(36) NOT NULL," . PHP_EOL;
         $sql .= "`name` VARCHAR(75) NOT NULL," . PHP_EOL;
         $sql .= "`username` VARCHAR(50) NOT NULL" . PHP_EOL;
         $sql .= ") ENGINE = MYISAM;" . PHP_EOL;
         $sql .=" flush tables;". PHP_EOL;
         $sql .="". PHP_EOL;
         
         //echo  PHP_EOL.$sql. PHP_EOL;
//          $mysql->query($sql);
//         echo $mysql->commit();
        
//        fwrite( $sql,"LOCK TABLES 'users' WRITE;". PHP_EOL);
//        
//        fwrite( $sql,"INSERT INTO 'users' VALUES ". PHP_EOL);
         
        //$sql .= "LOCK TABLES 'users' WRITE;". PHP_EOL;
        
         
         
        /*
         * $data[0] = Id
         * $data[1] = Name
         * $data[2] = Username
         */
       //  $mysql->begin_transaction();
        while (($data = fgetcsv($handle, ",")) !== FALSE)
        {
            /*
             * DADOS PARA A TABELA
             */
        $sql = "";
        $sql .= "INSERT INTO `users` VALUES ". PHP_EOL;
//            if($row === 0)
//            {
        $sql .="('".addslashes($data[0])."','".addslashes($data[1])."','".addslashes($data[2])."');";
//            }
//            else{
//                fwrite( $sql,",('".$data[0]."'),('".$data[1]."'),('".$data[2]."')");
//            }
            $row++;
//            if (fmod($row , 1000) ===0 )
//            {
//                echo $sql.PHP_EOL;
//                $mysql->commit();
//                $mysql->begin_transaction();
//                
//            }
        $mysql->query($sql);
        }
        $mysql->query("FLUSH TABLES;");
        $mysql->close();
//        $mysql/
//        echo $mysql->commit();
//        fwrite( $sql,";". PHP_EOL);
//        fwrite( $sql,"UNLOCK TABLES;". PHP_EOL);
        //fwrite( $sql,"FLUSH;". PHP_EOL);
    }

else
{
    echo "error";
}
fclose($handle);
//fclose($sql);
}

//mysqli_connect($host,$user,$password,$database,$port,$socket);
//mysqli_connect("localhost","root","root",$database,$port,$socket);
//$conn = new mysqli("test-mysql","root", "picpay","6603");
//echo($conn);


echo("fim");