<!DOCTYPE html>
<html lang="en">

<head>

    <title>Cash Time</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            margin-left: 22px;
            margin-right: 22px;
        }

        .table_products {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .table_products thead {
            border-top: 1.2px solid #F6187F;
            border-bottom: 1.2px solid #F6187F;
        }

        .table_products thead th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        .table_products thead th:nth-child(1) {

            width: 45px;
        }

        .table_products thead th:nth-child(2) {
            width: 65px;
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

        /*table totals */

        .table_totals {
            border-top: 1.2px solid #F6187F;
            border-bottom: 1.2px solid #F6187F;
            width: 100%;
            font-size: 8px;
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

        .bold{
            font-weight: bold;
        }

        .table_totals .totals {
            font-weight: bold;
            font-size: 8px;
        }

        .table_products tbody {}

        .box_border {
            margin-top: 10px;
            width: 97%;
            border: 1px solid #F6187F;
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
        .code_qr_content .box_info{
             font-size: 10px;
             width: 250px;
             padding: 8px;
        }

        .code_qr_content .box_qr {
            padding: 8px;
            border: 1px solid #F6187F;
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

    <img src="data:image/svg+xml;base64,{{ base64_encode($receipt) }}" class="receipt" />

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
                <td> </td>
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
                . puede verificarla utilizando su clave <strong>SOL.</strong></p>
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
                            <img width="80" src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" class="qrcode" />
                        </div>
                    </td>
                    <td>
                        <div class="box_info">
                            <p>Representación impresa del comprobante de venta electrónica, esta puede ser consultada en
                                <strong>www.cautivamodayestiloamericano.shop</strong></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>



    </div>

</body>

</html>
