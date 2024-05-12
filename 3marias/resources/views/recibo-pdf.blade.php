@extends('template')

@section('content')
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
<?php
    $month = date('m');
    $currentMonth = $MONTHS["$month"];

    $ticketDate = explode("-", $ticket->date);
    $ticketDay = $ticketDate[2];
    $ticketMonth = $MONTHS[$ticketDate[1]];
    $ticketYear = $ticketDate[0];
?>

<div id="container" class="container-fluid">
    <div class="row">
        <div class="col-12" style="margin-top: 10px;">
            <h6 style="text-align: justify">Recebi(emos) de <U>{{ mb_strtoupper($client->name, 'UTF-8') }}</U> a quantia de <U>R$ {{ number_format($ticket->value, 2, ',', '.') }}</U> reais correspondente a <U>{{ mb_strtoupper($ticket->description, 'UTF-8') }}</U> e para clareza firmo(amos) o presente na cidade de <U>{{ mb_strtoupper($contract->city_name, 'UTF-8') }}</U> no dia <U>   {{ $ticketDay }}  </U> de <U>  {{ $ticketMonth }}  </U> de <U>  {{ $ticketYear }}  </U>.</h6>
        </div>
        <div class="col-8"></div>
        <div class="col-4">
        Ibiapina - Ceará, {{ date('d') }} de {{ $currentMonth }} de {{ date('Y') }}
        </div>
        <div class="col-12">
            Atenciosamente, 
        </div>
        <div class="col-12 no-bottom-border">
            <div class="row" style="margin-top: 50px;">
                <div class="col-3"></div>
                <div class="col-6 text-center">
                    <div class="row" style="border-top: 1px solid gray;">
                        <div class="col-12">{{ mb_strtoupper($enterprise->name, 'UTF-8') }}</div>
                        <div class="col-12">CNPJ: {{ mb_strtoupper($enterprise->cnpj, 'UTF-8') }}</div>
                        <div class="col-12"><b>Contratada</b></div>
                    </div>
                </div>
                <div class="col-3"></div>
            </div>
        </div>
    </div>
</div>

@stop