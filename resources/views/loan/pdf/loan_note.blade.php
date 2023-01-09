<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAGARÉ TOTAL</title>
    <style>
        .signature-table {
            width: 100%; 
            height: 400px; 
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>PAGARÉ</h2>

    <p>Bueno por $ {{ $loan_amount }}</p>
    
    <p>En la Ciudad de México, México a {{ now()->format('j') }} de {{ now()->format('F') }} de {{ now()->format('Y') }}</p>

    <p>Debo y pagaré incondicionalmente a la orden de ANTONIO EMMANUEL FLORES ÁLVAREZ BUYLLA o a quien sus derechos represente la cantidad de ${{ $loan_amount }} ({{ strtoupper(amount_in_words($loan_amount)) }} 00/100 M.N.), en el domicilio ubicado en Cerrada de Niños Héroes número 26, interior B, Colonia San Pedro Mártir, Alcaldía Tlalpan en Ciudad de México, en fecha {{ $created_date }} ({{ amount_in_words($year_to_convert) }}).</p>

    <p>Valor recibido a mi entera satisfacción, en otro sentido, desde la fecha de vencimiento de este documento hasta el día de su liquidación causará intereses moratorios al tipo de {{ $roi }} ({{ strtoupper(amount_in_words(floatval($roi))) }} POR CIENTO) MENSUAL pagadero conjuntamente con el principal.</p>


    <table class="signature-table">
        <thead>
            <tr>
                <th>
                    NOMBRE Y DATOS DEL DEUDOR
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Nombre: {{ strtoupper($fullname) }}
                </td>
                <td>ACEPTO</td>
            </tr>
            <tr>
                <td>
                    <br><br>
                    <p>Domicilio: {{ $address->street }} {{ $address->house_number }} {{ $address->locality }} {{ $address->province }} {{ $address->city }} {{ $address->state }}
                    C.P. {{ $address->postal_code }}</p>
                </td>
                <td>
                    <br><br><br><br>
                    <p>{{ strtoupper($fullname) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>