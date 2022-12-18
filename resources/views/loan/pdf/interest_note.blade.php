<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interest Note</title>
</head>
<body>
<h2>PAGARÉ</h2>

<p>Bueno por $ {{ $loan_insterest_amount }}</p>

<p>En la Ciudad de México, México a 5 de julio de 2022</p>

<p>Debo y pagaré incondicionalmente a la orden de ANTONIO EMMANUEL FLORES ÁLVAREZ BUYLLA o a quien sus derechos represente la cantidad de ${{ $loan_insterest_amount }}
(MONTO_LETRAS PESOS 00/100 M.N.), en el domicilio ubicado en Cerrada de Niños Héroes número 26, interior B, Colonia San Pedro Mártir, Alcaldía Tlalpan en Ciudad de México, en fecha FECHA (dos mil veintitrés).</p>

<p>Valor recibido a mi entera satisfacción, en otro sentido, desde la fecha de vencimiento de este documento hasta el día de su liquidación causará intereses moratorios al tipo de 10% (DIEZ POR CIENTO) mensual pagadero conjuntamente con el principal.</p>

    <table>
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
                    Domicilio: {{ $address->street }} {{ $address->house_number }} {{ $address->locality }} {{ $address->province }} {{ $address->city }} {{ $address->state }}
                    C.P. {{ $address->postal_code }}
                </td>
                <td>
                {{ strtoupper($fullname) }}
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>