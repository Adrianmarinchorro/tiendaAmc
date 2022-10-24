<?php

trait Validations
{

    public static function validateName($name, $errors): array
    {
        if (empty($name)) {
            $errors[] = 'El nombre del producto es requerido';
        }
        if (strlen($name) < 3) {
            $errors[] = 'El nombre debe tener como minimo 3 caracteres';
        }

        return $errors;
    }

    public static function validateDescription($description, $errors): array
    {
        if (empty($description)) {
            $errors[] = 'La descripción del producto es requerida';
        }

        return $errors;
    }

    public static function validatePrice($price, $errors): array
    {
        if (!is_numeric($price)) {
            $errors[] = 'El precio del producto debe de ser un número';
        }
        if($price < 0) {
            $errors[] = 'El precio no puede ser negativo';
        }

        return $errors;
    }

    public static function validateDiscount($discount, $errors)
    {
        if (!is_numeric($discount)) {
            $errors[] = 'El descuento del producto debe de ser un número';
        }

        if($discount < 0) {
            $errors[] = 'El descuento no puede ser negativo';
        }

        return $errors;
    }

    public  static function validateSend($send, $errors)
    {
        if (!is_numeric($send)) {
            $errors[] = 'Los gastos de envío del producto deben de ser numéricos';
        }
        if($send < 0) {
            $errors[] = 'El gasto de envio no puede ser negativo';
        }

        return $errors;
    }

    public static function validateDiscountLowerThanPrice($discount, $price, $errors): array
    {
        if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
            $errors[] = 'El descuento no puede ser mayor que el precio';
        }
        return $errors;
    }

    public static function validatePublishedDate($published, $errors): array
    {
        if($published == '') {
            $errors[] = 'El campo fecha debe ser rellenado';
            return $errors;
        }
        if (!Validate::date($published) ) {
            $errors[] = 'La fecha o su formato no es correcto';
        } elseif (!Validate::dateDif($published)) {
            $errors[] = 'La fecha de publicación no puede ser anterior a hoy';
        }

        return $errors;
    }

    public static function validateImage($image, $errors) {

        if(!Validate::HasFile($image)) {
            $errors[] = 'No ha insertado ninguna imagen';
            return $errors;
        }

        if(!Validate::imageFile($_FILES['image']['tmp_name'])) {
            $errors[] =  'El formato de imagen no es aceptado';
            return $errors;
        }

        //implementado en el validate::File
        //$image = strtolower($image);

        if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
            $errors[] = 'Error al subir el archivo de imagen';
            return $errors;
        }
        //Controla que no se deberia cargar la imagen en img si hay un error en  el formulario
        if($errors) {
            return $errors;
        }

        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $image);
        Validate::resizeImage($image, 240);
        return $errors;
    }
}