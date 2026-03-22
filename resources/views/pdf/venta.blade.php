<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta #{{ $carro->id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 50%;
            width: 380px;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
        }

        /* ── HEADER ── */
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: white;
            padding: 28px 32px;
            margin-bottom: 0;
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px;
        }

        .header-text h1 {
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .header-text .subtitle {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-id {
            margin-left: auto;
            text-align: right;
        }

        .header-id .folio {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-id .numero {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        /* ── BANDA ACCENT ── */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #f59e0b, #ef4444, #2563eb);
            margin-bottom: 28px;
        }

        /* ── SECCIONES ── */
        .section {
            margin: 0 32px 24px 32px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* ── INFO DEL CARRO ── */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-grid td:first-child {
            font-weight: bold;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 35%;
            background: #f8fafc;
        }

        .info-grid td:last-child {
            color: #0f172a;
            font-size: 13px;
        }

        /* ── TABLA GASTOS ── */
        .gastos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .gastos-table thead tr {
            background: #1e3a5f;
            color: white;
        }

        .gastos-table thead th {
            padding: 9px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .gastos-table thead th:last-child {
            text-align: right;
        }

        .gastos-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .gastos-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .gastos-table tbody td {
            padding: 8px 12px;
            color: #334155;
        }

        .gastos-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #0f172a;
        }

        .gastos-table tfoot tr {
            background: #1e3a5f;
            color: white;
        }

        .gastos-table tfoot td {
            padding: 10px 12px;
            font-weight: bold;
            font-size: 12px;
        }

        .gastos-table tfoot td:last-child {
            text-align: right;
            font-size: 13px;
        }

        /* ── RESULTADO FINAL ── */
        .resultado-box {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            border-radius: 6px;
            padding: 16px 20px;
            margin: 0 32px 24px 32px;
        }

        .resultado-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .resultado-row:last-child {
            margin-bottom: 0;
        }

        .resultado-label {
            display: table-cell;
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
            vertical-align: middle;
        }

        .resultado-valor {
            display: table-cell;
            text-align: right;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            vertical-align: middle;
        }

        .resultado-divider {
            border: none;
            border-top: 1px solid #bbf7d0;
            margin: 10px 0;
        }

        .ganancia-label {
            font-size: 13px;
            color: #15803d;
            font-weight: bold;
        }

        .ganancia-valor {
            font-size: 20px;
            font-weight: bold;
            color: #16a34a;
        }

        /* ── FOOTER ── */
        .footer {
            margin: 0 32px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <!-- WATERMARK -->
    <img src="{{ public_path('images/BASS.png') }}" class="watermark">

    <!-- HEADER -->
    <div class="header">
        <div class="header-inner">
            <img src="{{ public_path('images/BASS.png') }}" class="logo">
            <div class="header-text">
                <h1>Carros Alberto</h1>
                <div class="subtitle">Reporte de Venta</div>
            </div>
            <div class="header-id">
                <div class="folio">Folio</div>
                <div class="numero">#{{ str_pad($carro->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

    <div class="accent-bar"></div>

    <!-- INFO DEL CARRO -->
    <div class="section">
        <div class="section-title">Información del Vehículo</div>
        <table class="info-grid">
            <tr>
                <td>Vehículo</td>
                <td>{{ $carro->marca }} {{ $carro->linea }} {{ $carro->modelo }}</td>
            </tr>
            <tr>
                <td>Fecha de venta</td>
                <td>
                    @if($carro->fecha_venta)
                        {{ \Carbon\Carbon::parse($carro->fecha_venta)->format('d \d\e F \d\e Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- DETALLE DE COSTOS -->
    <div class="section">
        <div class="section-title">Detalle de Costos</div>
        <table class="gastos-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Precio de compra</td>
                    <td>${{ number_format($carro->precio_compra, 2) }}</td>
                </tr>
                @foreach($carro->gastos as $gasto)
                    <tr>
                        <td>{{ $gasto->concepto }}</td>
                        <td>${{ number_format($gasto->monto, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Costo real</td>
                    <td>${{ number_format($carro->costo_real, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- RESULTADO FINAL -->
    <div class="resultado-box">
        <div class="section-title" style="margin-bottom: 12px;">Resultado Final</div>

        <div class="resultado-row">
            <span class="resultado-label">Precio de venta</span>
            <span class="resultado-valor">${{ number_format($carro->precio_venta, 2) }}</span>
        </div>

        <div class="resultado-row">
            <span class="resultado-label">Costo real</span>
            <span class="resultado-valor">- ${{ number_format($carro->costo_real, 2) }}</span>
        </div>

        <hr class="resultado-divider">

        <div class="resultado-row">
            <span class="resultado-label ganancia-label">Ganancia real</span>
            <span class="resultado-valor ganancia-valor">
                ${{ number_format($carro->ganancia_real, 2) }}
            </span>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
        &nbsp;·&nbsp; Carros Alberto &nbsp;·&nbsp; Uso interno
    </div>

</body>

</html>