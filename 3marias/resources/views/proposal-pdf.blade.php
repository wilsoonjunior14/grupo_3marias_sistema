@extends('template')

@section('content')

<div class="row">
    <div class="col-12"><b>Dados da Proponente</b></div>
</div>
<div class="row">
    <div class="col-9 row-colored">
        <b>Dados da Empresa</b> </br>
        <span>{{ strtoupper($enterprise->name) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CNPJ</b> </br>
        <span>{{ strtoupper($enterprise->cnpj) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Bairro</b> </br>
        <span>{{ strtoupper($enterprise->neighborhood) }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>CEP</b> </br>
        <span>{{ strtoupper($enterprise->zipcode) }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Cidade</b> </br>
        <span>{{ strtoupper($enterprise->city_name) }}</span>
    </div>
    <div class="col-1 row-colored">
        <b>UF</b> </br>
        <span>{{ strtoupper($enterprise->state_acronym) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ strtoupper($enterprise->address) }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>Número</b> </br>
        <span>{{ strtoupper($enterprise->number) }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Complemento</b> </br>
        <span>{{ strtoupper($enterprise->complement) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-1 row-colored">
        <b>DDD</b> </br>
        <span>{{ strtoupper(substr($enterprise->phone, 1, 2)) }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Telefone</b> </br>
        <span>{{ strtoupper(substr($enterprise->phone, 4)) }}</span>
    </div>
    <div class="col-7 row-colored">
        <b>Email</b> </br>
        <span></span>
    </div>
</div>

<div class="row">
    <div class="col-12"><b>Dados da Pessoais do Cliente</b></div>
</div>
<div class="row">
    <div class="col-9 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ strtoupper($proposal->client->name) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ strtoupper($proposal->client->cpf) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Naturalidade</b> </br>
        <span>{{ strtoupper($proposal->client->naturality) }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>UF</b> </br>
        <span>{{ strtoupper($proposal->client->state_acronym) }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ strtoupper($proposal->client->nationality) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ strtoupper($proposal->client->address) }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>Número</b> </br>
        <span>{{ strtoupper($proposal->client->number) }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Complemento</b> </br>
        <span>{{ strtoupper($proposal->client->complement) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Bairro</b> </br>
        <span>{{ strtoupper($proposal->client->neighborhood) }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>CEP</b> </br>
        <span>{{ strtoupper($proposal->client->zipcode) }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Cidade</b> </br>
        <span>{{ strtoupper($proposal->client->city_name) }}</span>
    </div>
    <div class="col-1 row-colored">
        <b>UF</b> </br>
        <span>{{ strtoupper($proposal->client->state_acronym) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-1 row-colored">
        <b>DDD</b> </br>
        <span>{{ strtoupper(substr($proposal->client->phone, 1, 2)) }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Telefone</b> </br>
        <span>{{ strtoupper(substr($proposal->client->phone, 4)) }}</span>
    </div>  
    <div class="col-7 row-colored">
        <b>Email</b> </br>
        <span>{{ strtoupper($proposal->client->email) }}</span>
    </div>
</div>
<div class="row">
    <div class="col-12 row-colored">
        <b>Objetos da Proposta</b> </br>
        <span>
        {{ strtoupper($proposal->description) }}
        
        <ul>
            <li>DOCUMENTAÇÃO TÉCNICA PARA FINANCIAMENTO BANCÁRIO: ELABORAÇÃO DE PLANILHA ORÇAMENTÁRIA, CRONOGRAMA FÍSICO-FINANCEIRO, MEMORIAL DESCRITIVO, E DEMAIS DOCUMENTOS TÉCNICOS NECESSÁRIOS EXIGIDOS PELAS INSTITUIÇÕES FINANCEIRAS, PARA FINS DE FINANCIAMENTO BANCÁRIO; </li>
            <li>PAGAMENTO DE TAXAS E IMPOSTOS: TODAS AS DESPESAS ORIUNDAS DE ANÁLISES, APROVAÇÕES E REGISTROS DOS SERVIÇOS ESTÃO INCLUSAS COMO DE RESPONSABILIDADE DA <b>CONSTRUTORA E IMOBILIÁRIA 3 MARIAS LTDA</b> TAIS COMO: TAXAS DO CREA, TAXAS DE PREFEITURA, TAXAS DE CARTÓRIO, TAXAS DAS INSTITUIÇÕES FINANCEIRAS, EMISSÃO DE ALVARÁ DE CONSTRUÇÃO, TAXAS DE RECOLHIMENTO INSS DA OBRA, TAXA EMISSÃO DE HABITE-SE, ISS, ITBI E AVERBAÇÕES, ATÉ A ENTREGA DA CONSTRUÇÃO;</li>
        </ul>    
        </span>
    </div>
</div>
<div class="row">
    <div class="col-12 row-colored">
        <b>Valor Total da Proposta</b> </br>
        <span>R$ {{ $global_value }}</span>
    </div>
</div>
<div class="row">
    <div class="col-12 row-colored">
        <b>Prazo da Proposta</b> </br>
        <span>45 DIAS</span>
        <b>Formas de Pagamento</b> </br>
        <span>
            <ul>
                @foreach ($proposal->payments as $payment)
                    @if (strcmp("Cliente", $payment->source) === 0)
                    <li>{{ strtoupper($payment->type) . ": R$" . number_format($payment->value, 2, ',', '.') . " " . $payment->description . " EM ". date_format(date_create($payment->desired_date),"d/m/Y") }}</li>
                    @endif
                @endforeach
            </ul>
        </span> </br>
        <span>
        @foreach ($proposal->payments as $payment)
            @if (strcmp("Banco", $payment->source) === 0)
                O VALOR DE R$ {{ number_format($payment->value, 2, ',', '.') }} SERÃO PAGOS DE ACORDO COM A EXECUÇÃO E {{ strtoupper($payment->type) }} DA OBRA REALIZADAS PELA {{ strtoupper($payment->bank) }};
            @endif        
        @endforeach
        </span>
    </div>
</div>

<div class="row" style="text-align: center;">
    <div class="col-12 row-colored">
        <?php date_default_timezone_set("America/Sao_Paulo"); ?>
        <span>Ibiapina - Ceará, {{ date('d') }} de Fevereiro de {{ date('Y') }}</span> </br> </br>

        <span style="border-top: 1px solid black">{{ strtoupper($enterprise->fantasy_name) }}</span> </br>
        <span>CNPJ {{ strtoupper($enterprise->cnpj) }}</span> </br>
    </div>
</div>

@stop