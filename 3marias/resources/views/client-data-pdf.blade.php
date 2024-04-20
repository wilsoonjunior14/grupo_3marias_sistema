@extends('template')

@section('content')

<div class="row">
    <div class="col-12"><b>Dados do Proponente</b></div>
</div>
<div class="row">
    <div class="col-6 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ mb_strtoupper($client->name, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ mb_strtoupper($client->cpf, 'UTF-8') }}</span>
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
        <span>{{ mb_strtoupper($client->rg, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Tipo de Documento</b> </br>
        <span>RG</span>
    </div>
    <div class="col-3 row-colored">
        <b>Òrgão de Emissão/UF</b> </br>
        <span>{{ mb_strtoupper($client->rg_organ, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Emissão</b> </br>
        @if (!is_null($client->rg_date))
        <span>{{ date_format(date_create($client->rg_date), "d/m/Y") }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Naturalidade</b> </br>
        <span>{{ mb_strtoupper($client->naturality, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ mb_strtoupper($client->nationality, 'UTF-8') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Profissão</b> </br>
        <span>{{ mb_strtoupper($client->ocupation, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Atendimento</b> </br>
        <span>| @if(strcmp($client->person_service, "Presencial") === 0) X @endif| Presencial 
            | @if(strcmp($client->person_service, "Whatsapp") === 0) X @endif| Whatsapp 
            | @if(strcmp($client->person_service, "Instagram") === 0) X @endif| Instagram</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Estado Civil</b> </br>
        <span>{{ mb_strtoupper($client->state, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Indicação</b> </br>
        <span>
            @if (is_null($client->indication))
            | | Não | | Sim  Quem?
            @else
            | X | Sim Quem? <U>{{ mb_strtoupper($client->indication, 'UTF-8') }}</U>
            @endif
        </span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Endereço Residencial</b> </br>
        <span>{{ mb_strtoupper($client->address, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Complemento</b> </br>
        <span>{{ mb_strtoupper($client->complement, 'UTF-8') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-3 row-colored">
        <b>Bairro</b> </br>
        <span>{{ mb_strtoupper($client->neighborhood, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Cep</b> </br>
        <span>{{ mb_strtoupper($client->zipcode, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Cidade</b> </br>
        <span>{{ mb_strtoupper($client->city_name, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>UF</b> </br>
        <span>{{ mb_strtoupper($client->state_acronym, 'UTF-8') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-3 row-colored">
        <b>DDD</b> </br>
        <span>{{ mb_strtoupper(substr($client->phone, 1, 2), 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Telefone</b> </br>
        <span>{{ mb_strtoupper(substr($client->phone, 4), 'UTF-8') }}</span>
    </div>
    <div class="col-6 row-colored">
        <b>Email</b> </br>
        <span>{{ mb_strtoupper($client->email, 'UTF-8') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-6 row-colored">
        <b>Renda Bruta</b> </br>
        @if(!is_null($client->salary))
        <span>R$ {{ number_format($client->salary, 2, ',', '.') }}</span>
        @endif
    </div>
    <div class="col-6 row-colored">
        <b>Funcionário Público</b> </br>
        <span>| @if(!is_null($client->is_public_employee) && strcmp($client->is_public_employee, "Sim") === 0) X @endif | Sim 
            | @if(!is_null($client->is_public_employee) && strcmp($client->is_public_employee, "Não") === 0) X @endif | Não</span>
    </div>
</div>

<div class="row">
    <div class="col-12 row-colored">
        <b>Marque as opções que se aplicam ao seu caso:</b> </br>
        <b>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> </br>
        <span>| @if(!is_null($client->has_fgts) && strcmp($client->has_fgts, "Sim") === 0) X @endif | Sim 
            | @if(!is_null($client->has_fgts) && strcmp($client->has_fgts, "Não") === 0) X @endif | Não</span> </br>
        <b>Mais de um comprador ou dependente?</b> </br>
        <span>| @if(!is_null($client->has_many_buyers) && strcmp($client->has_many_buyers, "Sim") === 0) X @endif | Sim  
            | @if(!is_null($client->has_many_buyers) && strcmp($client->has_many_buyers, "Não") === 0) X @endif | Não</span> </br>
    </div>
</div>

@if (strcmp($client->state, "Casado") === 0)

<div class="row">
    <div class="col-12"><b>Dados do Cônjugue do Proponente</b></div>
</div>
<div class="row">
    <div class="col-6 row-colored">
        <b>Nome Completo</b> </br>
        <span>{{ mb_strtoupper($client->name_dependent, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>CPF</b> </br>
        <span>{{ mb_strtoupper($client->cpf_dependent, 'UTF-8') }}</span>
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
        <span>{{ mb_strtoupper($client->rg_dependent, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Tipo de Documento</b> </br>
        <span>RG</span>
    </div>
    <div class="col-3 row-colored">
        <b>Òrgão de Emissão/UF</b> </br>
        <span>{{ mb_strtoupper($client->rg_dependent_organ, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Data de Emissão</b> </br>
        <span>{{ date_format(date_create($client->rg_dependent_date),"d/m/Y") }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Naturalidade</b> </br>
        <span>{{ mb_strtoupper($client->naturality_dependent, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Nacionalidade</b> </br>
        <span>{{ mb_strtoupper($client->nationality_dependent, 'UTF-8') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-9 row-colored">
        <b>Profissão</b> </br>
        <span>{{ mb_strtoupper($client->ocupation_dependent, 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Atendimento</b> </br>
        <span>| @if(strcmp($client->person_service, "Presencial") === 0) X @endif| Presencial 
            | @if(strcmp($client->person_service, "Whatsapp") === 0) X @endif| Whatsapp 
            | @if(strcmp($client->person_service, "Instagram") === 0) X @endif| Instagram</span>
    </div>
</div>

@if (!is_null($client->phone_dependent) || !is_null($client->email_dependent))
<div class="row">
    @if (!is_null($client->phone_dependent))
    <div class="col-3 row-colored">
        <b>DDD</b> </br>
        <span>{{ mb_strtoupper(substr($client->phone_dependent, 1, 2), 'UTF-8') }}</span>
    </div>
    <div class="col-3 row-colored">
        <b>Telefone</b> </br>
        <span>{{ mb_strtoupper(substr($client->phone_dependent, 4), 'UTF-8') }}</span>
    </div>
    @endif
    @if (!is_null($client->email_dependent))
    <div class="col-6 row-colored">
        <b>Email</b> </br>
        <span>{{ mb_strtoupper($client->email_dependent, 'UTF-8') }}</span>
    </div>
    @endif
</div>
@endif

<div class="row">
    <div class="col-6 row-colored">
        <b>Renda Bruta</b> </br>
        @if(!is_null($client->salary_dependent))
        <span>R$ {{ number_format($client->salary_dependent, 2, ',', '.') }}</span>
        @endif
    </div>
    <div class="col-6 row-colored">
        <b>Funcionário Público</b> </br>
        <span>| @if(!is_null($client->is_public_employee_dependent) && strcmp($client->is_public_employee_dependent, "Sim") === 0) X @endif | Sim 
            | @if(!is_null($client->is_public_employee_dependent) && strcmp($client->is_public_employee_dependent, "Não") === 0) X @endif | Não</span>
    </div>
</div>

<div class="row">
    <div class="col-12 row-colored">
        <b>Marque as opções que se aplicam ao seu caso:</b> </br>
        <b>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> </br>
        <span>| @if(!is_null($client->has_fgts_dependent) && strcmp($client->has_fgts_dependent, "Sim") === 0) X @endif | Sim 
            | @if(!is_null($client->has_fgts_dependent) && strcmp($client->has_fgts_dependent, "Não") === 0) X @endif | Não</span> </br>
        <b>Mais de um comprador ou dependente?</b> </br>
        <span>| @if(!is_null($client->has_many_buyers_dependent) && strcmp($client->has_many_buyers_dependent, "Sim") === 0) X @endif | Sim  
            | @if(!is_null($client->has_many_buyers_dependent) && strcmp($client->has_many_buyers_dependent, "Não") === 0) X @endif | Não</span> </br>
    </div>
</div>

@endif

@stop