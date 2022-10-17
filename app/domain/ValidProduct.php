<?php

interface ValidProduct
{
    public function validateName($name);

    public function validateDescription($description);

    public function validatePrice($price);

    public function validateDiscount($discount);

    public function validateSend($send);

    public function validateSendLowerThanPrice($send, $price);

    public function validatePublishedDate($published);
}