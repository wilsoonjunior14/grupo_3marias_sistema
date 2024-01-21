@extends('template')

@section('content')

<div class="row">
    <div class="col-12"><b>Dados do Proponente</b></div>
</div>
<div class="row">
    <div class="col-6 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ strtoupper($client->name) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ strtoupper($client->cpf) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Nascimento</b> </br>
        @if (!is_null($client->birthdate))
        <span>{{ date_format(date_create($client->birthdate),"d/m/Y") }}</span>
        @else
        <span></span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-3 row-colored">
        <b>Nº Documento de Identidade</b> </br>
        <span>{{ strtoupper($client->rg) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Tipo de Documento</b> </br>
        <span>RG</span>
    </div>
    <div class="col-3 row-colored">
        <b>Òrgão de Emissão/UF</b> </br>
        <span>{{ strtoupper($client->rg_organ) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Emissão</b> </br>
        <span>{{ date_format(date_create($client->rg_date),"d/m/Y") }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Naturalidade</b> </br>
        <span></span>
    </div>
    <div class="col-3 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ strtoupper($client->nationality) }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Profissão</b> </br>
        <span>{{ strtoupper($client->ocupation) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Atendimento</b> </br>
        <span>| | Presencial | | Whatsapp | | Instagram</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Estado Civil</b> </br>
        <span>{{ strtoupper($client->state) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Indicação</b> </br>
        <span>| | Não | | Sim  Quem? <U></U></span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ strtoupper($client->address) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Complemento</b> </br>
        <span>{{ strtoupper($client->complement) }}</span>
    </div>
</div>

<div class="row">
    <div class="col-3 row-colored">
        <b>Bairro</b> </br>
        <span>{{ strtoupper($client->neighborhood) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Cep</b> </br>
        <span>{{ strtoupper($client->zipcode) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Cidade</b> </br>
        <span>{{ strtoupper($client->city_name) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>UF</b> </br>
        <span>{{ strtoupper($client->state_name) }}</span>
    </div>
</div>

<div class="row">
    <div class="col-3 row-colored">
        <b>DDD</b> </br>
        <span>{{ strtoupper(substr($client->phone, 1, 2)) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Telefone</b> </br>
        <span>{{ strtoupper(substr($client->phone, 4)) }}</span>
    </div>
    <div class="col-6 row-colored">
        <b>Email</b> </br>
        <span>{{ strtoupper($client->email) }}</span>
    </div>
</div>

<div class="row">
    <div class="col-6 row-colored">
        <b>Renda Bruta</b> </br>
        <span>R$ </span>
    </div>
    <div class="col-6 row-colored">
        <b>Funcionário Público</b> </br>
        <span>| | Sim | | Não</span>
    </div>
</div>

<div class="row">
    <div class="col-12 row-colored">
        <b>Marque as opções que se aplicam ao seu caso:</b> </br>
        <b>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> </br>
        <span>| | Sim  | | Não</span> </br>
        <b>Mais de um comprador ou dependente?</b> </br>
        <span>| | Sim  | | Não</span> </br>
    </div>
</div>

@if (strcmp($client->state, "Casado") === 0)

<div class="row">
    <div class="col-12"><b>Dados do Cônjugue do Proponente</b></div>
</div>
<div class="row">
    <div class="col-6 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ strtoupper($client->name_dependent) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ strtoupper($client->cpf_dependent) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Nascimento</b> </br>
        @if (!is_null($client->birthdate_dependent))
        <span>{{ date_format(date_create($client->birthdate_dependent),"d/m/Y") }}</span>
        @else
        <span></span>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-3 row-colored">
        <b>Nº Documento de Identidade</b> </br>
        <span>{{ strtoupper($client->rg_dependent) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Tipo de Documento</b> </br>
        <span>RG</span>
    </div>
    <div class="col-3 row-colored">
        <b>Òrgão de Emissão/UF</b> </br>
        <span>{{ strtoupper($client->rg_dependent_organ) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Emissão</b> </br>
        <span>{{ date_format(date_create($client->rg_dependent_date),"d/m/Y") }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Naturalidade</b> </br>
        <span></span>
    </div>
    <div class="col-3 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ strtoupper($client->nationality) }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Profissão</b> </br>
        <span>{{ strtoupper($client->ocupation_dependent) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Atendimento</b> </br>
        <span>| | Presencial | | Whatsapp | | Instagram</span>
    </div>
</div>

@if (!is_null($client->phone_dependent) || !is_null($client->email_dependent))
<div class="row">
    @if (!is_null($client->phone_dependent))
    <div class="col-3 row-colored">
        <b>DDD</b> </br>
        <span>{{ strtoupper(substr($client->phone_dependent, 1, 2)) }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Telefone</b> </br>
        <span>{{ strtoupper(substr($client->phone_dependent, 4)) }}</span>
    </div>
    @endif
    @if (!is_null($client->email_dependent))
    <div class="col-6 row-colored">
        <b>Email</b> </br>
        <span>{{ strtoupper($client->email_dependent) }}</span>
    </div>
    @endif
</div>
@endif

<div class="row">
    <div class="col-6 row-colored">
        <b>Renda Bruta</b> </br>
        <span>R$ </span>
    </div>
    <div class="col-6 row-colored">
        <b>Funcionário Público</b> </br>
        <span>| | Sim | | Não</span>
    </div>
</div>

<div class="row">
    <div class="col-12 row-colored">
        <b>Marque as opções que se aplicam ao seu caso:</b> </br>
        <b>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> </br>
        <span>| | Sim  | | Não</span> </br>
        <b>Mais de um comprador ou dependente?</b> </br>
        <span>| | Sim  | | Não</span> </br>
    </div>
</div>

@endif

@stop