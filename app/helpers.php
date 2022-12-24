<?php

if (! function_exists('amount_in_words')) {
    function amount_in_words(float $amount)
    {
        $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
        return $formatter->format($amount);
    }
}