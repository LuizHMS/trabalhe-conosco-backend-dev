<?php

$get = $_GET;
//$filename = "users.csv";

$response =  array();
$json = array();
$start = empty($get["start"])?0:$get["start"]; //Primeiro item da pagina a ser carregada
$registros = 0;
$maxResults = empty($get["length"])?15:$get["length"]; //Numero de resultados por pagina
//$row = 0;
$totalRows = empty($_SESSION["totalRows"])?0:$_SESSION["totalRows"];
$search = $get["search"]["value"];
$Filtrar = (!empty($search)|| $search === "0")?true:false; //verificar se sera nescessario fazer uma filtragem
$recordsFiltered = empty($_SESSION["recordsFiltered"])?$totalRows:$_SESSION["recordsFiltered"];


try
{
    if($get["draw"] > 0)  //verifica se a requisicao partiu do plugin DataTables
    {
//$mysql = new mysqli("localhost","root","root","picpay","6603") or die (mysqli_connect_error().PHP_EOL);
        $mysql = new mysqli("172.17.0.1","root","root","picpay","6603") or die (mysqli_connect_error().PHP_EOL);

//$json["debug"][] = $get["filtrar"];
//if($get["filtrar"])
//{
/* @var $result mysqli_result */
        /*
         * Contagem de linhas filtradas
         */
        if($Filtrar && (empty($_SESSION["search"])  || $_SESSION["search"] !== $search ) )
        {
            $sql = "SELECT count(*) FROM users";
            $_SESSION["search"] = $search;
            $search = addslashes($search);
            $sql .= " WHERE name LIKE '%".$search."%' OR username='%".$search."%'";
    //        $recordsFiltered = 0;

    //        if (($handle = fopen($filename, "r")) !== FALSE) 
    //        { 
    //            while (($data = fgetcsv($handle, ",")) !== FALSE)
    //            {
    //                 if ((strpos($data[1],$search) !== false) ||(strpos($data[2],$search) !== false)) 
    //                 {
    //                     $recordsFiltered++;
    //                 }
    //            }
    //        }
    //        fclose($handle);
            $sql .= ";";
            $result = $mysql->query($sql);
            $json["debug"]["sql"][] = $sql;
            while ($row = $result->fetch_row()) 
            {
//             $json["debug"][] = $row[0];
                $recordsFiltered = $row[0];
            }
//            $json["debug"][] = $sql;
//            if ($result !== false)
//            {
//                 $json["debug"][] = $result;
//            }
            $_SESSION["recordsFiltered"] = $recordsFiltered;
        }

    //    $json["debug"][] = $recordsFiltered;
    //}
    $json["draw"] = $get["draw"]; //resposta esperada do plugin jquery DataTables


    //$json["debug"] = (!empty($get["search"]["value"])||)?false:true;

/*
 * Pega os dados do banco
 */
        $_SESSION["search"] = $search;
        $search = addslashes($search);
        /*
         * Verificar listas de relevancia e verificar se ira ser preciso resgatar dados por elas
         */
        $r1 = 0; //Resultados da lista de relevancia 1
        $r2 = 0; //Resultados da lista de relevancia 2
        $offset = 0;
        $offset2 = 0;
        $flgPesquisa = 0; // determina qual query ira resgatar os dados
        $sql = "SELECT count(*) ";
        if ($Filtrar)
        {
            $sql .= "FROM users u WHERE ";
            $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
            $sql .= "u.id IN (SELECT id from lista_relevancia_1)";
        }
        else
        {
            $sql .="FROM lista_relevancia_1 ";
        }
        $sql .= ";";
        
        $result = $mysql->query($sql);
        while ($row = $result->fetch_row()) 
        {
            $r1 = $row[0];
        }
        $json["debug"]["sql"][] = $sql;
        $json["debug"]["r1"] = $r1;
        
        if ($r1 < $start + $maxResults)
        {
            $offset = ($start===0)? $start + $maxResults - $r1:$start-$r1;
            if ($offset < 0)
            {
                $offset = $start + $maxResults - $r1;
            }
            
            $sql = "SELECT count(*)  ";
            if ($Filtrar)
            {
                $sql .= "FROM users u WHERE ";
                $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                $sql .= "u.id IN (SELECT id from lista_relevancia_2)";
            }
            else
            {
                $sql .="FROM lista_relevancia_2 ";
            }
            $sql .= ";";

            $result = $mysql->query($sql);
            while ($row = $result->fetch_row()) 
            {
                $r2 = $row[0];
            }
            $json["debug"]["sql"][] = $sql;
           

            if ($r2  <  $offset + $maxResults)
            {                
                $offset2 = $offset - $r2 ;                
                
                if ($r1 + $r2 < $start)
                {
                    $flgPesquisa = 6;
                }
                elseif($r2 === '0' && $offset2 < $maxResults)
                {
                    $flgPesquisa = 3;
                }
                elseif($r1 === '0' && $offset2 + $maxResults > $r2)
                {
                    if ($start === 0)
                    {
                        $offset = $start;
                    }
                    $flgPesquisa = 5;
                }                
            }
            else
            {
                if ($offset + $maxResults < $r2)
                {
                    $flgPesquisa = 4;
                }
                else
                {
                    $flgPesquisa = 2;
                }
            }

        }
        else
        {
            $flgPesquisa = 1;
        }
//        $json["debug"][] = $sql;
        $json["debug"]["Flag"] = $flgPesquisa;

        switch ($flgPesquisa)
        {
            case 1: //resultados que pertencem a Lista_Prioridade_1
                {
                    $sql = "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_1)";
                    $sql .= " LIMIT ".$start.",".$maxResults;
                    $sql .= ";";
            
                    break;
            
                }
            case 2: //resultados que pertencem a Lista_Prioridade_1 e a Lista_Prioridade_2 
                {
                    $sql = "SELECT * FROM (";
                    $sql .= "(SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_1)";
                    $sql .= " LIMIT ".$start.",".$maxResults;
                    $sql .= ") UNION (";
                    $sql .= "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset;
                    $sql .= ")";
                    $sql .= ") total;";
            
                    break;
            
                }
            case 3:  //resultados que pertencem a Lista_Prioridade_1 e restante da tabela users
                {
                    $sql = "SELECT * FROM (";
                    $sql .= "(SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_1)";
                    $sql .= " LIMIT ".$start.",".$maxResults;
                    $sql .= ") UNION (";
                    $sql .= "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                   $sql .= "u.id NOT IN (SELECT id FROM lista_relevancia_1) AND u.id NOT IN (SELECT id FROM lista_relevancia_2)";
                   $sql .= " LIMIT ".$offset2;
                   $sql .= ")";
                   $sql .= ") total;";
            
                    break;
            
                }
            case 4:  //resultados que pertencem a Lista_Prioridade_2
                {
                    $sql = "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id FROM lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset.",".$maxResults;
                    $sql .= ";";
            
                    break;
            
                }
            case 5:  //resultados que pertencem a Lista_Prioridade_2 e restante da tabela users
                {
                    $sql = "SELECT * FROM (";
                    $sql .= "(SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset.",".$maxResults;
                    $sql .= ") UNION (";
                    $sql .= "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id NOT IN (SELECT id FROM lista_relevancia_1) AND u.id NOT IN (SELECT id FROM lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset2;
                    $sql .= ")";
                    $sql .= ") total;";
            
                    break;
            
                }
            case 6:  //resultados que pertencem ao restante da tabela users
                {
                    $sql = "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id NOT IN (SELECT id FROM lista_relevancia_1) AND u.id NOT IN (SELECT id FROM lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset2.",".$maxResults;
                    $sql .= ";";
            
                    break;
            
                }
            default:  //resultados que pertencem a Lista_Prioridade_1, Lista_Prioridade_2 e restante da tabela users
                {
                    $sql = "SELECT * FROM ";

                    $sql .= "(SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_1)";
                    $sql .= " LIMIT ".$start.",".$maxResults;
                    $sql .= ") UNION (";
                    $sql .= "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id IN (SELECT id from lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset;
                    $sql .= ") UNION (";
                    $sql .= "SELECT u.* FROM users u WHERE ";
                    if ($Filtrar)
                    {
                        $sql .= "(u.name LIKE '%".$search."%' OR u.username='%".$search."%') AND ";
                    }
                    $sql .= "u.id NOT IN (SELECT id FROM lista_relevancia_1) AND u.id NOT IN (SELECT id FROM lista_relevancia_2)";
                    $sql .= " LIMIT ".$offset2;
                    $sql .= ")";
                    $sql .= ") total;";
            
                    break;
            
                }
        }
        
        
//        $sql = "SELECT users.* FROM users";        
//        if ($Filtrar)
//        {
//            $sql .= " WHERE name LIKE '%".$search."%' OR username='%".$search."%'";
//        }
//        $sql .= " LIMIT ".$start.",".$maxResults;
//        $sql .= ";";
        $json["debug"]["sql"][] = $sql;
        $json["debug"]["start"] = $start;
        $json["debug"]["off2"] = $offset2;
        
        $result = $mysql->query($sql);
//         $json["debug"]["rresult"][] = $result->fetch_row();
        if ($result !== false)
        {
            while ($row = $result->fetch_row()) 
            {
                 /*
                * $row[0] = Id
                * $row[1] = Name
                * $row[2] = Username
                */
                $response[] = array('id'=> ($row[0]), 'name'=> ($row[1]), 'username'=>($row[2]));
           // $json["debug"]["row0"][] =$row[0];
            }
        }
        
//        if (($handle = fopen($filename, "r")) !== FALSE) 
//        {         
//           
//            /*
//             * Verifica se o arquivo chegou ao fim OU se atigingiu o limite de resultados por pagina
//             */
//            fseek($handle,$start);
//             //$json["debug"][] = $start;
//            while (($data = fgets($handle)) !== FALSE && $registros < $maxResults) 
//            {            
//               
//                
//                $json["debug"][] = $data;
//                $data = explode(',',$data);
//                if(!$Filtrar || ( (strpos($data[1],$search) !== false) ||(strpos($data[2],$search) !== false) ) ) 
//                {
////                    $row++;
//                   
////                    if($row > $start && $registros < $maxResults)
//                    if($registros < $maxResults && count($data) ==3 )
//                    {
//                        $response[$registros] = array('id'=> ($data[0]), 'name'=> ($data[1]), 'username'=>($data[2]));
//                        $registros++;            
//                    }
//
//                }
//            }
//        }
        /*
         * conta o numero de linhas do arquivo
         */
        
//        fclose($handle);
        /*
         * Contagem total de linhas
         */
        if ($totalRows === 0){
            $sql = "SELECT count(*) FROM users";
            $sql .= ";";
            $result = $mysql->query($sql);
            while ($row = $result->fetch_row()) 
            {
                $totalRows = $row[0];
            }
            $json["debug"]["sql"][] = $sql;
//            $file = new SplFileObject($filename, 'r');
//            $file->seek(PHP_INT_MAX);
//            $totalRows = $file->key() +1;
            $_SESSION["totalRows"] = $totalRows;
        }
        if($recordsFiltered ===0)
        {
            $recordsFiltered = $totalRows;
        }

        $json["data"] = $response;
        $json["recordsTotal"] = $totalRows;
        $json["recordsFiltered"] = $recordsFiltered;
    }
    else
    {
        echo "acesso nao autorizado";
    }

}catch(Exception $e){
    $json["error"] = $e;    
}
if( empty($response))
{
    
    $json["error"] = "Nao foram encontrados de acordo com a filtragem!";
//    $json["debug"] = "Nao foram encontrados de acordo com a filtragem!";
}
$mysql->close();
echo utf8_encode(json_encode($json));
 


