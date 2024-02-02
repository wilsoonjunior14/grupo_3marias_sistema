<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>

    <body>
        <style>
            #title {
                position: absolute;
                top: 120px;
                right: 0;
                margin-right: 12px;
                font-family: 'Times New Roman', Times, serif;
                font-size: 25px;
                font-weight: bold;
                width: 100%;
                text-align: center;
            }
            #logo {
                position: absolute;
                top: 12px;
                left: calc(50vw - 180px);
            }
            #container {
                position: absolute;
                top: 120px;
                margin: 12px;
                width: 100%;
            }
            .row {
                margin-right: 20px;
            }
            .row-colored {
                border-left: 10px solid gold;
                border-bottom: 1px solid gray;
            }
            .no-bottom-border {
                border-bottom: none !important;
            }
            .row span {
                font-size: 11px;
            }
            .row b {
                font-size: 12px;
            }
        </style>

        <h1 id="title">{{ $title }}</h1>
        <img id="logo" height="80" src="/img/logo_document.png" />
        
        <div id="container" class="container-fluid">
        <div class="row" style="margin-top: 50px">
            <div class="col-12 row-colored no-bottom-border"><b>1. CLÁUSULA PRIMEIRA - DAS PARTES</b></div>
            <div class="col-12 row-colored"><b>Dados Pessoais dos Contratantes</b></div>
        </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Nome Completo</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->name) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Estado Civil</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->state) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Naturalidade</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->naturality) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Nacionalidade</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->nationality) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Endereço Residencial</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->address) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Complemento</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->complement) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>Bairro</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->neighborhood) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CEP</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->zipcode) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Cidade</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->city_name) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>UF</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->state_acronym) }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>RG</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->rg) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Òrgão Expedidor/UF</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->rg_organ) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CPF</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->cpf) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Profissão</b> </br>
                    <span>{{ strtoupper($contract->proposal->client->ocupation) }}</span>
                </div>
            </div>
            @if (strcmp($contract->proposal->client->state, "Casado"))
            <div style="height: 10px" class="row row-colored">
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Nome Completo</b> </br>
                    <span>{{ $contract->proposal->client->name_dependent }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Estado Civil</b> </br>
                    <span>{{ $contract->proposal->client->state }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Naturalidade</b> </br>
                    <span>{{ $contract->proposal->client->naturality_dependent }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Nacionalidade</b> </br>
                    <span>{{ $contract->proposal->client->nationality_dependent }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Endereço Residencial</b> </br>
                    <span>{{ $contract->proposal->client->address }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Complemento</b> </br>
                    <span>{{ $contract->proposal->client->complement }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>Bairro</b> </br>
                    <span>{{ $contract->proposal->client->neighborhood }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CEP</b> </br>
                    <span>{{ $contract->proposal->client->zipcode }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Cidade</b> </br>
                    <span>{{ $contract->proposal->client->city_name }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>UF</b> </br>
                    <span>{{ $contract->proposal->client->state_acronym }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>RG</b> </br>
                    <span>NOME</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Òrgão Expedidor/UF</b> </br>
                    <span>CPF</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CPF</b> </br>
                    <span>CPF</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Profissão</b> </br>
                    <span>CPF</span>
                </div>
            </div>
            @endif

            <div class="row row-colored no-bottom-border" style="margin-top: 10px"></div>
            <div class="row">
                <div class="col-12 row-colored"><b>Dados da Contratada</b></div>
            </div>
                <div class="row">
                    <div class="col-9 row-colored">
                        <b>Razão Social</b> </br>
                        <span>{{ $enterprise->name }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CNPJ</b> </br>
                        <span>{{ $enterprise->cnpj }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 row-colored">
                        <b>Endereço Residencial</b> </br>
                        <span>{{ $enterprise->address }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Complemento</b> </br>
                        <span>{{ $enterprise->complement }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ $enterprise->neighborhood }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ $enterprise->zipcode }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ $enterprise->city_name }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ $enterprise->state_acronym }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Representado Por</b> </br>
                        <span>{{ $owner->name }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CPF</b> </br>
                        <span>{{ $owner->cpf }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>RG</b> </br>
                        <span>{{ $owner->rg }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Òrgão Expedidor</b> </br>
                        <span>{{ $owner->rg_organ }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Nacionalidade</b> </br>
                        <span>{{ $owner->nationality }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Naturalidade/UF</b> </br>
                        <span>{{ $owner->naturality }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Estado Civil</b> </br>
                        <span>{{ $owner->state }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Profissão</b> </br>
                        <span>{{ $owner->ocupation }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 row-colored">
                        <b>Endereço Residencial</b> </br>
                        <span>{{ $owner->address }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ $owner->neighborhood }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ $owner->city_name }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ $owner->state_acronym }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ $owner->zipcode }}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 50px">
                    <div class="col-12 row-colored no-bottom-border"><b>2. CLÁUSULA SEGUNDA - DO OBJETO CONTRATO</b></div>
                    <div class="col-12 row-colored"><b>2.1 Dados do Objeto Contrato</b></div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Tipo de Obra</b> </br>
                        <span>{{ strtoupper($contract->building_type) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Descrição da Obra</b> </br>
                        <span>{{ strtoupper($contract->description) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Metros Quadrados da Obra</b> </br>
                        <span>{{ strtoupper($contract->meters) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Endereço</b> </br>
                        <span>{{ strtoupper($contract->address->address) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ strtoupper($contract->address->neighborhood) }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ strtoupper($contract->address->zipcode) }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ strtoupper($contract->address->city_name) }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ strtoupper($contract->address->state_name) }}</span>
                    </div>
                </div>
                <div class="row row-colored no-bottom-border" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-12 row-colored no-bottom-border">
                        2.2. Por este instrumento particular e na melhor forma de direito, os CONTRATANTES acordam os serviços da CONTRATADA para elaborar os projetos de arquitetura e engenharia, bem como a documentação técnica de financiamento bancário e executar as obras de construção de uma residência unifamiliar com 62,57 M² (Sessenta E Dois Metros Quadrados E Cinquenta E Sete Decímetros Quadrados) de área total, conforme descrita e especificada no projeto a ser desenvolvido e posteriormente anexo a este contrato para todos os fins de direito.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.3. O Memorial Descritivo anexo a este contrato contém todas as especificações de materiais, necessários ao desenvolvimento do projeto, bem como a execução da obra, definindo assim o escopo, a qualidade e o custo da obra a ser construída.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.3.1. O Memorial Descritivo prevê a quantidade de cômodos, e as especificações de acabamento de cada ambiente a ser construído. 
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.4. Qualquer alteração do escopo do objeto, previamente definido através do Memorial Descritivo em Anexo, e que implique no aumento do valor contratado e acertado neste ato, deverá ser negociada entre as partes, a fim de garantir a atualização dos valores a serem acrescidos, e emitido o aditivo de contrato para todos os fins.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.5. O projeto a ser desenvolvido será discutido e desenvolvido no decorrer deste contrato, seguindo as premissas contidas neste documento.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.6. A área total indicada na cláusula 2.2.inclui as áreas de projeção horizontal das paredes que compõe a edificação, com exceção das calçadas de proteção no entorno da edificação.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.7. Este contrato inclui a execução de muros e calçadas externas ao imóvel.
                    </div>
                    <br></br>
                    <div class="col-12 row-colored no-bottom-border">
                        2.8. Os serviços a serem executados são:
                        <ul>
                            <li>PROJETO DE ARQUITETURA LEGAL (PREFEITURA): Projeto para aprovação junto à Prefeitura do Município de Ibiapina, atendendo as exigências legais;</li>
                            <li>PROJETO ESTRUTURAL: A "solução definitiva do projeto estrutural a nível de execução" é o plano detalhado para a construção da estrutura de um edifício, incluindo a escolha de materiais, tipo de fundação e o design dos elementos estruturais como colunas e vigas. O objetivo é garantir uma estrutura segura, durável e conforme às normas de construção;</li>
                            <li>PROJETO ELÉTRICO: A solução definitiva do projeto elétrico a nível de execução" é o plano final para instalar o sistema elétrico, incluindo a estratégia geral (partido adotado) e o detalhamento de como cabos, tomadas, e equipamentos elétricos serão organizados. O objetivo é garantir segurança, eficiência e conformidade com normas técnicas;</li>
                            <li>PROJETO HIDROSSANITÁRIO: Um projeto hidrossanitário a nível de execução é um plano detalhado para instalar e gerenciar os sistemas de água e esgoto. Ele define a estratégia geral do sistema (partido adotado) e detalha como as tubulações, conexões e equipamentos serão organizados e operados para garantir eficiência, conformidade com normas e conforto para os usuários;</li>
                            <li>DOCUMENTAÇÃO TÉCNICA PARA FINANCIAMENTO BANCÁRIO: Elaboração de Planilha Orçamentária, Cronograma Físico-Financeiro, Memorial Descritivo, e demais documentos técnicos necessários exigidos pelas instituições financeiras, para fins de financiamento bancário;</li>
                            <li>CONSTRUÇÃO DA OBRA: Proceder com a construção total da obra, conforme preço global e serviços previstos na planilha orçamentária que será anexa a esse contrato. Além disso deverá seguir o Memorial Descritivo deste contrato;</li>
                        </ul>
                    </div>
                    <br></br>
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