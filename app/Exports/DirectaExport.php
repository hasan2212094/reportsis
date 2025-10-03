<?php

namespace App\Exports;

use App\Models\Directa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class DirectaExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents
{
    public function collection()
    {
        return Directa::with('direct_ps')
            ->get()
            ->map(function ($item) {
                return [
                    $item->direct_ps->Item ?? '-',
                    $item->direct_ps->Qty ?? '-',
                    $item->direct_ps->Unit ?? '-',
                    $item->direct_ps->Needed_by ?? '-',
                    $item->Qty,
                    $item->Unit,
                    $item->Date_actual,
                    $item->Toko,
                    $item->Transaksi == 0 ? 'CASH' : 'TRANSFER',
                    $item->Total,
                ];
            });
    }

    // mulai export dari A2 (karena A1 dipakai untuk judul)
    public function startCell(): string
    {
        return 'A1';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DIRECT COST ACTUAL'], // judul utama di baris 1
            [
                'ITEM',
                'QTY PENGAJUAN',
                'SATUAN PENGAJUAN',
                'KEBUTUHAN',
                'QTY ACTUAL',
                'SATUAN ACTUAL',
                'TANGGAL ACTUAL',
                'TOKO',
                'TRANSAKSI',
                'TOTAL',
            ],
        ];
    }

    // Styling untuk judul dan heading
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // Judul
            2 => ['font' => ['bold' => true]], // Heading tabel
        ];
    }

    // Merge cell untuk judul
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:J1'); // merge judul dari A1 sampai J1
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
