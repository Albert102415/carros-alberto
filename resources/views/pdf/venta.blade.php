<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta #{{ $carro->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2c3e50;
            position: relative;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 35%;
            left: 50%;
            width: 350px;
            transform: translate(-50%, -50%);
            opacity: 0.07;
            /* Transparencia elegante */
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
            margin-bottom: 5px;
        }

        h1 {
            margin: 0;
            font-size: 20px;
            color: #111827;
        }

        .subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        hr {
            border: none;
            border-top: 2px solid #e5e7eb;
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #f9fafb;
        }

        .ganancia {
            font-size: 14px;
            font-weight: bold;
            color: #16a34a;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 25px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

    <!-- WATERMARK -->
    <img src="{{ public_path('images/BASS.png') }}" class="watermark">

    <div class="header">
        <img src="{{ public_path('images/BASS.png') }}" class="logo">
        <h1>Carros Alberto</h1>
        <div class="subtitle">Reporte de Venta</div>
    </div>

    <hr>

    <table>
        <tr>
            <th>Carro</th>
            <td>{{ $carro->marca }} {{ $carro->linea }} {{ $carro->modelo }}</td>
        </tr>
        <tr>
            <th>Año</th>
            <td>{{ $carro->Año }}</td>
        </tr>
        <tr>
            <th>Fecha de venta</th>
            <td>{{ optional($carro->fecha_venta)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <h3>Detalle de Costos</h3>

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

    <h3>Resultado Final</h3>

    <table>
        <tr>
            <th>Precio de venta</th>
            <td class="right">${{ number_format($carro->precio_venta, 2) }}</td>
        </tr>
        <tr class="total">
            <th>Ganancia real</th>
            <td class="right ganancia">
                ${{ number_format($carro->ganancia_real, 2) }}
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>