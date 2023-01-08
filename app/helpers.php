<?php

if (! function_exists('amount_in_words')) {
    function amount_in_words(float $amount)
    {
        $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
        return $formatter->format($amount);
    }
}

if (! function_exists('get_state_by_city')) {
    function get_state_by_city(string $city)
    {
        return 'Ciudad de México';
    }
}