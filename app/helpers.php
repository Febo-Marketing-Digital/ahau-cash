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

if (!function_exists('number_in_words')) {
    function number_in_words($amount)
    {
        $formatter = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
        return $formatter->format($amount);
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

if (!function_exists('display_alert'))
{
    function display_alert()
    {
        if (session()->has('message')) {
            list($type, $message) = explode('|', session()->get('message'));

            $format = '<p class="%s">%s</p>';

            if ($type == 'warning') {
                $format = '
                    <div class="flex flex-col content-center items-center mx-auto">
                    <div role="alert" class="alert alert-%s w-1/2 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 shrink-0 stroke-current"
                            fill="none"
                            viewBox="0 0 24 24">
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>%s</span>
                    </div></div>';
            }

            if ($type == 'info') {
                $format = '
                    <div class="flex flex-col content-center items-center mx-auto">
                    <div role="alert" class="alert alert-%s w-1/2 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            class="h-6 w-6 shrink-0 stroke-current">
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>%s</span>
                    </div></div>';
            }

            if ($type == 'success') {
                $format = '
                    <div class="flex flex-col content-center items-center mx-auto">
                    <div role="alert" class="alert alert-%s w-1/2 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 shrink-0 stroke-current"
                            fill="none"
                            viewBox="0 0 24 24">
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>%s</span>
                    </div></div>';
            }

            if ($type == 'error') {
                $format = '
                    <div class="flex flex-col content-center items-center mx-auto">
                    <div role="alert" class="alert alert-%s w-1/2 text-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 shrink-0 stroke-current"
                            fill="none"
                            viewBox="0 0 24 24">
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>%s</span>
                    </div></div>';
            }

            return sprintf($format, $type, $message);
        }

        return '';
    }
}
