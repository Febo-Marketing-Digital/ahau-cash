<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>{{ $titulo }}</h1>

    <p>Sra/ita {{ $user['name'] . ' ' . $user['lastname'] }}</p>

    <p>Prestamo: {{ $loan_amount }}</p>

    <p>Pago final: {{ $payment_amount }} ({{ $roi }}) a 6 meses.</p>

    <p>Pago mensual: $ 4000</p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Pago</th>
                <th>Debe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ now() }}</td>
                <td>$ 4000</td>
                <td>$ 20000</td>
            </tr>
            <tr>
                <td>{{ now()->addMonth() }}</td>
                <td>$ 4000</td>
                <td>$ 16000</td>
            </tr>
            <tr>
                <td>{{ now()->addMonths(2) }}</td>
                <td>$ 4000</td>
                <td>$ 12000</td>
            </tr>
            <tr>
                <td>{{ now()->addMonths(3) }}</td>
                <td>$ 4000</td>
                <td>$ 8000</td>
            </tr>
            <tr>
                <td>{{ now()->addMonths(4) }}</td>
                <td>$ 4000</td>
                <td>$ 4000</td>
            </tr>
            <tr>
                <td>{{ now()->addMonths(5) }}</td>
                <td>$ 4000</td>
                <td>$ 0</td>
            </tr>
        </tbody>
    </table>
</body>
</html>