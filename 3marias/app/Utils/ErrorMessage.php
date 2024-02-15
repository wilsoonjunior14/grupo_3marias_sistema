<?php

namespace App\Utils;

/**
 * Class with all constants to be used on application;
 */
class ErrorMessage {

    // The list of general messages
    public static $ID_NOT_EXISTS = "Identificador de %s não existe.";
    public static $ID_NOT_PROVIDED = "Identificador de %s não informado.";
    public static $ENTITY_EXISTS = "Registro com informações já existentes.";
    public static $ENTITY_NOT_FOUND = "Nenhum registro foi encontrado.";
    public static $ENTITY_NOT_FOUND_PATTERN = "Nenhum registro de %s foi encontrado.";
    public static $ENTITY_DUPLICATED = "Registro de %s já registrado em %s.";
    public static $EMAIL_ALREADY_EXISTS = "Email já registrado no sistema.";
    public static $FIELD_NOT_PROVIDED = "Campo %s não informado.";
    public static $FIELD_INVALID = "Campo %s está inválido.";
    public static $FIELD_REQUIRED = "Campo %s é obrigatório.";
    public static $FIELD_MUSTBE_STRING= "Campo %s deve conter somente letras.";
    public static $FIELD_SHORT= "Campo %s deve conter no mínimo %s caracteres.";
    public static $FIELD_LONG= "Campo %s permite no máximo %s caracteres.";
    public static $METHOD_NOT_IMPLEMENTED = "Recurso não implementado.";
}