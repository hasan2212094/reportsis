<?php

namespace App\Exports; // Ganti dengan namespace yang sesuai

use App\Models\DirectP; // Pastikan model Anda benar
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DirectpExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = DirectP::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('Date_pengajuan', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    // 1. Judul kolom (sesuai gambar: KEBUTUH, TANGGAL, NOTE)
    public function headings(): array
    {
        return [
            ['LAPORAN DIRECT COST PENGAJUAN'], // Baris 1: Judul Utama
            [ 
                'ID_ITEM',
                'ITEM',
                'QTY',
                'SATUAN',
                'KEBUTUHAN',
                'WORKORDER', 
                'TANGGAL', // Diubah dari 'TANGGAL PENGAJUAN'
                'TOTAL',
                'NOTE', // Diubah dari 'Notes'
            ] // Baris 2: Heading Tabel
        ];
    }

    // 2. Mapping data ke kolom
    public function map($row): array
    {
        return [
            $row->item_id,
            $row->Item,
            $row->Qty,
            $row->Unit,
            $row->Needed_by,
            optional($row->workorder)->kode_wo ?? '-', // WORKORDER
            $row->Date_pengajuan ? \Carbon\Carbon::parse($row->Date_pengajuan)->format('d-m-Y') : '',
            (int)$row->Total,
            $row->Notes,
        ];
    }

    // 3. Styles (Judul, Heading, dan Border Tebal)
    public function styles(Worksheet $sheet)
    {
        // 1. Merge dan Center Judul Utama (Baris 1)
        // Asumsi tabel memiliki 8 kolom (A hingga H)
        $sheet->mergeCells('A1:I1'); 
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Definisi styles dasar yang akan di-return (Judul dan Heading)
        $returnStyles = [
            // Baris 1: Judul Utama
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ], 
            // Baris 2: Heading Tabel
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 
            ], 
        ];

        // 2. Penerapan Border Tebal pada Tabel
        $lastRow = $sheet->getHighestRow();
        // Range tabel: dari header (Baris 2) hingga data terakhir (Kolom H)
        $range = 'A2:I' . $lastRow; 

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK, // Border tebal
                    'color' => ['argb' => 'FF000000'], // Warna hitam
                ],
            ],
        ];

        // Terapkan style border secara langsung ke Worksheet
        $sheet->getStyle($range)->applyFromArray($borderStyle);
        
        // Kembalikan styles untuk baris spesifik (Judul dan Heading)
        return $returnStyles; 
    }
}
