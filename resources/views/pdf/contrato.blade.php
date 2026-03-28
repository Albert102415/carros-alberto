<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contrato #{{ $carro->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #1e293b;
        }

        .watermark {
            position: fixed;
            top: 38%;
            left: 50%;
            width: 380px;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
        }

        .header {
            background-color: #1e3a5f;
            color: white;
            padding: 24px 32px;
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            padding: 5px;
        }

        .header-text h1 {
            font-size: 19px;
            font-weight: bold;
            color: #ffffff;
        }

        .header-text .subtitle {
            font-size: 10px;
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
            font-size: 9px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-id .numero {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
        }

        .accent-bar {
            height: 4px;
            background-color: #2563eb;
            margin-bottom: 24px;
        }

        .section {
            margin: 0 32px 20px 32px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-grid td:first-child {
            font-weight: bold;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 35%;
            background: #f8fafc;
        }

        .info-grid td:last-child {
            color: #0f172a;
            font-size: 12px;
        }

        .clausula {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .clausula-titulo {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            color: #1e3a5f;
            margin-bottom: 3px;
        }

        .clausula-texto {
            font-size: 10px;
            color: #334155;
            text-align: justify;
        }

        .firmas {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .firma-col {
            display: table-cell;
            width: 45%;
            text-align: center;
            padding: 0 10px;
        }

        .firma-linea {
            border-top: 1px solid #1e293b;
            margin-bottom: 6px;
            margin-top: 50px;
        }

        .firma-nombre {
            font-size: 10px;
            font-weight: bold;
            color: #1e293b;
        }

        .firma-rol {
            font-size: 9px;
            color: #64748b;
        }

        .footer {
            margin: 0 32px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <img src="{{ public_path('images/BASS.png') }}" class="watermark">

    <!-- HEADER -->
    <div class="header">
        <div class="header-inner">
            <img src="{{ public_path('images/BASS.png') }}" class="logo">
            <div class="header-text">
                <h1>Carros Alberto</h1>
                <div class="subtitle">Contrato de Compra-Venta</div>
            </div>
            <div class="header-id">
                <div class="folio">Contrato</div>
                <div class="numero">#{{ str_pad($carro->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

    <div class="accent-bar"></div>

    <!-- DATOS DEL VEHÍCULO -->
    <div class="section">
        <div class="section-title">Datos del Vehículo</div>
        <table class="info-grid">
            <tr>
                <td>Vehículo</td>
                <td>{{ $carro->marca }} {{ $carro->linea }} {{ $carro->modelo }}</td>
            </tr>
            <tr>
                <td>Color</td>
                <td>{{ $carro->color ?? '—' }}</td>
            </tr>
            <tr>
                <td>Precio de venta</td>
                <td><strong>${{ number_format($carro->precio_venta, 2) }} MXN</strong></td>
            </tr>
            <tr>
                <td>Fecha de venta</td>
                <td>
                    @if($carro->fecha_venta)
                        {{ \Carbon\Carbon::parse($carro->fecha_venta)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- DATOS DEL COMPRADOR -->
    <div class="section">
        <div class="section-title">Datos del Comprador</div>
        <table class="info-grid">
            <tr>
                <td>Nombre</td>
                <td>
                    @if($carro->expediente?->cliente)
                        {{ $carro->expediente->cliente }}
                    @else
                        <span style="color:#94a3b8">_______________________________</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Teléfono</td>
                <td>
                    @if($carro->expediente?->telefono)
                        {{ $carro->expediente->telefono }}
                    @else
                        <span style="color:#94a3b8">_______________________________</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Domicilio</td>
                <td>
                    @if($carro->expediente?->domicilio)
                        {{ $carro->expediente->domicilio }}
                    @else
                        <span style="color:#94a3b8">_______________________________</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Identificación</td>
                <td><span style="color:#94a3b8">_______________________________</span></td>
            </tr>
        </table>
    </div>

    <!-- CLÁUSULAS -->
    <div class="section">
        <div class="section-title">Clausulas del Contrato</div>

        <div class="clausula">
            <div class="clausula-titulo">Primera — Objeto</div>
            <div class="clausula-texto">
                El vendedor transfiere la propiedad del vehiculo descrito en este contrato al comprador,
                libre de gravamenes y adeudos, en el estado fisico y mecanico en que se encuentra al
                momento de la firma del presente documento.
            </div>
        </div>

        <div class="clausula">
            <div class="clausula-titulo">Segunda — Precio y forma de pago</div>
            <div class="clausula-texto">
                El precio pactado por ambas partes es de
                <strong>${{ number_format($carro->precio_venta, 2) }} MXN</strong>
                (pesos mexicanos), cantidad que
                @if($carro->expediente?->cliente)
                    <strong>{{ $carro->expediente->cliente }}</strong>
                @else
                    el comprador
                @endif
                declara haber entregado al vendedor a su entera satisfaccion.
            </div>
        </div>

        <div class="clausula">
            <div class="clausula-titulo">Tercera — Estado del vehiculo</div>
            <div class="clausula-texto">
                El comprador declara haber revisado el vehiculo y aceptarlo en las condiciones en que
                se encuentra, sin que el vendedor tenga responsabilidad por vicios ocultos o fallas
                mecanicas posteriores a la firma del presente contrato.
            </div>
        </div>

        <div class="clausula">
            <div class="clausula-titulo">Cuarta — Entrega</div>
            <div class="clausula-texto">
                El vendedor hace entrega al comprador del vehiculo junto con los documentos que acreditan
                su propiedad en la fecha indicada en este contrato, quedando el comprador responsable
                de realizar los tramites de cambio de propietario ante las autoridades correspondientes.
            </div>
        </div>

        <div class="clausula">
            <div class="clausula-titulo">Quinta — Conformidad</div>
            <div class="clausula-texto">
                Ambas partes declaran estar de acuerdo con los terminos y condiciones del presente
                contrato, firmando de conformidad en la ciudad de Torreon, Coahuila, a la fecha
                indicada en este documento.
            </div>
        </div>
    </div>

    <!-- FIRMAS -->
    <div class="section">
        <div class="section-title">Firmas</div>
        <div class="firmas">
            <div class="firma-col">
                <div class="firma-linea"></div>
                <div class="firma-nombre">Blas Alberto Salas Saucedo</div>
                <div class="firma-rol">Vendedor</div>
            </div>
            <div class="firma-col"></div>
            <div class="firma-col">
                <div class="firma-linea"></div>
                <div class="firma-nombre">
                    @if($carro->expediente?->cliente)
                        {{ $carro->expediente->cliente }}
                    @else
                        ________________________________
                    @endif
                </div>
                <div class="firma-rol">Comprador</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y') }}
        &nbsp;·&nbsp; Carros Alberto &nbsp;·&nbsp; Torreon, Coahuila
    </div>

</body>

</html>