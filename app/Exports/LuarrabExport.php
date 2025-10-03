<?php

namespace App\Exports;

use App\Models\Ppna;
use App\Models\Luarrab;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LuarrabExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ppna::with('ppn')->get()->map(function ($item) {
            return [
                $item->ppn->Item ?? '-',
                $item->ppn->Needed_by ?? '-',
                $item->Qty,
                $item->Unit,
                $item->Date_actual,
                $item->Toko,
                $item->Transaksi == 0 ? 'CASH' : 'TRANSFER',
                $item->Total,
            ];
        });
    }

    public function headings(): array
    {
        return [
             ['LAPORAN PPN ACTUAL'], // judul utama di baris 1
           [ 'ITEM',
            'KEBUTUHAN',
            'QTY ACTUAL',
            'SATUAN',
            'TANGGAL ACTUAL',
            'TOKO',
            'TRANSAKSI',
            'TOTAL',]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // judul
            2 => ['font' => ['bold' => true]], // header tebal
        ];
    }

   public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // Auto size kolom
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // ====== Judul ======
            $sheet->mergeCells('A1:H1'); // merge kolom A sampai H baris 1
            $sheet->setCellValue('A1', 'LAPORAN PPN ACTUAL');

            // Style judul
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // ====== Header tabel ======
            $sheet->getStyle('A2:H2')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // ====== Border tabel ======
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $sheet->getStyle("A2:{$highestColumn}{$highestRow}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
        },
    ];
}
}
