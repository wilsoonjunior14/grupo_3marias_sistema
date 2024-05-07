@extends('template')

@section('content')

<div id="container" class="container-fluid">
    <div class="row">
        <div class="col-12" style="margin-top: 10px;">
            <h6 style="text-align: justify">Recebi(emos) de <U>{{ $client->name }}</U> a quantia de <U>R$ XXXX</U> reais correspondente a <U>DESCRIÇÃO DO PAGAMENTO DESCRIÇÃO DO PAGAMENTO DESCRIÇÃO DO PAGAMENTO DESCRIÇÃO DO PAGAMENTO</U> e para clareza firmo(amos) o presente na cidade de <U>IBIAPINA</U> no dia <U>   07  </U> de <U>  Maio  </U> de <U>  2024  </U>.</h6>
        </div>
        <div class="col-8"></div>
        <div class="col-4">
        Ibiapina, 05 de Maio de 2024
        </div>
        <div class="col-12">
            Atenciosamente, 
        </div>
        <div class="col-12 no-bottom-border">
            <div class="row" style="margin-top: 50px;">
                <div class="col-3"></div>
                <div class="col-6 text-center">
                    <div class="row" style="border-top: 1px solid gray;">
                        <div class="col-12">Francisco Wilson Rodrigues Júnior</div>
                        <div class="col-12">CPF: 038.192.773-31</div>
                        <div class="col-12"><b>Assinatura</b></div>
                    </div>
                </div>
                <div class="col-3"></div>
            </div>
        </div>
    </div>
</div>

@stop