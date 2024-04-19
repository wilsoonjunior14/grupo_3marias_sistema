
# 3Marias

## Team

| Team Members                      | Core Function     |
|-----------------------------------|-------------------|
| Francisco Wilson Rodrigues Junior | Software Engineer |

## Architecture

### General

```mermaid
  graph LR;
CLIENTE == Cliente Cadastrado ==> PROPOSTA;
CLIENTE == Cliente Cadastrado  ==> FICHA_CLIENTE;
PROPOSTA == Proposta Validada ==> CONTRATO;
PROPOSTA == Proposta Validada  ==> MODELO_PROPOSTA;
CONTRATO == Contrato Cadastrado ==> MODELO_CONTRATO;
CONTRATO == Contrato Cadastrado ==> CENTRO_DE_CUSTO; 
CONTRATO == Contrato Cadastrado ==> CONTAS_A_RECEBER;
```

```mermaid
  graph LR;
CATEGORIA_PRODUTO --> PRODUTO;
PRODUTO --> ORDEM_DE_COMPRA;
ORDEM_DE_COMPRA --> CONTAS_A_PAGAR;
ORDEM_DE_COMPRA --> CENTRO_DE_CUSTO;
```

```mermaid
  graph LR;
CATEGORIA_SERVICO --> SERVICO;
SERVICO --> ORDEM_DE_SERVICO;
ORDEM_DE_SERVICO --> CONTAS_A_PAGAR;
ORDEM_DE_SERVICO --> CENTRO_DE_CUSTO;
```

### Backend

### Frontend

## Technologies

### Backend Technologies

- PHP 8-apache
- MySQL 5.7
- Laravel
- Sanctum
- Rest APIs
- Docker
- PHPUnit

### Frontend Technologies

- ReactJs
- Bootstrap
- CryptoJS

### AWS

- Apply these changes on each bucket

```json
{
    "Version": "2008-10-17",
    "Statement": [
        {
            "Sid": "AllowPublicRead",
            "Effect": "Allow",
            "Principal": {
                "AWS": "*"
            },
            "Action": [
                "s3:GetObject"
            ],
            "Resource": [
                "arn:aws:s3:::bucket_name/*"
            ]
        }
    ]
}
```

## Changelog