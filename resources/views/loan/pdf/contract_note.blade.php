<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Contract</title>
</head>
<body>

    <div style="margin: 0 auto; text-align: center;">
        <div class="main" style="text-align: justify; max-width: 800px;">
            <p>
                CONVENIO QUE CELEBRAN POR UNA PARTE ANTONIO EMMANUEL FLORES ÁLVAREZ BUYLLA, 
                A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ "EL PRESTAMISTA" Y POR OTRA PARTE <strong>{{ strtoupper($client_fullname) }}</strong>, 
                A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ "EL PRESTATARIO"; 
                SIENDO QUE EN SU CONJUNTO SE LES DENOMINARÁ "LAS PARTES", QUIENES SE SUJETAN AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS:
            </p>

            <h2 style="text-align: center;">DECLARACIONES</h2>

            <ul style="list-style-type: none;">
                <li>
                    <h3>I. Declara "EL PRESTAMISTA":</h3>
                    <ol type="a">
                        <li>Que es mexicano por nacimiento, mayor de edad, cuenta con la capacidad legal y los recursos económicos necesarios para obligarse en los términos del presente contrato.</li>
                        <li>Que cuenta con la capacidad legal y económica para cumplir con las obligaciones que se generan el presente convenio y que es su voluntad adquirirlas.</li>
                        <li>Que los recursos con los que cumplirá con las obligaciones que se generen por la celebración del presente contrato proceden de una fuente lícita.</li>
                        <li>Que señala como su domicilio el ubicado en Cerrada de Niños Héroes número 26, Casa B, Colonia San Pedro Mártir, Alcaldía Tlalpan, Código Postal 14650, Ciudad de México.</li>
                    </ol>
                </li>

                <li>
                    <h3>II. Declara el "EL PRESTATARIO":</h3>
                    <ol type="a">
                        <li>Que es mexicano por nacimiento, mayor de edad, cuenta con la capacidad legal y los recursos económicos necesarios para obligarse en los términos del presente contrato.</li>
                        <li>Que es su voluntad obligarse en los términos del presente contrato.</li>
                        <li>Que señala como su domicilio el ubicado en <strong>{{ $client_address }}</strong>. </li>
                        <li>Que los recursos con los que pagará las obligaciones que se generen por la celebración del presente contrato proceden de una fuente lícita.</li>
                    </ol>
                </li>

                <li>
                    <h3>III. Declaran "LAS PARTES":</h3>
                    <p style="padding-left: 15px;">ÚNICO. Se reconocen mutuamente la personalidad que ostentan y acreditan estar de acuerdo en obligarse, conviniendo en celebrar el presente convenio al tenor de las siguientes:</p>
                </li>
            </ul>


            <h2 style="text-align: center;">CLÁUSULAS</h2>

            <div>
                <p><strong>PRIMERA.</strong> OBJETO DEL CONTRATO. <strong>"LAS PARTES"</strong> convienen que “EL PRESTAMISTA” entregará a "EL PRESTATARIO" el día {{ $loan_date }}, la cantidad de ${{ $loan_amount }} pesos en calidad de préstamo, lo anterior, mediante transferencia bancaria a la cuenta número {{ $account_number }} en la Institución financiera {{ $bank_name }} a nombre de {{ $account_holder_name }} .</p>
                <p>Asimismo, "EL PRESTATARIO" se obliga a pagar a "EL PRESTAMISTA" la cantidad referida en el párrafo anterior, más una cantidad adicional como contraprestación en favor de este último, lo anterior, en los términos y condiciones pactados en la cláusula SEGUNDA.</p>
                <p><strong>SEGUNDA.</strong> PAGO DEL PRÉSTAMO. <strong>"LAS PARTES"</strong> acuerdan que “EL PRESTATARIO” pagará a “EL PRESTAMISTA” el préstamo que recibe por la cantidad de ${{ $loan_amount }} pesos mediante {{ $installments_total }} mensualidades, cantidad mensual a la cual se le agregará la cantidad de ${{ $loan_roi }} pesos como contraprestación en beneficio de "EL PRESTAMISTA", por lo que, la obligación de pago adquirida por "EL PRESTATARIO" deberá ser cumplida de la siguiente manera: </p>
            </div>

            <ol>
                <li>
                    Forma de pago: Mediante transferencias o depósitos bancarios a la cuenta número 00106842017 cuenta clabe 044180001068420170 de la institución financiera Scotiabank nombre de ANTONIO EMMANUEL FLORES ÁLVAREZ BUYLLA.
                </li>
                <li>
                    Cantidad y fecha de pago:

                    <table style="width: 100%; border: 1px solid black;">
                        <thead>
                            <tr>
                                <th>FECHA DE PAGO</th>
                                <th>CANTIDAD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($installments as $installment)
                            <tr>
                                <td>{{ $installment->end_date }}</td>
                                <td>{{ $installment->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </li>
            </ol>


            <strong>"LAS PARTES"</strong> convienen que el comprobante de la transferencia o depósito bancario fungirá como comprobante del cumplimiento de la obligación de pago referida en la presente cláusula.</p>
            
            
            <p><strong>TERCERA.</strong> NATURALEZA CIVIL DEL CONTRATO. <strong>"LAS PARTES"</strong> convienen que el destino de los recursos económicos que constituyen el objeto del presente convenio no serán utilizados para actos de comercio, por lo que, la naturaleza del presente contrato es civil y que está regulado por las disposiciones contenidas en el Código Civil para el Distrito Federal, ahora Ciudad de México, en ese sentido, entre “EL PRESTAMISTA” y “EL PRESTATARIO” no hay una relación mercantil, laboral o du cualquier tipo, ni existe subordinación alguna entre ellos.</p>
            <p><strong>CUARTA.</strong> MODIFICACIONES. <strong>"LAS PARTES"</strong> convienen que cualquier modificación al presente contrato debe constar por escrito y estar firmado por <strong>"LAS PARTES"</strong>.</p>
            <p><strong>QUINTA.</strong> VIGENCIA. La vigencia del presente convenio es indeterminada.</p>


            <p><strong>SEXTA.</strong> CAUSAS DE RECISIÓN. "LAS PARTES" convienen que el presente contrato podrá ser rescindido en los casos que a continuación se señalan:</p>

            A. Con responsabilidad para "EL PRESTATARIO":

                I. No realizar cualquiera de los pagos acordados en el tiempo, lugar y forma establecidos.

                II. Incumplir con cualquiera de las obligaciones derivadas del presente contrato.

            B. Con responsabilidad para "EL PRESTAMISTA":

                I. Si "EL PRESTAMISTA", no realiza el depósito referido en la cláusula PRIMERA.

                II. Incumplir con cualquiera de las obligaciones derivadas del presente contrato.

            <p><strong>SÉPTIMA.</strong> FORMA DE LLEVAR A CABO LA RECISIÓN. "LAS PARTES" convienen que, en caso de recisión del presente contrato, deberá hacerse previo aviso por escrito y firma de enterado, por lo menos quince días hábiles anteriores por cualquiera de las partes.</p>

            <p><strong>OCTAVA.</strong> RECONOCIMIENTO CONTRACTUAL. El presente contrato constituye la totalidad de los acuerdos entre "LAS PARTES", en relación con el objeto de éste y deja sin efecto cualquier otra negociación, obligación, contrato o comunicación entre éstas, ya sea verbal o escrita con anterioridad o al inicio de la vigencia del presente instrumento, Asimismo, “LAS PARTES” manifiestan que, en la celebración del presente contrato no existe error, dolo, mala fe, violencia ni vicio alguno en el consentimiento de la voluntad.</p>

            <p><strong>NOVENA.</strong> CLÁUSULA PENAL. "LAS PARTES" convienen que en caso de que “EL PRESTATARIO” incumpliere con las obligaciones de pago convenidas en el presente acuerdo de voluntades o cualquier otra, se obliga a pagar a “EL PRESTAMISTA”, una penalidad equivalente a $300 pesos por cada día que transcurra una vez que se haya excedido cualquier fecha de pago referida en la cláusula SEGUNDA del presente documento, cantidad que será exigible en el momento en el que se genere, así como al pago de los daños y perjuicios que esto llegare a ocasionar.</p>

            <p><strong>DÉCIMA.</strong> JURISDICCIÓN Y COMPETENCIA. Para la interpretación y cumplimiento del presente contrato, así como, para todo lo no previsto en el mismo, las partes se someten a la jurisdicción de los tribunales y las leyes aplicables en la Ciudad de México, por lo que, renuncian al fuero que por razón de su domicilio presente o futuro pudiera corresponderles.</p>

            <p>Enteradas las partes del contenido y alcance legal del presente contrato, el cual entienden y manifiestan su conformidad con este, firmando por duplicado en la Ciudad de México, a los {{ now()->format('d') }} días del mes de {{ now()->format('F') }} de {{ now()->format('Y') }}.</p>


            <table style="width: 100%; height: 400px; text-align: center;">
                <thead>
                    <tr>
                        <th>"EL PRESTAMISTA"</th>
                        <th>"EL PRESTATARIO"</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <br><br><br><br>
                            <p>ANTONIO EMMANUEL FLORES ÁLVAREZ BUYLLA</p>
                        </td>
                        <td>
                        <br>
                            <br><br><br><br>
                            <p>____________________________________________</p>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>



