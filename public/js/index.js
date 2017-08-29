$(document).ready(function(){   
    
    $("#dataGrid").DataTable({
        "lengthMenu":[15],
        "serverSide": true,
        "processing": true,
        "ordering": false,
        "pageLength": 15,
        //"pagingType": "full_numbers",
        "searchDelay":1000,
        "columns": [
            { 
                "data": "id",
                "searchable":false
            },
            { 
                "data": "name" 
            },
            { 
                "data": "username" 
            }
        ],
        
        "ajax": {
        "url": "data/paginacao.php",
        "type": "GET"
      }
    });
         
    $("#dataGrid_processing").addClass("ui-state-active");
});