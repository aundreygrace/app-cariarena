<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VenueReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $totalPendapatan;
    protected $totalTransaksi;

    public function __construct($data, $totalPendapatan, $totalTransaksi)
    {
        $this->data = $data;
        $this->totalPendapatan = $totalPendapatan;
        $this->totalTransaksi = $totalTransaksi;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Booking',
            'Customer',
            'Venue',
            'Metode Bayar',
            'Jumlah (Rp)',
            'Tanggal',
            'Durasi',
            'Status'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){

                $sheet = $event->sheet->getDelegate();

                $lastRow = count($this->data) + 1;

                // Auto width setiap kolom
                foreach(range('A','I') as $col){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Style Header
                $sheet->getStyle("A1:I1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE5E7EB']
                    ]
                ]);

                // Border semua tabel
                $sheet->getStyle("A1:I{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);

                // Wrap text untuk kolom panjang
                $sheet->getStyle("B2:D{$lastRow}")->getAlignment()->setWrapText(true);

                // Align center kolom tertentu
                $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I2:I{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format Rupiah
                $sheet->getStyle("F2:F{$lastRow}")
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');

                /**
                 * Baris Total
                 */
                $totalRow = $lastRow + 1;

                // Merge cell untuk label TOTAL
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL');

                // Total Pendapatan
                $sheet->setCellValue("F{$totalRow}", $this->totalPendapatan);
                $sheet->getStyle("F{$totalRow}")
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');

                // Total transaksi
                $sheet->mergeCells("G{$totalRow}:I{$totalRow}");
                $sheet->setCellValue("G{$totalRow}", "{$this->totalTransaksi} transaksi");

                // Style baris total
                $sheet->getStyle("A{$totalRow}:I{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F4F6']
                    ]
                ]);
            }
        ];
    }
}
