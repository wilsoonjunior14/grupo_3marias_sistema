<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>3Marias</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!-- Bootstrap -->
        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Crypto JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>

        <!-- Chart JS -->
        <script src="{{ URL::asset('js/library/chart.js') }}" type="text/javascript"></script>

        <!-- Application files -->
        <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                background: whitesmoke !important;
            }
        </style>
    </head>
    <body>

        <div id="root"></div>

        <div id="loadingModal" class="modal">
            <div class="modal-dialog" style="position: absolute; top: 45%; left: 45%">
                <div class="modal-content" style="border: none; background: none">
                    <div class="modal-body">
                        <!-- <div class="spinner-border text-info" role="status" style="width: 100px; height: 100px">
                          <span class="sr-only"></span>
                        </div> -->
                        <img src="{{ URL::asset('img/preloader.gif') }}" />
                    </div>
                </div>
            </div>
        </div>

        <div id="errorModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Atenção</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="border-left: 10px solid red; padding-left: 10px" id="textErrorModal"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="successModal" class="modal">
             <div class="modal-dialog">
                  <div class="modal-content">
                       <div class="modal-header">
                            <h5 class="modal-title">Atenção</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                       </div>
                       <div class="modal-body">
                            <p style="border-left: 10px solid green; padding-left: 10px" id="textSuccessModal"></p>
                       </div>
                       <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                       </div>
                  </div>
             </div>
        </div>

        <!-- Indicates the environment on application -->
        <?php if (strcmp(env("APP_ENV"), "local") === 0) { ?>
            <style>
                .dev-box{
                    position: absolute;
                    top: 10px;
                    left: 70%;
                    padding: 10px;
                    border: 2px solid red;
                    border-radius: 25px;
                    font-weight: bold;
                    color: black;
                    background-color: red;
                }
            </style>
            <div class="dev-box">
                DEV
            </div>
        <?php } ?>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('js/library/popper.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('js/library/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('js/library/jquery.mask.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('js/library/wcaptcha.js') }}" type="text/javascript"></script>

        <!-- JQuery Scripts -->
        <script type="text/javascript">
                function updateFields() {
                    $(".cpf").mask("000.000.000-00");
                    $(".phoneNumber").mask("(00)00000-0000");
                    $(".cep").mask("00000-000");
                    $(".birthdate").mask("00/00/0000");
                }

                function loading(flag) {
                    if (flag === true){
                        $("#loadingModal").modal("show");
                        $("body").css("pointer-events", "none");
                    } else {
                        $("#loadingModal").modal("hide");
                        $("body").css("pointer-events", "all");
                    }
                }

                function showErrorMessage(message) {
                    if (message.errors) {
                        if (message.errors.request){
                            $("#textErrorModal").text(message.errors.request);
                        } else {
                            $("#textErrorModal").text(message.errors);
                        }
                    } else if (message.message) {
                        $("#textErrorModal").text(message.message);
                    } else {
                        $("#textErrorModal").text(message);
                    }

                    $("#errorModal").modal("show");
                }

                function showSuccessMessage(message) {
                    $("#textSuccessModal").text(message);
                    $("#successModal").modal("show");
                }
        </script>

        <!-- Botman chatbot -->
        <script>
            var botmanWidget = {
                title: "BusqBot",
                placeholderText: "Escreva sua mensagem",
                aboutLink: "http://localhost:3000",
                mainColor: "#31B573",
                bubbleAvatarUrl: "http://localhost:3000/img/question.png",
                bubbleBackground: "#316573",
                aboutText: 'ssdsd',
                introMessage: "<b>Olá Seja Bem vindo ao 3Marias!</b> ✋✋✋ </br>" +
                "<b>O que está buscando?</b> </br>" +
                "1 - Cadastrar na plataforma </br>" +
                "2 - Acessar a plataforma </br>" +
                "3 - Recuperar a senha </br>" +
                "4 - Enviar um feedback </br>"
            };
        </script>
        <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

        <!-- App -->
        <link rel="stylesheet" href="{{ URL::asset('application/build/static/css/main.c61a530d.css') }}">
        <script src="{{ URL::asset('application/build/static/js/main.640f80cc.js') }}"></script>

    </body>
</html>
