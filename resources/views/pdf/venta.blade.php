<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #111;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .box {
            border: 1px solid #333;
            padding: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px;
        }

        .right {
            text-align: right;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #16a34a;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Comprobante de Venta</h2>
        <p>Agencia de Autos</p>
    </div>

    <div class="box">
        <strong>Datos del vehículo</strong>
        <table>
            <tr>
                <td>Marca:</td>
                <td>{{ $carro->marca }}</td>
            </tr>
            <tr>
                <td>Línea:</td>
                <td>{{ $carro->linea }}</td>
            </tr>
            <tr>
                <td>Modelo:</td>
                <td>{{ $carro->modelo }}</td>
            </tr>
            <tr>
                <td>Año:</td>
                <td>{{ $carro->anio }}</td>
            </tr>
            <tr>
                <td>Color:</td>
                <td>{{ $carro->color }}</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <strong>Información de la venta</strong>
        <table>
            <tr>
                <td>Fecha de venta:</td>
                <td>{{ \Carbon\Carbon::parse($carro->fecha_venta)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Precio compra:</td>
                <td class="right">${{ number_format($carro->precio_compra, 2) }}</td>
            </tr>
            <tr>
                <td>Precio venta:</td>
                <td class="right">${{ number_format($carro->precio_venta, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="right total">Ganancia:</td>
                <td class="right total">
                    ${{ number_format($ganancia, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <p style="text-align:center;margin-top:30px;">
        Documento generado automáticamente
    </p>

</body>

</html>