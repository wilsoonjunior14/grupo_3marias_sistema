<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href='https://fonts.googleapis.com/css?family=Quattrocento Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>

    <body>
        <?php 
            $MONTHS = [
                "01" => "Janeiro",
                "02" => "Fevereiro",
                "03" => "Março",
                "04" => "Abril",
                "05" => "Maio",
                "06" => "Junho",
                "07" => "Julho",
                "08" => "Agosto",
                "09" => "Setembro",
                "10" => "Outubro",
                "11" => "Novembro",
                "12" => "Dezembro"
            ];
        ?>
        <style>
            #title {
                position: absolute;
                top: 50px;
                right: 0;
                margin-right: 12px;
                font-family: 'Times New Roman', Times, serif;
                font-size: 25px;
                font-weight: bold;
                width: 100%;
                text-align: center;
            }
            #container {
                position: absolute;
                top: 80px;
                margin: 12px;
                width: 100%;
            }
            .row {
                margin-right: 15px;
            }
            .row-colored {
                border-left: 10px solid gold;
                border-bottom: 1px solid gray;
            }
            .no-bottom-border {
                border-bottom: none !important;
                text-align: justify;
                font-size: 11px;
            }
            .row span {
                font-size: 11px;
            }
            .row b {
                font-size: 12px;
            }
            .list-no-style {
                list-style: none;
                margin: 0;
                padding: 0;
                text-align: justify;
            }

            .container-table {
                padding-left: 25px;
            }

            .container-table .row div{
                border: 1px solid black;
                padding: 3px;
            }

            @media print {
                
            }
        </style>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 id="title">{{ $title }}</h1>
                </div>
            </div>
        </div>
        <br></br>
        
        <div id="container" class="container-fluid">
            <div class="row">
                <div class="col-12 container-table">
                    <div class="row">
                        <div class="col-2">
                            NOME
                        </div>
                        <div class="col-7">
                            {{ mb_strtoupper($contract->proposal->client->name, 'UTF-8') }}
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            CPF
                        </div>
                        <div class="col-7">
                            {{ mb_strtoupper($contract->proposal->client->cpf, 'UTF-8') }}
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            ENDEREÇO
                        </div>
                        <div class="col-7">
                            {{ mb_strtoupper($contract->proposal->client->address, 'UTF-8') }}
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            CIDADE/UF
                        </div>
                        <div class="col-7">
                            {{ mb_strtoupper($contract->proposal->client->city_name, 'UTF-8') }}/{{ mb_strtoupper($contract->proposal->client->state_acronym, 'UTF-8') }}
                        </div>
                        <div class="col-3"></div>
                        <div class="col-2">
                            EMAIL
                        </div>
                        <div class="col-7">
                            {{ $contract->engineer->email }}
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
                <div class="col-12" style="margin-top: 10px;">
                    <h5>À Secretaria de Infraestrutura, Serviços Públicos e Meio Ambiente de {{ $contract->proposal->client->city_name, 'UTF-8' }}</h5>
                </div>
                <div class="col-12">
                    <h5>Assunto: Solicitação de Alvará de Construção</h5>
                </div>
                <div class="col-12">
                    <p>Por meio deste, venho requerer, na forma da Legislação vigente: Art. 6o da Lei 276/03, Alvará de Construção, submetendo para apreciação os documentos abaixo listados:
                    </p>
                </div>
                <div class="col-12">
                    <ul>
                        <li>Requerimento;</li>
                        <li>Documento de Propriedade;</li>
                        <li>Anotação de Responsabilidade Técnica - ART;</li>
                        <li><h6>Projetos:</h6>
                            <ul>
                                <li>Memorial Descritivo;</li>
                                <li>Planta da Situação;</li>
                                <li>Planta Baixa;</li>
                                <li>Cortes Esquemáticos;</li>
                                <li>Detalhes da Fachada;</li>
                                <li>Projeto de Instalações hidráulicas e sanitárias;</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-12">
                    <p>Nesses termos, peço deferimento.</p>
                </div>
                <div class="col-8"></div>
                <div class="col-4">
                <?php
                    $month = date('m');
                    $currentMonth = $MONTHS["$month"];
                ?>
                {{ $contract->proposal->client->city_name, 'UTF-8' }}, {{ date('d') }} de {{ $currentMonth }} de {{ date('Y') }}
                </div>
                <div class="col-12">
                    Atenciosamente, 
                </div>
                <div class="col-12 no-bottom-border">
                    <div class="row" style="margin-top: 50px;">
                        <div class="col-3"></div>
                        <div class="col-6 text-center">
                            <div class="row" style="border-top: 1px solid gray;">
                                <div class="col-12">{{ mb_strtoupper($contract->proposal->client->name, 'UTF-8') }}</div>
                                <div class="col-12">CPF: {{ mb_strtoupper($contract->proposal->client->cpf, 'UTF-8') }}</div>
                                <div class="col-12"><b>REQUERENTE</b></div>
                            </div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>