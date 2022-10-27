<?php

class Validate
{
    public static function number($string)
    {
        $search = [' ', '€', '$', ','];
        $replace = ['', '', '', ''];

        return str_replace($search, $replace, $string);
    }

    public static function date($string)
    {
        $date = explode('-', $string);

        // si no tiene tres campos (dia, mes y año)
        if (count($date) > 3) {
            return false;
        }


        return checkdate($date[1], $date[2], $date[0]);
    }

    public static function dateDif($string)
    {
        $now = new DateTime();
        $date = new DateTime($string);

        return ($date > $now);
    }


    public static function file($string)
    {
        if (!$string) {
            return '';
        }

        $search = [' ', '*', '!', '@', '?', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü', '¿', '¡'];
        $replace = ['-', '', '', '', '', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u', 'U', '', ''];
        $file = str_replace($search, $replace, $string);
        $file = strtolower($file);

        return $file;
    }

    //TODO modificar luego.
    public static function resizeImage($image, $newWidth)
    {
        $file = 'img/' . $image;

        //comprobar que devuelve si no metemos un filename correcto
        //segun php.net fileinfo es mejor
        $info = getimagesize($file);
        $width = $info[0];
        $height = $info[1];
        $type = $info['mime'];

        $factor = $newWidth / $width;
        $newHeight = round($factor * $height, 0, PHP_ROUND_HALF_DOWN);

        $imageArray = getimagesize($file);
        $imageType = $imageArray[2];

        if ($imageType == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($file);
        } elseif ($imageType == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($file);
        }


        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagejpeg($canvas, $file, 80);
    }

    public static function text($string)
    {
        $search = ['^', 'delete', 'drop', 'truncate', 'exec', 'system'];
        $replace = ['-', 'dele*te', 'dr*op', 'trunca*te', 'ex*ec', 'syst*em'];
        $string = str_replace($search, $replace, $string);
        $string = addslashes(htmlentities($string));

        return $string;
    }

    public static function HasFile($image)
    {
        return $image;
    }

    public static function imageFile($file)
    {
        //soluciona error warning de acceder al array en la posicion 2 de abajo
        if (!getimagesize($file)) {
            return false;
        }

        $imageArray = getimagesize($file);
        $imageType = $imageArray[2];

        //TODO: permitir guardar restos de extensiones de imagenes.
        //cambio quite IMAGETYPE_PNG del array.
        return (bool)(in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG]));
    }


}