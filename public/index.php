<?php 
session_start();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo "PicPay teste Backend"?></title>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/jquery-ui.js" type="text/javascript"></script>
       
        <script type="text/javascript" src="https://cdn.datatables.net/v/ju/dt-1.10.15/r-2.1.1/datatables.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/ju/dt-1.10.15/r-2.1.1/datatables.css"/>
 
                
        <link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.structure.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui.theme.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="jsGrid">
            <table id="dataGrid" class="display">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Id</td>
                        <td>Name</td>
                        <td>Username</td>
                    </tr>
                </tbody>
        </table>
        </div>
        
        
        <script src="js/index.js" type="text/javascript"></script>
    </body>
</html>
