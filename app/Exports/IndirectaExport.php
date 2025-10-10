<?php

namespace App\Exports;

use App\Models\Indirecta;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class IndirectaExport implements FromCollection,  WithHeadings, WithCustomStartCell, WithStyles, WithEvents
{
    public function collection()
    {
       return Indirecta::with('indirectp')
        ->get()
        ->map(function ($item) {
            return [
                $item->indirectp->Item ?? '-',
                $item->indirectp->Qty ?? '-',
                $item->indirectp->Unit ?? '-',
                $item->indirectp->Needed_by ?? '-',
                $item->Qty,
                $item->Unit,
                $item->Date_actual,
                $item->Toko,
                $item->Transaksi == 0 ? 'CASH' : 'TRANSFER',
                $item->Total,
            ];
        });
    }
 public function startCell(): string
    {
        return 'A1';
    }

    public function headings(): array
    {
        return [
               ['LAPORAN INDIRECT COST ACTUAL'], // judul utama di baris 1
           [ 'Item',
            'Qty',
            'Unit',
            'Kebutuhan',
            'Qty',
            'Unit',
            'Date_actual',
            'Toko',
            'Transaksi',
            'Total',
        ],
        ];
    }
     public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn(); // Dapatkan kolom terakhir (misal H)
        
        // 1. Merge dan Center Judul Utama (Baris 1) menggunakan highestColumn
        $sheet->mergeCells('A1:J1');  
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $lastRow = $sheet->getHighestRow();
        $range = 'A2:' . $highestColumn . $lastRow; // Range menggunakan highestColumn

        // 2. Penerapan Border Tebal pada Tabel (Range: A2 hingga data terakhir)
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        
        // 3. Kembalikan styles untuk baris spesifik (Judul dan Heading)
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ], 
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 
            ], 
        ];
    }

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
