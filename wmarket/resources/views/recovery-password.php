<body>
        <center>
            <h1>Recuperação de Senha</h1>
            <div class="panel">
                <h3><b>Alguém solicitou recuperação de senha para 
                a seguinte conta: <?= $data["name"] ?>
                </b>
                </h3>
                <p>
                    Para resetar sua senha, clique no link abaixo:
                </p>
                <p>
                    <a href="<?= "http://localhost:3000/#!/recovery/{$data['recovery_password_token']}" ?>" style="text-align: 'center'; padding: 10px"><button class="btn-link">
                        Clique aqui para resetar sua senha
                    </button></a>
                </p>
                <p>
                    Seu email é: <span class="link"><?= $data["email"] ?></span>
                </p>
                <p>Caso você não tenha solicitado a recuperação de senha, apenas ignore este email.</p>
            </div>
            <div>
                Busquei App
            </div>
        </center>

        <style>
            body {
                background: lightgray;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .panel {
                padding: 20px;
                margin: 16px;
                background: white;
                box-shadow: 3px 3px 5px gray;
                border-radius: 10px;
                width: 85%;
                min-height: 200px;
                text-align: center;
            }
            .btn-link {
                border-radius: 5px;
                height: 40px;
                padding: 10px;
                background: rgb(37, 112, 233);
                color: white;
                text-align: center;
            }
            .link {
                color: rgb(37, 112, 233);
            }
        </style>
</body>