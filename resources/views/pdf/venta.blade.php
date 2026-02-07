<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta #{{ $carro->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>Detalle de Venta</h1>

    <table>
        <tr>
            <th>Carro</th>
            <td>{{ $carro->marca }} {{ $carro->linea }} {{ $carro->modelo }}</td>
        </tr>
        <tr>
            <th>Año</th>
            <td>{{ $carro->anio }}</td>
        </tr>
        <tr>
            <th>Fecha de venta</th>
            <td>{{ optional($carro->fecha_venta)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <h3>Costos</h3>

    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th class="right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Precio de compra</td>
                <td class="right">${{ number_format($carro->precio_compra, 2) }}</td>
            </tr>

            @foreach($carro->gastos as $gasto)
                <tr>
                    <td>{{ $gasto->concepto }}</td>
                    <td class="right">${{ number_format($gasto->monto, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td>Costo real</td>
                <td class="right">${{ number_format($carro->costo_real, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Resultado</h3>

    <table>
        <tr>
            <th>Precio de venta</th>
            <td class="right">${{ number_format($carro->precio_venta, 2) }}</td>
        </tr>
        <tr class="total">
            <th>Ganancia real</th>
            <td class="right">
                ${{ number_format($carro->ganancia_real, 2) }}
            </td>
        </tr>
    </table>

</body>

</html>