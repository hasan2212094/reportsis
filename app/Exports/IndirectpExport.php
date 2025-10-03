<?php

namespace App\Exports;

use App\Models\Indirectp;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
// Ganti namespace ini:
use PhpOffice\PhpSpreadsheet\Style\Alignment; // <--- INI PERBAIKANNYA
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndirectpExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{ 
    protected $start_date;
    protected $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection(): Collection
    {
        $query = Indirectp::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('Date_pengajuan', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            // Ganti Judul jika memang ini untuk INDIRECT Cost
            ['LAPORAN INDIRECT COST PENGAJUAN'], 
            [ 
                'ID_ITEM',
                'ITEM',
                'QTY',
                'SATUAN',
                'KEBUTUH', 
                'TANGGAL', 
                'TOTAL',
                'NOTE', 
            ] 
        ];
    }

    public function map($row): array
    {
        return [
            $row->item_id,
            $row->Item,
            $row->Qty,
            $row->Unit,
            $row->Needed_by,
            $row->Date_pengajuan ? \Carbon\Carbon::parse($row->Date_pengajuan)->format('d-m-Y') : '', 
            (int)$row->Total, 
            $row->Notes,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn(); // Dapatkan kolom terakhir (misal H)
        
        // 1. Merge dan Center Judul Utama (Baris 1) menggunakan highestColumn
        $sheet->mergeCells('A1:' . $highestColumn . '1'); 
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
}
