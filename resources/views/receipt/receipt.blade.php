<!DOCTYPE html>
<html lang="en">

<head>

    <title>Cash Time</title>
    <style>
        :root {
            --color-primary: #F6187F;
            --color-secondary: #0076DA;
            --fontColorPrimary: #414141;
            --fontColorSecondary: #3c3c3c;
        }

        * {
            margin: 0px;
            padding: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            margin-left: 32px;
            margin-right: 32px;
            margin-top: 20px;

        }

        .tableHeader {
            width: 100%;
            padding-bottom: 10px;
        }

        .tableHeader .logo_img {
            width: 110px;
        }

        .tableHeader .logo_text {
            padding: 0px;
            font-size: 26px;
            font-family: Arial, sans-serif;
            color: var(--color-primary);
        }

        .tableHeader .receipt_logo {
            width: 525px;
        }

        .tableHeader .sub_logo {
            padding: 0px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            color: var(--fontColorSecondary);
        }


        .tableHeader .logo_description {
            font-size: 15px;
            font-family: Arial, sans-serif;
            color: var(--fontColorSecondary);
        }

        .tableHeader .address {
            font-size: 10px;
            font-family: Arial, sans-serif;
            color: var(--fontColorSecondary);
        }

        .receipt_info {
            width: 190px;
            border: 1px solid var(--color-primary);
            border-collapse: collapse;
            border-radius: 15px;
            padding: 4px;
        }

        .receipt_info table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }

        .receipt_info table tbody {
            text-align: center;
            margin: auto;
        }

        .receipt_info tbody tr {}

        .receipt_info .receipt_type {
            font-size: 12px;
            text-align: center font-family: Arial, sans-serif;
            color: var(--color-primary);

        }

        .receipt_info .receipt_series {
            font-size: 16px;
            color: var(--color-primary);

        }

        .receipt_info .receipt_ruc {
            font-size: 12px;
            text-align: center font-family: Arial, sans-serif;
            color: var(--color-secondary);

        }

        .receipt_info .receipt_series {
            font-size: 12px;
            text-align: center font-family: Arial, sans-serif;
            color: var(--fontColorPrimary);
        }


        /* table productos */
        .table_products {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }

        .table_products thead {
            border-top: 1.2px solid var(--color-primary);
            border-bottom: 1.2px solid var(--color-primary);
        }

        .table_products thead th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        .table_products tbody tr td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .table_products thead th:nth-child(1) {
            width: 45px;
        }

        .table_products thead th:nth-child(2) {
            width: 70px;
        }

        .table_products thead th:nth-child(3) {
            width: 375px;
        }

        .table_products thead th:nth-child(4) {
            text-align: right;
            width: 58px;
        }

        .table_products thead th:nth-child(5) {
            text-align: right;
            width: 65px;
        }

        .table_products thead th:nth-child(6) {
            text-align: right;
            width: 55px;
        }

        /* table info */

        .table_info {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
            border-top: 1.2px solid var(--color-primary);

            padding-bottom: 5px;
        }

        .table_info tbody {}

        .table_info .row_one {
            width: 110px;
            font-weight: bold;
            padding-bottom: 5px;
        }

        .table_info .row_two {
            width: 40px;
            text-align: right;
            font-weight: bold;
            padding-bottom: 5px;
        }

        .table_info .row_three {
            padding-left: 5px;
            padding-bottom: 5px;
            color: var(--fontColorSecondary);
        }


        /*table totals */

        .table_totals {
            border-top: 1.2px solid var(--color-primary);
            border-bottom: 1.2px solid var(--color-primary);
            width: 100%;
            font-size: 10px;
            padding-top: 6px;
            padding-bottom: 10px;
        }

        .table_totals thead th:nth-child(1) {
            width: 400px;
        }

        .table_totals thead th:nth-child(2) {
            width: 165px;
            text-align: right;
        }

        .table_totals .value {
            text-align: right;
        }

        .table_totals .bold {
            font-weight: bold;
        }

        .bold {
            font-weight: bold;
        }

        .table_totals .totals {
            font-weight: bold;
            font-size: 12px;
        }

        .table_products tbody {}

        .box_border {
            margin-top: 10px;
            width: 97%;
            border: 1px solid var(--color-primary);
            border-radius: 6px;
            padding: 10px;
        }

        .box_border_content {
            margin: auto;
            text-align: center;
            font-size: 12px;
            width: 75%;
        }

        .code_qr_content {
            margin-top: 15px;
            width: 100%;
            display: flex;
        }

        .code_qr_content table {
            margin: auto;
        }

        .code_qr_content .box_info {
            font-size: 10px;
            width: 250px;
            padding: 8px;
        }

        .code_qr_content .box_qr {
            padding: 8px;
            border: 1px solid var(--color-primary);
            border-radius: 5px;
        }

        .receipt {
            width: 100%;
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body>

    <table class="tableHeader">
        <tbody>
            <tr>
                <td class="receipt_logo">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('images/logo.png') }}" class="logo_img" />
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="logo_text">CAUTIVA</td>
                                            </tr>
                                            <tr>
                                                <td class="logo_description">MODA Y ESTILO</td>
                                            </tr>
                                            <tr>
                                                <td class="sub_logo">CASH TIME E.I.R.L</td>
                                            </tr>
                                            <tr>
                                                <td class="address">JR. BOLOGNESI 523 SAN MARTIN - SAN MARTIN - TARAPOTO
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="receipt_info">
                    <table>
                        <tbody>
                            <tr class="receipt_type">
                                <td>BOLETA DE VENTA</td>
                            </tr>
                            <tr class="receipt_type">
                                <td>ELECTRÓNICA</td>
                            </tr>
                            <tr class="receipt_ruc">
                                <td>RUC: 20608330284</td>
                            </tr>
                            <tr class="receipt_series">
                                <td>EBO1-4276</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

        </tbody>
    </table>

    <table class="table_info">
        <tbody>
            <tr>
                <td class="row_one"> </td>
                <td class="row_two"> </td>
                <td class="row_three">  </td>
            </tr>
            <tr>
                <td class="row_one">Fecha de Emisión</td>
                <td class="row_two">:</td>
                <td class="row_three">2022-05-01</td>
            </tr>
            <tr>
                <td class="row_one">Señor(es)</td>
                <td class="row_two">:</td>
                <td class="row_three">VENTA DEL DIA (CLIENTE VARIOS)</td>
            </tr>
            <tr>
                <td class="row_one">Establecimiento del Eminsor</td>
                <td class="row_two">:</td>
                <td class="row_three">JR. BOLOGNESI 523 SAN MARTIN - SAN MARTIN - TARAPOTO</td>
            </tr>
            <tr>
                <td class="row_one">Tipo de moneda </td>
                <td class="row_two">:</td>
                <td class="row_three">SOLES</td>
            </tr>
    </table>


    <table class="table_products">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Unidad Medida</th>
                <th>Descripción</th>
                <th>Valor <br />
                    Unitario(*)</th>
                <th align="right">Descuento(*)</th>
                <th align="right">Importe de<br />
                    Venta(**)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Unidad</td>
                <td>Bermuda talla 36</td>
                <td align="right">35.00</td>
                <td align="right">0.00</td>
                <td align="right">35.00</td>
            </tr>
        </tbody>
    </table>

    <table class="table_totals">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td class="value bold">Op. Gravada:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td>(*) Sin impuestos.</td>
                <td class="value bold">Op. Exonerada:</td>
                <td class="value">S/ 136.00</td>
            </tr>
            <tr>
                <td>(**) Incluye impuestos, de ser Op. gravada</td>
                <td class="value bold">Op. Inafecta:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td class="value bold">ISC:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td class="value bold">IGV:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td><strong>SON: CIENTO TREINTA Y SEIS Y 00/100 SOLES</strong></td>
                <td class="value bold">Otros Cargos:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td class="value bold">Otros Tributos:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td class="value bold">Monto de Redondeo:</td>
                <td class="value">S/ 0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td class="value bold totals">Importe Total:</td>
                <td class="value totals">S/ 136.00</td>
            </tr>

        </tbody>
    </table>

    <div class="box_border">
        <div class="box_border_content">
            <p>Esta es una representación impresa de la factura electrónica, generada en el Sistema de
                <strong>SUNAT</strong>
                . puede verificarla utilizando su clave <strong>SOL.</strong>
            </p>
        </div>
    </div>

    <div class="box_border">
        <div class="box_border_content">
            <p>BIENES TRANSFERIDOS SERVICIOS PRESTADOS EN LA REGIÓN DE SELVA PARA SER CONSUMIDOS EN LA MISMA</p>
        </div>
    </div>

    <div class="code_qr_content">
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="box_qr">
                            <img width="80" src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}"
                                class="qrcode" />
                        </div>
                    </td>
                    <td>
                        <div class="box_info">
                            <p>Representación impresa del comprobante de venta electrónica, esta puede ser consultada en
                                <strong>www.cautivamodayestiloamericano.shop</strong>
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>



    </div>

</body>

</html>
