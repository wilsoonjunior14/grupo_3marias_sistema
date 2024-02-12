@extends('template')

@section('content')

<div class="row">
    <div class="col-12"><b>Dados da Proponente</b></div>
</div>
<div class="row">
    <div class="col-9 row-colored">
        <b>Dados da Empresa</b> </br>
        <span>{{ mb_strtoupper($enterprise->name, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CNPJ</b> </br>
        <span>{{ mb_strtoupper($enterprise->cnpj, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Bairro</b> </br>
        <span>{{ mb_strtoupper($enterprise->neighborhood, 'UTF-8') }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>CEP</b> </br>
        <span>{{ mb_strtoupper($enterprise->zipcode, 'UTF-8') }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Cidade</b> </br>
        <span>{{ mb_strtoupper($enterprise->city_name, 'UTF-8') }}</span>
    </div>
    <div class="col-1 row-colored">
        <b>UF</b> </br>
        <span>{{ mb_strtoupper($enterprise->state_acronym, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ mb_strtoupper($enterprise->address, 'UTF-8') }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>Número</b> </br>
        <span>{{ mb_strtoupper($enterprise->number, 'UTF-8') }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Complemento</b> </br>
        <span>{{ mb_strtoupper($enterprise->complement, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-1 row-colored">
        <b>DDD</b> </br>
        <span>{{ mb_strtoupper(substr($enterprise->phone, 1, 2), 'UTF-8') }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Telefone</b> </br>
        <span>{{ mb_strtoupper(substr($enterprise->phone, 4), 'UTF-8') }}</span>
    </div>
    <div class="col-7 row-colored">
        <b>Email</b> </br>
        <span>{{ $enterprise->email }}</span>
    </div>
</div>

<div class="row">
    <div class="col-12"><b>Dados da Pessoais do Cliente</b></div>
</div>
<div class="row">
    <div class="col-9 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ mb_strtoupper($proposal->client->name, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ mb_strtoupper($proposal->client->cpf, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Naturalidade</b> </br>
        <span>{{ mb_strtoupper($proposal->client->naturality, 'UTF-8') }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>UF</b> </br>
        <span>{{ mb_strtoupper($proposal->client->state_acronym, 'UTF-8') }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ mb_strtoupper($proposal->client->nationality, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ mb_strtoupper($proposal->client->address, 'UTF-8') }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>Número</b> </br>
        <span>{{ mb_strtoupper($proposal->client->number, 'UTF-8') }}</span>
    </div>
    <div class="col-5 row-colored">
        <b>Complemento</b> </br>
        <span>{{ mb_strtoupper($proposal->client->complement, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-5 row-colored">
        <b>Bairro</b> </br>
        <span>{{ mb_strtoupper($proposal->client->neighborhood, 'UTF-8') }}</span>
    </div>
    <div class="col-2 row-colored">
        <b>CEP</b> </br>
        <span>{{ mb_strtoupper($proposal->client->zipcode, 'UTF-8') }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Cidade</b> </br>
        <span>{{ mb_strtoupper($proposal->client->city_name, 'UTF-8') }}</span>
    </div>
    <div class="col-1 row-colored">
        <b>UF</b> </br>
        <span>{{ mb_strtoupper($proposal->client->state_acronym, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-1 row-colored">
        <b>DDD</b> </br>
        <span>{{ mb_strtoupper(substr($proposal->client->phone, 1, 2), 'UTF-8') }}</span>
    </div>
    <div class="col-4 row-colored">
        <b>Telefone</b> </br>
        <span>{{ mb_strtoupper(substr($proposal->client->phone, 4), 'UTF-8') }}</span>
    </div>  
    <div class="col-7 row-colored">
        <b>Email</b> </br>
        <span>{{ mb_strtoupper($proposal->client->email, 'UTF-8') }}</span>
    </div>
</div>
<div class="row">
    <div class="col-12 row-colored">
        <b>Objetos da Proposta</b> </br>
        <span>
        {{ mb_strtoupper($proposal->description, 'UTF-8') }}
        
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
        <b>Prazo da Proposta:</b> <span style="font-size: 12px">45 DIAS</span></br>
        <span></span>
        <b>Formas de Pagamento</b> </br>
        <span>
            <ul>
                @foreach ($proposal->payments as $payment)
                    @if (strcmp("Cliente", $payment->source) === 0)
                    <li>{{ mb_strtoupper($payment->type, 'UTF-8') . ": R$" . number_format($payment->value, 2, ',', '.') . " " . mb_strtoupper($payment->description, 'UTF-8') . " EM ". date_format(date_create($payment->desired_date),"d/m/Y") }}</li>
                    @endif
                @endforeach
            </ul>
        </span> </br>
        <span>
        @foreach ($proposal->payments as $payment)
            @if (strcmp("Banco", $payment->source) === 0)
                O VALOR DE R$ {{ number_format($payment->value, 2, ',', '.') }} SERÃO PAGOS DE ACORDO COM A EXECUÇÃO E {{ mb_strtoupper($payment->type, 'UTF-8') }} DA OBRA REALIZADAS PELA {{ mb_strtoupper($payment->bank, 'UTF-8') }};
            @endif        
        @endforeach
        </span>
    </div>
</div>

<div class="row" style="text-align: center;">
    <div class="col-12 row-colored">
        <?php date_default_timezone_set("America/Sao_Paulo"); ?>
        <span>Ibiapina - Ceará, {{ date('d') }} de Fevereiro de {{ date('Y') }}</span> </br> </br>

        <span style="border-top: 1px solid black">{{ mb_strtoupper($enterprise->fantasy_name, 'UTF-8') }}</span> </br>
        <span>CNPJ {{ mb_strtoupper($enterprise->cnpj, 'UTF-8') }}</span> </br>
    </div>
</div>

@stop