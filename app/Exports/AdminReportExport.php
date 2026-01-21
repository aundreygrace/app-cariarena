<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AdminReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $data;
    protected $totalPendapatan;
    protected $totalTransaksi;
    protected $filters;

    public function __construct($data, $totalPendapatan, $totalTransaksi, $filters = [])
    {
        $this->data = $data;
        $this->totalPendapatan = $totalPendapatan;
        $this->totalTransaksi = $totalTransaksi;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Customer',
            'Venue',
            'Metode Pembayaran',
            'Jumlah (Rp)',
            'Tanggal Transaksi',
            'Durasi (jam)',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            '', // Nomor akan diisi di registerEvents
            $transaction->transaction_number ?? '-',
            $transaction->pengguna ?? $transaction->customer_name ?? '-',
            $transaction->nama_venue ?? '-',
            $transaction->metode_pembayaran ?? 'Transfer Bank',
            $transaction->amount ?? 0,
            $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') : '-',
            $transaction->durasi ?? 2,
            $this->getStatusText($transaction->status ?? 'pending')
        ];
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'selesai' => 'Selesai',
            'completed' => 'Selesai',
            'pending' => 'Pending',
            'cancelled' => 'Dibatalkan',
            'dibatalkan' => 'Dibatalkan',
            'refunded' => 'Dikembalikan'
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $dataCount = count($this->data);
                $lastRow = $dataCount + 7; // Header (3 baris) + data + total (2 baris) + footer

                // Set judul laporan
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', 'LAPORAN ADMIN - CARIARENA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Info filter
                $filterInfo = 'Periode: ' . $this->getPeriodeText($this->filters['periode'] ?? 'minggu-ini');
                if (!empty($this->filters['status'])) {
                    $statusText = $this->getStatusText($this->filters['status']);
                    $filterInfo .= ' | Status: ' . $statusText;
                }
                if (!empty($this->filters['jenis_laporan']) && $this->filters['jenis_laporan'] !== 'semua') {
                    $filterInfo .= ' | Jenis: ' . ucfirst($this->filters['jenis_laporan']);
                }
                
                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', $filterInfo);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Geser header tabel ke baris 4
                $headingsRange = 'A4:I4';
                $sheet->fromArray($this->headings(), null, 'A4');
                
                // Auto width setiap kolom
                foreach(range('A','I') as $col){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Style Header Tabel
                $sheet->getStyle($headingsRange)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4299E1']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000']
                        ]
                    ]
                ]);

                // Style Data
                $dataStartRow = 5;
                $dataEndRow = $dataCount + 4;
                
                if ($dataCount > 0) {
                    $dataRange = "A{$dataStartRow}:I{$dataEndRow}";
                    
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FFD1D5DB']
                            ]
                        ]
                    ]);

                    // Format angka Rupiah untuk kolom jumlah
                    $sheet->getStyle("F{$dataStartRow}:F{$dataEndRow}")
                          ->getNumberFormat()
                          ->setFormatCode('#,##0');

                    // Format tanggal untuk kolom tanggal transaksi
                    $sheet->getStyle("G{$dataStartRow}:G{$dataEndRow}")
                          ->getNumberFormat()
                          ->setFormatCode('dd/mm/yyyy');

                    // Align center untuk kolom tertentu
                    $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    $sheet->getStyle("H{$dataStartRow}:H{$dataEndRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    $sheet->getStyle("I{$dataStartRow}:I{$dataEndRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Nomor urut
                    for ($i = 0; $i < $dataCount; $i++) {
                        $sheet->setCellValue("A" . ($dataStartRow + $i), $i + 1);
                    }
                }

                /**
                 * Baris Total
                 */
                $totalRow = $dataEndRow + 2;

                // Total Transaksi
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL TRANSAKSI');
                $sheet->setCellValue("F{$totalRow}", $this->totalTransaksi);
                $sheet->getStyle("F{$totalRow}")
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');

                // Total Pendapatan
                $totalRow++;
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL PENDAPATAN');
                $sheet->setCellValue("F{$totalRow}", $this->totalPendapatan);
                $sheet->getStyle("F{$totalRow}")
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');

                // Style baris total
                $totalRange = "A" . ($totalRow - 1) . ":I{$totalRow}";
                $sheet->getStyle($totalRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F4F6']
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF000000']
                        ]
                    ]
                ]);

                // Footer
                $footerRow = $totalRow + 2;
                $sheet->mergeCells("A{$footerRow}:I{$footerRow}");
                $sheet->setCellValue("A{$footerRow}", 'Dibuat pada: ' . date('d/m/Y H:i:s') . ' | © ' . date('Y') . ' CariArena');
                $sheet->getStyle("A{$footerRow}")->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }
        ];
    }

    private function getPeriodeText($periode)
    {
        $periodeTexts = [
            'hari-ini' => 'Hari Ini',
            'minggu-ini' => 'Minggu Ini',
            'bulan-ini' => 'Bulan Ini',
            'tahun-ini' => 'Tahun Ini',
            'custom' => 'Kustom'
        ];
        
        return $periodeTexts[$periode] ?? 'Minggu Ini';
    }
}