<?php

if (!function_exists('amount_in_words')) {
    function amount_in_words(float $amount)
    {
        // $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
        // return $formatter->format($amount);

        $amountInCents = bcmul($amount, 100);
        $centReminder = $amountInCents % 100;
        $newAmount = ($amountInCents - $centReminder) / 100;

        $f = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);

        return sprintf("%s pesos y %s centavos", $f->format($newAmount), $f->format($centReminder));
    }
}

if (!function_exists('get_state_by_city')) {
    function get_state_by_city(string $city)
    {
        return 'Ciudad de México';
    }
}

if (!function_exists('render_payment_status_label')) {
    function render_payment_status_label(string $paymentStatus)
    {
        return match ($paymentStatus) {
            'CURRENT_LOAN' => ['AL CORRIENTE', 'success'],
            'CLOSE_TO_PAYMENT' => ['LIMITE CERCANO', 'warning'],
            'PAST_DUE' => ['EN MORA', 'danger'],
            'PENDING_TO_START' => ['POR INICIAR', 'dark'],
            'UNKNOWN' => ['NO ESTATUS', 'dark'],
            'SETTLED' => ['LIQUIDADO', 'primary'],
        };
    }
}
