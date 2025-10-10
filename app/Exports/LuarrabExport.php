<?php

namespace App\Exports;

use App\Models\Luarrab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LuarrabExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = Luarrab::with('workorder');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('Date_actual', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN LUAR RAB ACTUAL'], // Baris 1: Judul
            [
                'ID ITEM',
                'ITEM',
                'WORKORDER',
                'KEBUTUHAN',
                'QTY ACTUAL',
                'SATUAN',
                'TANGGAL ACTUAL',
                'TOKO',
                'TRANSAKSI',
                'TOTAL',
            ],
        ];
    }

    public function map($row): array
    {
        return [
            $row->luarrabps_id ?? '-',
            $row->Item ?? '-',
            optional($row->workorder)->kode_wo ?? '-', // ITEM
            $row->Needed_by ?? '-',                    // KEBUTUHAN
            $row->Qty ?? '-',                          // QTY ACTUAL
            $row->Unit ?? '-',                         // SATUAN
            $row->Date_actual
                ? Carbon::parse($row->Date_actual)->format('d-m-Y')
                : '-',                                 // TANGGAL ACTUAL
            $row->Toko ?? '-',                         // TOKO
            $row->Transaksi == 0 ? 'CASH' : 'TRANSFER', // TRANSAKSI
            'Rp ' . number_format($row->Total, 0, ',', '.'), // TOTAL
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge judul
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
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
                $sheet = $event->sheet->getDelegate();

                // Auto size tiap kolom
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border semua data dari header sampai data terakhir
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A2:J{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
