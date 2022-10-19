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
        if(count($date) > 3) {
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
        $search = [' ', '*', '!', '@', '?', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü', '¿', '¡'];
        $replace = ['-', '', '', '', '', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u', 'U', '', ''];
        $file = str_replace($search,$replace, $string);

        return $file;
    }

    //TODO modificar luego.
    public static function resizeImage($image, $newWidth)
    {
        $file = 'img/' . $image;

        //comprobar que devuelve si no metemos un filename correcto
        //segun php.net fileinfo
        $info = getimagesize($file);
        $width = $info[0];
        $height = $info[1];
        $type = $info['mime'];

        $factor = $newWidth / $width;
        $newHeight = $factor * $height;

        $image = imagecreatefromjpeg($file);

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($canvas, $image, 0,0,0,0,$newWidth, $newHeight,$width, $height);

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

    public static function imageFile($file)
    {
        //soluciona error warning de acceder al array en la posicion 2 de abajo
        if(!getimagesize($file)) {
            return false;
        }

        $imageArray = getimagesize($file);
        $imageType = $imageArray[2];

        return (bool) (in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG]));
    }



}