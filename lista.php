<?php

    $handle = fopen("pacientes.csv", "r");

    $header = fgetcsv($handle, 1000, ",");
    $header = array_map("utf8_decode", $header);
    while ($row = fgetcsv($handle, 1000, ",")) {
        $patients[] = array_combine($header, $row);
    }

    foreach($patients as $patient){
        $patient = array_map("utf8_encode", $patient );
        $patients_utf8[] = $patient;
    }

    unset($header[11]); // remove last column, first row from array (frkPlanoSaude) - header
    unset($patients[11]); // remove last column from array (frkPlanoSaude) - content

    fclose($handle);

    function formatCnpjCpf($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);
        
        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        } 
        
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistemashosp - Teste</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="shortcut icon" href="#">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" ></script>
        <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js" ></script>
       <style>
           #load{
            width:100%;
            height:100%;
            position:fixed;
            z-index:9999;
            background:url("loading.gif") no-repeat center center rgba(0,0,0,0.25)
    
            }
            

            tr td:last-child {
                width: 1%;
                white-space: nowrap;
            }
       </style>
    </head>
    <body>
    <div id="load">
       Aguarde, carregando pacientes...
    </div>  
    <div id="contents">   
        <div class="container">
            <div class="row">
                <div class="col">
                    <table id="example" class="table table-hover table-fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Sobrenome</td>
                                <th scope="col">Email</th>
                                <th scope="col">Data de nascimento</th>
                                <th scope="col">Gênero</th>
                                <th scope="col">Tipo Sanguineo</th>
                                <th scope="col">Endereço</th>
                                <th scope="col">Cidade</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Cep</th>
                                <th scope="col">CPF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($patients_utf8 as $key => $patient){

                                    echo    '<tr>
                                                <td>'.$patient['nome'].'</td>
                                                <td>'.$patient['sobrenome'].'</td>
                                                <td>'.strtolower($patient['email']).'</td>
                                                <td>'.$patient['datanascimento'].'</td>
                                                <td>'.$patient['genero'].'</td>
                                                <td>'.$patient['tiposanguineo'].'</td>
                                                <td>'.$patient['endereco'].'</td>
                                                <td>'.$patient['cidade'].'</td>
                                                <td>'.$patient['estado'].'</td>
                                                <td>'.$patient['cep'].'</td>
                                                <td>'.formatCnpjCpf($patient['cpf']).'</td>
                                            </tr>';
                                                        
                                                    
                                            }  
                                ?>
                        </tbody>
                        <tfoot>
                        <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Sobrenome</td>
                                <th scope="col">Email</th>
                                <th scope="col">Data de nascimento</th>
                                <th scope="col">Gênero</th>
                                <th scope="col">Tipo Sanguineo</th>
                                <th scope="col">Endereço</th>
                                <th scope="col">Cidade</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Cep</th>
                                <th scope="col">CPF</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- Latest compiled and minified JavaScript -->
        <script >
            document.onreadystatechange = function () {
                var state = document.readyState
                if (state == 'interactive') {
                    document.getElementById('contents').style.visibility="hidden";
                    console.log(document.getElementById('contents'));
                    $("#contents").fadeIn();
                } else if (state == 'complete') {
                    setTimeout(function(){
                        document.getElementById('inter  active');
                        document.getElementById('load').style.visibility="hidden";
                        document.getElementById('contents').style.visibility="visible";
                        
                    },1000);
                }
            }
            $(document).ready(function () {
                $('#example').DataTable({
                    "pagingType": "numbers", // [
                    "processing": true,
                    "pageLength": 7,
                    "oLanguage": {
                        "sSearch": "Buscar: ",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sInfo":           "Mostrando _START_ até _END_ de _TOTAL_ registros"
                    }
                    });
                $('.dataTables_length').addClass('bs-select');
            });
        </script>           
        
    </body>
</html>