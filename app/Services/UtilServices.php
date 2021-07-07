<?php


namespace App\Services;


class UtilService
{
    public function dealInput($clearMask)
    {
        $clearedInput = preg_replace('/[^0-9]/is', '', $clearMask);
        return $clearedInput;
    }
}
