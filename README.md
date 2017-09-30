Luiz Henrique Motta de Souza

# Backend
Codigo escrito em PHP sem a utilizacao de nenhum framework
 * Todo o processamento esta no arquivo public/data/paginacao.php
 * alguns comentarios no arquivo public/data/paginacao.php sao resultados de teste enquanto estava testando o codigo para leitura diretamente do arquivo users.csv

# Frontend
bibliotecas utilizadas:
 - JQuery
 - JQueryUI
 - jquery datatables

# Banco de dados
arquivos public/data/criaListaRelevancia.php e public/data/csvToSql.php foram utilizados para gerar o banco de dados porem, por motivos de seguranca devem ser feitos alguns ajustes antes de executa-los (para ter a saida em um arquivo .sql ou executar direto no banco alguns blocos de codigo devem ser comentado/descomentado)

* lista_relevancia_1.txt e lista_relevancia_2.txt foram movidos para a pasta public/data/ 
* users.csv devera ser colocado na pasta /public/data/ para executar o arquivo csvToSql.php, caso precise gerar o banco de dados
 - os datafiles do MySQL devem ficar no host no diretorio /storage/docker/mysql-datadir/ (ou configurar no arquivo docker-compose.yml


