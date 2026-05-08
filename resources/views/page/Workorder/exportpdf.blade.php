<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Work Order</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            border: 2px solid #000;
            padding: 10px;
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .company {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .section-title {
            background: #e5e5e5;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .no-border td {
            border: none;
        }

        .label {
            width: 150px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="company">
            PT. SRIBARU INDAH SEJAHTERA
        </div>

        <div class="header">
            WORK ORDER
        </div>

        <div class="section-title">INFORMATION</div>

        <table>
            <tr>
                <td class="label">Customer Name</td>
                <td class="bold">{{ $wo->customer_name }}</td>
                <td class="label">WO No.</td>
                <td class="bold">{{ $wo->kode_wo }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>{{ $wo->address }}</td>
                <td>WO Date</td>
                <td>{{ \Carbon\Carbon::parse($wo->wo_date)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Contact Person</td>
                <td>{{ $wo->contact_person }}</td>
                <td>Quantity</td>
                <td>{{ $wo->quantity }}</td>
            </tr>
            <tr>
                <td>Customer PO No.</td>
                <td>{{ $wo->customer_po_no }}</td>
                <td>Customer PO Date</td>
                <td>{{ $wo->customer_po_date }}</td>
            </tr>
            {{-- <tr>
                <td>Delivery Point</td>
                <td colspan="3">{{ $wo->delivery_point }}</td>
            </tr> --}}
        </table>

        <div class="section-title">WORK DESCRIPTION</div>

        <table class="no-border">
            <tr>
                <td style="width:150px;">Nama Produk</td>
                <td>: {{ $wo->nama_produk }}</td>
            </tr>
            <tr>
                <td>Type Unit</td>
                <td>: {{ $wo->type_unit }}</td>
            </tr>
            <tr>
                <td>Pekerjaan Selesai</td>
                <td>: {{ $wo->pekerjaan_selesai }}</td>
            </tr>
            <tr>
                <td>Pekerjaan Termasuk</td>
                <td>: {{ $wo->pekerjaan_termasuk }}</td>
            </tr>
            <tr>
                <td>Pekerjaan Tidak Termasuk</td>
                <td>: {{ $wo->pekerjaan_tidak_termasuk }}</td>
            </tr>
            <tr>
                <td>Garansi</td>
                <td>: {{ $wo->garansi }}</td>
            </tr>
        </table>

    </div>

</body>

</html>
