<?php

class CourseDomain implements ValidProduct
{
    use Validations;


    public static function validateObjectivePublic($people, $errors) {

        if(empty($people)) {
            $errors[] = 'El publico objetivo del curso es obligatorio';
        }
        return $errors;
    }

    public static function validateObjetives($objetives, $errors){

        if (empty($objetives)) {
            $errors[] = 'Los objetivos del curso son necesarios';
        }
        return $errors;
    }

    public static function validateNecesites($necesites, $errors){

        if (empty($necesites)) {
            $errors[] = 'Los requisitos del curso son necesarios';
        }
        return $errors;
    }

}