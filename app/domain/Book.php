<?php

class Book implements validProduct
{
    use Validations;

    public static function  validateAuthor($author, $errors) {
        if(strlen($author) < 3 ) {
            $errors[] = 'El nombre del autor no puede ser inferior a tres caracteres';
        }

        return $errors;
    }

    public static function validatePublisher($publisher, $errors) {

        if(strlen($publisher) < 3 ) {
            $errors[] = 'El nombre de la editorial no puede ser inferior a tres caracteres';
        }

        return $errors;

    }

    public static function validatePages($pages, $errors) {
        if($pages <= 0) {
            $errors[] = 'El numero de paginas no pueden ser 0 o negativas';
        }

        return $errors;
    }

}