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
            body {
                font-family: 'Quattrocento Sans';
            }
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
        </style>

        <div class="container">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <img id="logo" height="80" src="/img/logo_document.png" />
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h1 id="title">{{ $title }}</h1>
                </div>
            </div>
        </div>

        <br></br>
        <br></br>

        <?php
        $countBuyers = strcmp($contract->proposal->client->state, "Casado") === 0 ? 2 : 1;
        if ($countBuyers === 2) {
            $subjectArticle = "Os";
            $subject = "Contratantes";
        } else if (strcmp($contract->proposal->client->sex, "Masculino") === 0) {
            $subjectArticle = "O";
            $subject = "Contratante";
        } else {
            $subjectArticle = "A";
            $subject = "Contratante";
        }
        ?>
        
        <div id="container" class="container-fluid">
        <div class="row" style="margin-top: 50px">
            <div class="col-12 row-colored no-bottom-border"><b>1. CLÁUSULA PRIMEIRA - DAS PARTES</b></div>
            <div class="col-12 row-colored"><b>Dados Pessoais d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}</b></div>
        </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Nome Completo</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->name, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Estado Civil</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->state, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Naturalidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->naturality, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Nacionalidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->nationality, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Endereço Residencial</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->address, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Complemento</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->complement, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>Bairro</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->neighborhood, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CEP</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->zipcode, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Cidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->city_name, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>UF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->state_acronym, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>RG</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->rg, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Òrgão Expedidor/UF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->rg_organ, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CPF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->cpf, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Profissão</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->ocupation, 'UTF-8') }}</span>
                </div>
            </div>
            @if (strcmp($contract->proposal->client->state, "Casado") === 0)
            <div style="height: 10px" class="row row-colored">
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Nome Completo</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->name_dependent, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Estado Civil</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->state, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Naturalidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->naturality_dependent, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Nacionalidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->nationality_dependent, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-9 row-colored">
                    <b>Endereço Residencial</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->address, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Complemento</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->complement, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>Bairro</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->neighborhood, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CEP</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->zipcode, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Cidade</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->city_name, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>UF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->state_acronym, 'UTF-8') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-3 row-colored">
                    <b>RG</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->rg_dependent, ÚTF-8) }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Òrgão Expedidor/UF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->rg_organ_dependent, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>CPF</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->cpf_dependent, 'UTF-8') }}</span>
                </div>
                <div class="col-3 row-colored">
                    <b>Profissão</b> </br>
                    <span>{{ mb_strtoupper($contract->proposal->client->ocupation_dependent, 'UTF-8') }}</span>
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
                        <span>{{ mb_strtoupper($enterprise->name, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CNPJ</b> </br>
                        <span>{{ mb_strtoupper($enterprise->cnpj, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 row-colored">
                        <b>Endereço Residencial</b> </br>
                        <span>{{ mb_strtoupper($enterprise->address, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Complemento</b> </br>
                        <span>{{ mb_strtoupper($enterprise->complement, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ mb_strtoupper($enterprise->neighborhood, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ mb_strtoupper($enterprise->zipcode, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ mb_strtoupper($enterprise->city_name, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ mb_strtoupper($enterprise->state_acronym, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Representado Por</b> </br>
                        <span>{{ mb_strtoupper($owner->name, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CPF</b> </br>
                        <span>{{ mb_strtoupper($owner->cpf, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>RG</b> </br>
                        <span>{{ mb_strtoupper($owner->rg, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Òrgão Expedidor</b> </br>
                        <span>{{ mb_strtoupper($owner->rg_organ, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Nacionalidade</b> </br>
                        <span>{{ mb_strtoupper($owner->nationality, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Naturalidade/UF</b> </br>
                        <span>{{ mb_strtoupper($owner->naturality, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Estado Civil</b> </br>
                        <span>{{ mb_strtoupper($owner->state, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Profissão</b> </br>
                        <span>{{ mb_strtoupper($owner->ocupation, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 row-colored">
                        <b>Endereço Residencial</b> </br>
                        <span>{{ mb_strtoupper($owner->address, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ mb_strtoupper($owner->neighborhood, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ mb_strtoupper($owner->city_name, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ mb_strtoupper($owner->state_acronym, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ mb_strtoupper($owner->zipcode, 'UTF-8') }}</span>
                    </div>
                </div>


                <div class="row" style="margin-top: 50px">
                    <div class="col-12 row-colored no-bottom-border"><b>2. CLÁUSULA SEGUNDA - DO OBJETO CONTRATO</b></div>
                    <div class="col-12 row-colored"><b>2.1 Dados do Objeto Contrato</b></div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Tipo de Obra</b> </br>
                        <span>{{ mb_strtoupper($contract->building_type, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Descrição da Obra</b> </br>
                        <span>{{ mb_strtoupper($contract->description, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Metros Quadrados da Obra</b> </br>
                        <span>{{ mb_strtoupper($contract->meters, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 row-colored">
                        <b>Endereço</b> </br>
                        <span>{{ mb_strtoupper($contract->address->address, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 row-colored">
                        <b>Bairro</b> </br>
                        <span>{{ mb_strtoupper($contract->address->neighborhood, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>CEP</b> </br>
                        <span>{{ mb_strtoupper($contract->address->zipcode, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>Cidade</b> </br>
                        <span>{{ mb_strtoupper($contract->address->city_name, 'UTF-8') }}</span>
                    </div>
                    <div class="col-3 row-colored">
                        <b>UF</b> </br>
                        <span>{{ mb_strtoupper($contract->address->state_name, 'UTF-8') }}</span>
                    </div>
                </div>
                <div class="row row-colored no-bottom-border" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>2.2. Por este instrumento particular e na melhor forma de direito, {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} acordam os serviços da CONTRATADA para elaborar os projetos de arquitetura e engenharia, bem como a documentação técnica de financiamento bancário e executar as obras de construção de uma {{ $contract->building_type }} com {{ $contract->meters }}, conforme descrita e especificada no projeto a ser desenvolvido e posteriormente anexo a este contrato para todos os fins de direito.</li>
                            <li>2.3. O Memorial Descritivo anexo a este contrato contém todas as especificações de materiais, necessários ao desenvolvimento do projeto, bem como a execução da obra, definindo assim o escopo, a qualidade e o custo da obra a ser construída.</li>
                            <li>2.3.1. O Memorial Descritivo prevê a quantidade de cômodos, e as especificações de acabamento de cada ambiente a ser construído.</li>
                            <li>2.4. Qualquer alteração do escopo do objeto, previamente definido através do Memorial Descritivo em Anexo, e que implique no aumento do valor contratado e acertado neste ato, deverá ser negociada entre as partes, a fim de garantir a atualização dos valores a serem acrescidos, e emitido o aditivo de contrato para todos os fins.</li>
                            <li>2.5. O projeto a ser desenvolvido será discutido e desenvolvido no decorrer deste contrato, seguindo as premissas contidas neste documento.</li>
                            <li>2.6. A área total indicada na cláusula 2.2.inclui as áreas de projeção horizontal das paredes que compõe a edificação, com exceção das calçadas de proteção no entorno da edificação.</li>
                            <li>2.7. Este contrato inclui a execução de muros e calçadas externas ao imóvel.</li>
                            <li>2.8. Os serviços a serem executados são:
                                <ul style="list-style: lower-norwegian;">
                                    <li>PROJETO DE ARQUITETURA LEGAL (PREFEITURA): Projeto para aprovação junto à Prefeitura do Município de Ibiapina, atendendo as exigências legais; </li>
                                    <li>PROJETO ESTRUTURAL: A "solução definitiva do projeto estrutural a nível de execução" é o plano detalhado para a construção da estrutura de um edifício, incluindo a escolha de materiais, tipo de fundação e o design dos elementos estruturais como colunas e vigas. O objetivo é garantir uma estrutura segura, durável e conforme às normas de construção.</li>
                                    <li>PROJETO ELÉTRICO: A solução definitiva do projeto elétrico a nível de execução" é o plano final para instalar o sistema elétrico, incluindo a estratégia geral (partido adotado) e o detalhamento de como cabos, tomadas, e equipamentos elétricos serão organizados. O objetivo é garantir segurança, eficiência e conformidade com normas técnicas.</li>
                                    <li>PROJETO HIDROSSANITÁRIO: Um projeto hidrossanitário a nível de execução é um plano detalhado para instalar e gerenciar os sistemas de água e esgoto. Ele define a estratégia geral do sistema (partido adotado) e detalha como as tubulações, conexões e equipamentos serão organizados e operados para garantir eficiência, conformidade com normas e conforto para os usuários.</li>
                                    <li>DOCUMENTAÇÃO TÉCNICA PARA FINANCIAMENTO BANCÁRIO: Elaboração de Planilha Orçamentária, Cronograma Físico-Financeiro, Memorial Descritivo, e demais documentos técnicos necessários exigidos pelas instituições financeiras, para fins de financiamento bancário;</li>
                                    <li>CONSTRUÇÃO DA OBRA: Proceder com a construção total da obra, conforme preço global e serviços previstos na planilha orçamentária que será anexa a esse contrato. Além disso deverá seguir o Memorial Descritivo deste contrato.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>3. CLÁUSULA TERCEIRA - DA FORMA DE EXECUÇÃO E OBRIGAÇÕES DAS PARTES</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <?php $bank = "" ?>
                        @foreach ($contract->proposal->payments as $payment)
                            @if (strcmp("Banco", $payment->source) === 0)
                                <?php $bank = $payment->bank; ?>
                            @endif
                        @endforeach
                        <ul class="list-no-style">
                            <li>3.1. A elaboração dos projetos e documentação técnica observará as seguintes premissas: </li>
                            <li>3.1.1. O engenheiro BHRENO DE OLIVEIRA PONTES, registrado no CREA sob o nº 45211, é contratado em comum acordo pela CONTRATADA e {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} para se responsabilizar tecnicamente pela aprovação do projeto de construção junto à Prefeitura do Município de Ibiapina. Além disso, ele acompanhará o andamento da obra, 
                                realizando as medições necessárias que serão enviadas ao banco {{ $bank }} para fiscalização por um engenheiro credenciado da mesma. Todas as exigências, ajustes e esclarecimentos requisitados pela Prefeitura Municipal de Ibiapina ou pelo banco {{ $bank }} serão prontamente atendidos por ele. 
                                Os honorários deste engenheiro serão pagos pela CONTRATADA.</li>
                            <li>3.1.2. Fornecer para {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} em formato digital (em extensão.pdf) um original de cada uma das plantas, e detalhes relativos ao desenvolvimento do projeto;</li>
                            <li>3.1.3. A CONTRATADA caberá coordenar os cálculos complementares do projeto arquitetônico incluindo supervisionar o desenvolvimento dos planos de estrutura, instalações hidráulicas, sanitárias, elétricas, telecomunicações, climatização e paisagismo, sempre que estes elementos façam parte do projeto, garantindo sua integração e conformidade com as normas técnicas.</li>
                            <li>3.1.4. A CONTRATADA deve elaborar os serviços objetivados no presente contrato, em obediência as normas e especificações técnicas vigentes, responsabilizando-se pelos serviços prestados, na forma da legislação em vigor;</li>
                            <li>3.1.5. {{ $subjectArticle }} {{ strtoupper($subject) }} deverão pagar os honorários da CONTRATADA, referentes a projetos modificativos, e alterações de projetos das fases já executadas, decorrentes das solicitações feitas pel{{ strtolower($subjectArticle) }} {{strtoupper($subject)}}, independente das razões que o motivaram. Esses honorários serão cobrados conforme Cláusula 5.9.do presente contrato.</li>
                            <li>3.2. A construção da obra observará as seguintes premissas:</li>
                            <li>3.2.1. As obras serão executadas pela CONTRATADA, com equipamentos, ferramentas e maquinários próprios ou locados às suas expensas, prestando pessoalmente os serviços ou contratando de mão de obra especializada, tendo este vínculo exclusivamente com o mesmo, a qual responderá pelo pagamento dos salários bem como todos os encargos tributários, cíveis ou trabalhistas decorrentes da contratação da mão de obra.</li>
                            <li>3.2.2. A CONTRATADA será responsável ainda pela aquisição dos materiais a serem empregados na obra, já inclusos no valor deste contrato, os quais obedecerão as especificações e qualidade descritas no Projeto e Memorial Descritivo anexos.</li>
                            <li>3.2.3. Será a CONTRATADA a única responsável por eventuais danos e prejuízos causados a terceiros em decorrência da execução da obra a seu cargo.</li>
                            <li>3.2.4. Poderá {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, ou pessoa por eles autorizada, visitar as obras em qualquer dia ou horário, a fim de proceder com o seu acompanhamento técnico.</li>
                            <li>3.2.5. {{ $subjectArticle }} {{ strtoupper($subject) }}, em hipótese alguma, {{ strpos($subjectArticle, "s") === true ? "poderão" : "poderá" }} intervir na gestão, controle e execução da mão de obra, serviços e materiais a serem aplicados na obra, a qualquer momento, a fim de garantir a perfeita execução da obra.</li>
                            <li>3.2.6. Cabe a CONTRATADA a total gestão, controle e execução da mão de obra, serviços e materiais a serem aplicados na obra, o qual deverá fazer seguindo fielmente os projetos aprovados e o Memorial Descritivo anexo a este contrato.</li>
                            <li>3.2.7. Todas as despesas oriundas de análises, aprovações e registros dos serviços contratados são de responsabilidade da CONTRATADA, até a conclusão da obra, tais como: Taxas do CREA, Taxas de Prefeitura, Taxas de Cartório, Taxas das Instituições Financeiras, Emissão de Alvará de Construção, Taxas de Recolhimento INSS da Obra, Taxa Emissão de Habite-se, ISS, ITBI e Averbações.</li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>4. CLÁUSULA QUARTA - DOS PRAZOS</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>4.1. Os serviços ora contratados serão executados nos prazos abaixo especificados:
                            <li>4.1.1. ESTUDO PRELIMINAR: 5 (cinco) dias após a assinatura do contrato;</li>
                            <li>4.1.2. PROJETO DE ARQUITETURA LEGAL (PREFEITURA): 15 (quinze) dias após a aprovação pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} do estudo preliminar;</li>                          
                            <li>4.1.3. PROJETO ESTRUTURAL: 15 (quinze) dias após a aprovação pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} do projeto de arquitetura legal;</li>
                            <li>4.1.4. PROJETO ELÉTRICO: 15 (quinze) dias após a aprovação pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} do projeto de arquitetura legal;</li>
                            <li>4.1.5. PROJETO HIDROSSANITÁRIO: 15 (quinze) dias após a aprovação pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} do projeto de arquitetura legal;</li>
                            <li>4.1.6. DOCUMENTAÇÃO TÉCNICA PARA FINANCIAMENTO BANCÁRIO: 15 (quinze) dias após a aprovação pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} do projeto de arquitetura legal;</li>
                            <li>4.1.7. CONSTRUÇÃO DA OBRA: A obra será iniciada 10 (dez) dias após a assinatura do contrato de financiamento junto ao banco {{ $bank }}. E será executada conforme cronograma físico-financeiro de financiamento imobiliário apresentado e posteriormente anexo a este contrato.</li>
                            <li>4.2. Os prazos da Clausula 4.1 constituem os mínimos necessários para o desenvolvimento técnico dos serviços, podendo no entanto, serem dilatados a pedido da CONTRATADA.</li>
                            <li>4.3. Não serão contados os dias em que o projeto ficar retido pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, para apreciação;</li>
                            <li>4.4. Os prazos acima não se vinculam aos prazos necessários para aprovação junto aos órgãos competentes, podendo, entretanto, a CONTRATADA desenvolver, paralelamente e estes trâmites, as etapas posteriores.</li>
                            <li>4.5. {{ $subjectArticle }} {{ strtoupper($subject) }} devem agir prontamente em todas as ações que dependerem exclusivamente deles para o avanço do projeto e documentações deste contrato. Caso eles atrasem ou causem dificuldades, a CONTRATADA não será responsável por atrasos nos serviços e pode cancelar o contrato. Se {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} falharem em colaborar adequadamente, especialmente na fase de projetos e documentos, terão que pagar R$ 10.000,00 (Dez Mil Reais) à CONTRATADA, que pode decidir encerrar o contrato se necessário sem nenhuma penalidade.</li>
                            <li>4.6. {{ $subjectArticle }} {{ strtoupper($subject) }} terão 5 (cinco) dias úteis para a aprovação ou solicitação de eventuais alterações a contar da entrega de cada etapa.</li>
                            <li>4.7. O presente contrato terá início na data da sua assinatura, e seguirá os prazos estabelecidos na cláusula 4.1.</li>
                            <li>4.8. O prazo de conclusão poderá ser prorrogado em caso de comprovado caso fortuito ou de força maior, como guerra, calamidade pública, suspensão de fornecimento de força elétrica ou de água por culpa das concessionárias ou falta de materiais na praça, o atraso no pagamento dos serviços, ou mesmo a não liberação de recursos de financiamento bancário em favor d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, hipóteses em que a CONTRATADA deverá ser comunicada por escrito e o prazo será prorrogado por tempo igual àquele em que perdurar o respectivo evento.</li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>5. CLÁUSULA QUINTA - DO PREÇO E FORMA DE PAGAMENTO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>5.1. {{ $subjectArticle }} {{ strtoupper($subject) }} {{ strpos($subjectArticle, "s") === true ? "pagarão" : "pagará" }} à CONTRATADA para execução do objeto deste contrato o valor total de R$ R$" . number_format($contract->value, 2, ',', '.') . " " (Cento e Trinta e Seis Mil Reais), sendo pagos da seguinte forma:
                                <ul>
                                    @foreach ($contract->proposal->payments as $payment)
                                        @if (strcmp("Cliente", $payment->source) === 0)
                                            <li>{{ strtoupper($payment->type) . ": R$" . number_format($payment->value, 2, ',', '.') . " " . $payment->description . " para pagamento até o dia ". date_format(date_create($payment->desired_date),"d/m/Y") }}</li>
                                        @endif
                                    @endforeach
                                    @foreach ($contract->proposal->payments as $payment)
                                        @if (strcmp("Banco", $payment->source) === 0)
                                            <li>O valor de R$ {{ number_format($payment->value, 2, ',', '.') }} serão pagos de acordo com a execução e {{ strtoupper($payment->type) }} da obra realizados pela instituição financeira ({{ strtoupper($payment->bank) }});
                                        @endif        
                                    @endforeach
                                </ul>
                            </li>
                            <li>5.2.  As medições realizadas por engenheiros enviados pelo banco {{ $bank }} para a liberação dos recursos da obra, conforme estipulado no contrato de número NÚMERO SERÁ ANEXADO POSTERIORMENTE A ESTE CONTRATO assinado entre {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} {{ strtoupper($contract->proposal->client->name) }} e o banco {{ $bank }}, e que servirão como único parâmetro para o pagamento d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} à CONTRATADA, exceto em casos de serviços adicionais acordados entre as partes, funcionarão da seguinte maneira:
                            Medição Inicial: O engenheiro civil BHRENO DE OLIVEIRA PONTES, inscrito no CREA nº 45211, faz uma medição do progresso, baseando-se no cronograma estabelecido e envia para o banco {{ $bank }};
                            Fiscalização pela Caixa: Essa medição é então fiscalizada por um engenheiro credenciado pelo banco {{ $bank }}, que verifica a precisão e conformidade com o projeto.
                            Relatório de Vistoria: Se a fiscalização confirmar a conclusão adequada das etapas, o engenheiro do banco {{ $bank }} prepara um relatório de vistoria.
                            Liberação dos Recursos: Com base nesse relatório, o banco {{ $bank }} libera os recursos financeiros para a etapa correspondente, depositando-os na conta d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} ({{strtoupper($contract->proposal->client->name)}}). </li>

                            <li>5.3 {{ $subjectArticle }} {{ strtoupper($subject) }}, deverão realizar os pagamentos correspondentes a cada medição em até 2 (dois) dias úteis após o pagamento da medição pelo banco {{ $bank }}. O cronograma apresentado ao banco deverá ser anexado a este contrato, e passará a valer como cronograma de pagamento deste contrato, para todos os fins de direito. Caso haja atraso no repasse dos valores dentro do período estipulado, será aplicada uma multa de 1% (um por cento) sobre o valor total do contrato. </li>
                            <li>5.4. Os valores das parcelas, deverão ser transferidos via TED ou PIX, para a conta da CONTRATADA no BANCO DO BRASIL, conforme os dados a seguir:
                                <ul>
                                    <li>Agência: {{ strtoupper($enterprise->bank_agency) }}</li>
                                    <li>Conta Corrente: {{ strtoupper($enterprise->bank_account) }}</li>
                                    <li>CNPJ: {{ strtoupper($enterprise->cnpj) }}</li>
                                    <li>PIX: {{ strtoupper($enterprise->pix) }}</li>
                                </ul>
                            </li>
                            <li>5.5. Ocorrendo atraso no pagamento de qualquer prestação haverá incidência de juros de mora de 5% (cinco por cento) ao mês e correção pelo INPC, podendo ainda ocorrer a rescisão caso seja, à opção da CONTRATADA.</li>
                            <li>5.6. As medições mensais serão registradas através de emissão de relatório de medição de obra, elaborado pelo engenheiro responsável pela obra, onde deverá constar o avanço percentual do período, o acumulado anterior e o acumulado atual das medições. O relatório deverá ser apresentado para {{ strtolower($subjectArticle) }} {{ $subject }}, que deverá dar conhecimento deste, através de assinatura ou visto. </li>
                            <li>5.7. A CONTRATADA é responsável por emitir boletos bancários para {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, correspondentes aos valores do saldo devedor mencionados na cláusula 5.1 deste contrato. Uma vez que o saldo devedor seja total ou parcialmente pagos pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, a CONTRATADA emitirá um recibo a eles, confirmando os recebimento. Essa obrigação da contratada em emitir boletos e o recibo final está condicionada ao que foi especificado na cláusula 5.1 do contrato. </li>
                            <li>5.8. Caso {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} não consigam o financiamento bancário e decidam cancelar o contrato, eles são obrigados a pagar R$ 10.000,00 (Dez Mil Reais) à CONTRATADA. Este valor serve como compensação pelos projetos já realizados e pela preparação dos documentos de financiamento. </li>
                            <li>5.9. Para cada revisão de projeto solicitada pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, após a aprovação do serviço, será cobrado o valor de R$ 1.000,00 (Um Mil Reais), que deverá ser pago à vista e adiantado. </li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>6. CLÁUSULA SEXTA - DA ENTREGA DA OBRA</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>6.1. Estando a obra completamente pronta, testada, e atestada pelo banco {{ $bank }}, a mesma será entregue para {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, que procederá a um minucioso exame e verificação, após o que será a entrega final ratificada mediante termo por escrito, assinado pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} e pela CONTRATADA, e no qual ficará especificado o cumprimento das cláusulas contratuais, sem, contudo, eximir a CONTRATADA das responsabilidades que por sua natureza ou por dispositivo legal, se estendam além do término do presente contrato.</li>
                            <li>6.2. {{ $subjectArticle }} {{ strtoupper($subject) }} só poderão realizar a posse e ocupação do imóvel após o recebimento definitivo da obra, que será dado através da assinatura do TERMO RECEBIMENTO DE OBRA, TERMO DE VISTORIA DE OBRA e MANUAL DO PROPRIETÁRIO com orientações sobre os prazos de garantia.</li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>7. CLÁUSULA SÉTIMA - DA RESCISÃO OU RESOLUÇÃO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>7.1. São motivos de rescisão ou resolução de pleno direito, do presente contrato, independentemente de interpelação ou notificação judicial ou extrajudicial e sem prejuízo das penalidades, se assim convier à parte não infratora:
                                <ul style="list-style: lower-norwegian;">
                                    <li>A interrupção ou paralisação das obras pel{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, por mais de 10 (dez) dias consecutivos, sem motivo justificado; </li>
                                    <li>O atraso por parte d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}, por mais de 10 (dez) dias consecutivos no pagamento das parcelas medidas mensalmente; </li>
                                    <li>A falência ou concordata de qualquer das partes contratantes; </li>
                                    <li>A infração de quaisquer outras cláusulas do presente contrato, incluindo a inobservância do Projeto e especificações dos materiais descritos no memorial descritivo; </li>
                                </ul>
                            </li>
                            <li>7.2. Rescindido ou resolvido este contrato, a parte infratora ficará sujeita ao pagamento da multa de 20% (vinte por cento) sobre o valor do presente contrato, indenização por perdas e danos causados à parte não infratora, além de custas e honorários advocatícios decorrentes de eventual ação judicial.</li>
                            <li>7.3. Se {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} decidirem cancelar o contrato durante a fase de obras, eles precisam pagar uma multa de 20% do valor do contrato a CONTRATADA e acertar os serviços ainda não pagos até o momento da data da rescisão. Caso a CONTRATADA decida cancelar, ela deve pagar 20% do valor total do contrato d{{ strtolower($subjectArticle) }} {{strtoupper($subject)}} e entregar o trabalho correspondente ao que já foi pago até o momento da data da rescisão. </li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>8. CLÁUSULA OITAVA - DA PROPRIEDADE INTELECTUAL E COMPETÊNCIA TÉCNICA</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>8.1. Salvo se expressamente contemplado neste contrato, nenhuma das partes adquirirá nenhum direito, titularidade ou participação na propriedade intelectual ou competência técnica. </li>
                            <li>8.2. Nenhuma disposição deste contrato deverá ser interpretada como concedendo a qualquer das partes licença ou direito expresso ou tácito em relação a qualquer propriedade intelectual ou competência técnica da outra, inclusive no que disser respeito aos projetos e trabalhos técnicos. </li>
                        </ul>
                    </div>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>9. CLÁUSULA NONA - DO SIGILO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>9.1. As partes, por si e por eventuais empregados e prestadores de serviço ou outros terceiros envolvidos na execução dos serviços, obrigam-se a manter os critérios de sigilo e confidencialidade quando utilizarem dados e informações pertinentes ao presente contrato, obrigando-se a estender tal vinculação obrigacional de sigilo a todos aqueles que, ainda que indiretamente, mantenham qualquer tipo de conhecimento das informações referentes ao presente contrato. </li>
                            <li>9.2. Para os fins e efeitos desta cláusula, considera-se informação sigilosa e/ou confidencial aquela disponibilizada e/ou fornecida por uma das partes às outras relativas à natureza técnica, operacional, econômica, de engenharia, bem como quaisquer outros dados, materiais, informações, documentos, especificações técnicas que não disponíveis ao público, bem assim outras que a CONTRATADA venha a ter acesso e/ou conhecimento, ou que lhe venha a ser confiado em razão do objeto do presente contrato. </li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>10. CLÁUSULA DÉCIMA - DAS DISPOSIÇÕES GERAIS</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>10.1. As partes não poderão ceder seus direitos e obrigações decorrentes neste contrato sem o prévio consentimento escrito da outra parte. </li>
                            <li>10.2. Nenhum aditamento, alteração, revisão ou isenção deste contrato, no todo ou em parte, terá vigor ou efeito se não for realizado por escrito e assinado pelas partes. </li>
                            <li>10.3. Qualquer omissão ou tolerância por uma das partes quanto ao fiel cumprimento das disposições do presente contrato não constituirá novação, renúncia ou transigência, não afetando o direito da parte de exigi-las a qualquer tempo. </li>
                            <li>10.4. As cláusulas e condições estabelecidas no presente contrato prevalecem sobre todos os ajustes, verbais e/ou escritos, firmados pelas partes anteriormente ao ato de assinatura do presente contrato e que tenham relação, direta e/ou indireta, com os objetivos do vínculo obrigacional ora estipulado. </li>
                            <li>10.5. Caso {{ strtolower($subjectArticle) }} {{ strtoupper($subject) }} desejarem alterar mudanças no objeto deste contrato após o mesmo já estiver registrado e a obra iniciada, deverá observar os seguintes procedimentos: </li>
                                <ul style="list-style: lower-norwegian;">
                                    <li>Solicitação por escrito, assinada e datada à CONTRATADA, descrevendo a mudança solicitada; </li>
                                    <li>A CONTRATADA num prazo de até 05 (cinco) dias úteis, responderá por escrito d{{ strtolower($subjectArticle) }} {{strtoupper($subject)}} sobre a viabilidade da mudança solicitada, levando-se em consideração: Viabilidade da mudança frente aos órgãos público; Viabilidade da mudança frente às questões técnicas da obra, consultando os profissionais correspondentes envolvidos (arquitetônico, estrutural, elétrico, hidráulico, etc); Viabilidade da mudança frente ao cronograma físico financeiro da obra; Custos e condições de pagamento da mudança; </li>
                                    <li>Os custos decorrentes da mudança (modificação do projeto aprovado, consulta aos profissionais responsáveis, taxas, material, mão-de-obra, etc.) serão de inteira responsabilidade d{{ strtolower($subjectArticle) }} {{ strtoupper($subject) }}; </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>11. CLÁUSULA DÉCIMA PRIMEIRA - DA IRREVOGABILIDADE E DA IRRETRATABILIDADE</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>11.1. Este contrato, por convenção expressa das partes, é firmado em caráter irrevogável e irretratável, obrigando as partes e seus sucessores.</li>
                        </ul>
                    </div>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>12. CLÁUSULA DÉCIMA SEGUNDA - DO FORO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            <li>12.1. Fica eleito o foro da Ibiapina no estado do Ceará para qualquer ação oriunda do presente contrato, renunciando as partes contratantes a qualquer outro.
                                E por estarem assim justas e contratadas, as partes assinam o presente contrato em 2 (duas) vias de igual teor e forma, juntamente com o Memorial Descritivo anexo, na presença de duas testemunhas. </li>

                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b></b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <div class="row">
                            <div class="col-7"></div>
                            <?php
                                $month = date('m');
                                $currentMonth = $MONTHS["$month"];
                            ?>
                            <div class="col-5">Ibiapina - Ceará, {{ date('d') }} de {{ $currentMonth }} de {{ date('Y') }}</div>
                        </div>
                    </div>
                    <div class="col-12 row-colored no-bottom-border">
                        <div class="row" style="margin-top: 50px;">
                            <div class="col-3"></div>
                            <div class="col-6 text-center">
                                <div class="row" style="border-top: 1px solid gray;">
                                    <div class="col-12">{{ mb_strtoupper($enterprise->name, 'UTF-8') }}</div>
                                    <div class="col-12">CNPJ: {{ mb_strtoupper($enterprise->cnpj, 'UTF-8') }}</div>
                                    <div class="col-12"><b>CONTRATADA</b></div>
                                </div>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                    <div class="col-12 row-colored no-bottom-border">
                        <div class="row" style="margin-top: 50px; margin-left: 20px">
                            <div class="col-6 text-center">
                                <div class="row" style="border-top: 1px solid gray;">
                                    <div class="col-12">{{ mb_strtoupper($contract->proposal->client->name, 'UTF-8') }}</div>
                                    <div class="col-12">CPF: {{ mb_strtoupper($contract->proposal->client->cpf, 'UTF-8') }}</div>
                                    <div class="col-12"><b>CONTRATANTE</b></div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="row" style="border-top: 1px solid gray;">
                                    <div class="col-12">{{ mb_strtoupper($contract->proposal->client->name_dependent, 'UTF-8') }}</div>
                                    <div class="col-12">CPF: {{ mb_strtoupper($contract->proposal->client->cpf_dependent, 'UTF-8') }}</div>
                                    <div class="col-12"><b>CONTRATANTE</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row-colored no-bottom-border">
                        <div class="row" style="margin-top: 50px; margin-left: 20px">
                            <div class="col-6 text-center">
                                <div class="row" style="border-top: 1px solid gray;">
                                    <div class="col-12">{{ mb_strtoupper($contract->witness_one_name, 'UTF-8') }}</div>
                                    <div class="col-12">CPF: {{ mb_strtoupper($contract->witness_one_cpf, 'UTF-8') }}</div>
                                    <div class="col-12"><b>TESTEMUNHA</b></div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="row" style="border-top: 1px solid gray;">
                                    <div class="col-12">{{ mb_strtoupper($contract->witness_two_name, 'UTF-8') }}</div>
                                    <div class="col-12">CPF: {{ mb_strtoupper($contract->witness_two_cpf, 'UTF-8') }}</div>
                                    <div class="col-12"><b>TESTEMUNHA</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 50px">
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