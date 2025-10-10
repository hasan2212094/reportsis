<?php

namespace App\Exports;

use App\Models\Ppna;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class PpnaExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
  public function collection()
{
    return Ppna::with('ppn')
        ->get()
        ->map(function ($item) {
            return [
                $item->ppn->Item ?? '-',
                $item->ppn->Qty ?? '-',
                $item->ppn->Unit ?? '-',
                $item->ppn->Needed_by ?? '-',
                $item->ppn->workorder->kode_wo ?? '-',
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
               ['LAPORAN PPN ACTUAL'], // judul utama di baris 1
           [    'ITEM',
                'QTY PENGAJUAN',
                'SATUAN PENGAJUAN',
                'KEBUTUHAN',
                'WORKORDER',
                'QTY ACTUAL',
                'SATUAN ACTUAL',
                'TANGGAL ACTUAL',
                'TOKO',
                'TRANSAKSI',
                'TOTAL',
        ],
        ];
    }
     public function styles(Worksheet $sheet)
    {
         $highestColumn = $sheet->getHighestColumn(); // Dapatkan kolom terakhir (misal H)
        
        // 1. Merge dan Center Judul Utama (Baris 1) menggunakan highestColumn
        $sheet->mergeCells('A1:K1');  
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
