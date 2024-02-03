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
                text-align: justify;
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
            @if (strcmp($contract->proposal->client->state, "Casado") === 0)
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
                        <ul class="list-no-style">
                            <li>2.2. Por este instrumento particular e na melhor forma de direito, os CONTRATANTES acordam os serviços da CONTRATADA para elaborar os projetos de arquitetura e engenharia, bem como a documentação técnica de financiamento bancário e executar as obras de construção de uma residência unifamiliar com 62,57 M² (Sessenta E Dois Metros Quadrados E Cinquenta E Sete Decímetros Quadrados) de área total, conforme descrita e especificada no projeto a ser desenvolvido e posteriormente anexo a este contrato para todos os fins de direito.</li>
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
                        <ul class="list-no-style">
                            <li>3.1. A elaboração dos projetos e documentação técnica observará as seguintes premissas: </li>
                            <li>3.1.1. O engenheiro BHRENO DE OLIVEIRA PONTES, registrado no CREA sob o nº 45211, é contratado em comum acordo pela CONTRATADA e os CONTRATANTES para se responsabilizar tecnicamente pela aprovação do projeto de construção junto à Prefeitura do Município de Ibiapina. Além disso, ele acompanhará o andamento da obra, realizando as medições necessárias que serão enviadas a CAIXA ECONÔMICA FEDERAL para fiscalização por um engenheiro credenciado da mesma. Todas as exigências, ajustes e esclarecimentos requisitados pela Prefeitura Municipal de Ibiapina ou pela CAIXA ECONÔMICA FEDERAL serão prontamente atendidos por ele. Os honorários deste engenheiro serão pagos pela CONTRATADA.</li>
                            <li>3.1.2. Fornecer aos CONTRATANTES em formato digital (em extensão.pdf) um original de cada uma das plantas, e detalhes relativos ao desenvolvimento do projeto;</li>
                            <li>3.1.3. A CONTRATADA caberá coordenar os cálculos complementares do projeto arquitetônico incluindo supervisionar o desenvolvimento dos planos de estrutura, instalações hidráulicas, sanitárias, elétricas, telecomunicações, climatização e paisagismo, sempre que estes elementos façam parte do projeto, garantindo sua integração e conformidade com as normas técnicas.</li>
                            <li>3.1.4. A CONTRATADA deve elaborar os serviços objetivados no presente contrato, em obediência as normas e especificações técnicas vigentes, responsabilizando-se pelos serviços prestados, na forma da legislação em vigor;</li>
                            <li>3.1.5. Os CONTRATANTES deverão pagar os honorários da CONTRATADA, referentes a projetos modificativos, e alterações de projetos das fases já executadas, decorrentes das solicitações feitas pelos CONTRATANTES, independente das razões que o motivaram. Esses honorários serão cobrados conforme Cláusula 5.9.do presente contrato.</li>
                            <li>3.2. A construção da obra observará as seguintes premissas:</li>
                            <li>3.2.1. As obras serão executadas pela CONTRATADA, com equipamentos, ferramentas e maquinários próprios ou locados às suas expensas, prestando pessoalmente os serviços ou contratando de mão de obra especializada, tendo este vínculo exclusivamente com o mesmo, a qual responderá pelo pagamento dos salários bem como todos os encargos tributários, cíveis ou trabalhistas decorrentes da contratação da mão de obra.</li>
                            <li>3.2.2. A CONTRATADA será responsável ainda pela aquisição dos materiais a serem empregados na obra, já inclusos no valor deste contrato, os quais obedecerão as especificações e qualidade descritas no Projeto e Memorial Descritivo anexos.</li>
                            <li>3.2.3. Será a CONTRATADA a única responsável por eventuais danos e prejuízos causados a terceiros em decorrência da execução da obra a seu cargo.</li>
                            <li>3.2.4. Poderá os CONTRATANTES, ou pessoa por eles autorizada, visitar as obras em qualquer dia ou horário, a fim de proceder com o seu acompanhamento técnico.</li>
                            <li>3.2.5. Os CONTRATANTES, em hipótese alguma, poderá intervir na gestão, controle e execução da mão de obra, serviços e materiais a serem aplicados na obra, a qualquer momento, a fim de garantir a perfeita execução da obra.</li>
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
                            <li>4.1.2. PROJETO DE ARQUITETURA LEGAL (PREFEITURA): 15 (quinze) dias após a aprovação pelos CONTRATANTES do estudo preliminar;</li>                          
                            <li>4.1.3.	PROJETO ESTRUTURAL: 15 (quinze) dias após a aprovação pelos CONTRATANTES do projeto de arquitetura legal;</li>
                            <li>4.1.4. PROJETO ELÉTRICO: 15 (quinze) dias após a aprovação pelos CONTRATANTES do projeto de arquitetura legal;</li>
                            <li>4.1.5. PROJETO HIDROSSANITÁRIO: 15 (quinze) dias após a aprovação pelos CONTRATANTES do projeto de arquitetura legal;</li>
                            <li>4.1.6. DOCUMENTAÇÃO TÉCNICA PARA FINANCIAMENTO BANCÁRIO: 15 (quinze) dias após a aprovação pelos CONTRATANTES do projeto de arquitetura legal;</li>
                            <li>4.1.7. CONSTRUÇÃO DA OBRA: A obra será iniciada 10 (dez) dias após a assinatura do contrato de financiamento junto à CAIXA ECONÔMICA FEDERAL. E será executada conforme cronograma físico-financeiro de financiamento imobiliário apresentado e posteriormente anexo a este contrato.</li>
                            <li>4.2. Os prazos da Clausula 4.1.constituem os mínimos necessários para o desenvolvimento técnico dos serviços, podendo no entanto, serem dilatados a pedido da CONTRATADA.</li>
                            <li>4.3. Não serão contados os dias em que o projeto ficar retido pelos CONTRATANTES, para apreciação;</li>
                            <li>4.4. Os prazos acima não se vinculam aos prazos necessários para aprovação junto aos órgãos competentes, podendo, entretanto, a CONTRATADA desenvolver, paralelamente e estes trâmites, as etapas posteriores.</li>
                            <li>4.5. Os CONTRATANTES devem agir prontamente em todas as ações que dependerem exclusivamente deles para o avanço do projeto e documentações deste contrato. Caso eles atrasem ou causem dificuldades, a CONTRATADA não será responsável por atrasos nos serviços e pode cancelar o contrato. Se os CONTRATANTES falharem em colaborar adequadamente, especialmente na fase de projetos e documentos, terão que pagar R$ 10.000,00 (Dez Mil Reais) à CONTRATADA, que pode decidir encerrar o contrato se necessário sem nenhuma penalidade.</li>
                            <li>4.6. Os CONTRATANTES terão 5 (cinco) dias úteis para a aprovação ou solicitação de eventuais alterações a contar da entrega de cada etapa.</li>
                            <li>4.7. O presente contrato terá início na data da sua assinatura, e seguirá os prazos estabelecidos na cláusula 4.1.</li>
                            <li>4.8. O prazo de conclusão poderá ser prorrogado em caso de comprovado caso fortuito ou de força maior, como guerra, calamidade pública, suspensão de fornecimento de força elétrica ou de água por culpa das concessionárias ou falta de materiais na praça, o atraso no pagamento dos serviços, ou mesmo a não liberação de recursos de financiamento bancário em favor dos CONTRATANTES, hipóteses em que a CONTRATADA deverá ser comunicada por escrito e o prazo será prorrogado por tempo igual àquele em que perdurar o respectivo evento.</li>
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>5. CLÁUSULA QUINTA - DO PREÇO E FORMA DE PAGAMENTO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>6. CLÁUSULA SEXTA - DA ENTREGA DA OBRA</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>7. CLÁUSULA SÉTIMA - DA RESCISÃO OU RESOLUÇÃO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>8. CLÁUSULA OITAVA - DA PROPRIEDADE INTELECTUAL E COMPETÊNCIA TÉCNICA</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>9. CLÁUSULA NONA - DO SIGILO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>10. CLÁUSULA DÉCIMA - DAS DISPOSIÇÕES GERAIS</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>11. CLÁUSULA DÉCIMA PRIMEIRA - DA IRREVOGABILIDADE E DA IRRETRATABILIDADE</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
                    </div>
                    <br></br>
                    <div style="padding-top: 15px;" class="col-12 row-colored no-bottom-border"><b>12. CLÁUSULA DÉCIMA SEGUNDA - DO FORO</b></div>
                    <div class="col-12 row-colored no-bottom-border">
                        <ul class="list-no-style">
                            
                        </ul>
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