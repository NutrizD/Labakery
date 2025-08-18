<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlySalesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    protected int $month;
    protected int $year;
    protected Collection $transactions;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year  = $year;

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $this->transactions = Transaction::with(['details', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'No. Invoice', 'Kasir', 'Jumlah Produk', 'Total Pembayaran'];
    }

    public function collection(): Collection
    {
        return $this->transactions->values();
    }

    public function map($transaction): array
    {
        static $row = 0;
        $row++;

        $productCount = $transaction->details->sum('quantity');

        return [
            $row,
            $transaction->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
            $transaction->invoice_number,
            optional($transaction->user)->name ?? '-',
            (int) $productCount,
            (int) $transaction->total_amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '"Rp" #,##0',
            'E' => '#,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = max(1, $sheet->getHighestRow()); // jaga minimal 1 (header)
                $highestCol = $sheet->getHighestColumn();

                // Header style
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F5F9'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Border seluruh data (A1 hingga baris terakhir berisi data)
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Freeze header bila ada data
                if ($highestRow >= 2) {
                    $sheet->freezePane('A2');
                }

                // Baris TOTAL tepat di bawah data
                $totalRow = $highestRow + 1;
                $sheet->setCellValue("D{$totalRow}", 'TOTAL');
                $sheet->getStyle("D{$totalRow}")->getFont()->setBold(true)->setSize(12);

                if ($highestRow >= 2) {
                    // Ada data -> pakai SUM dari baris 2 s/d baris data terakhir
                    $sheet->setCellValue("E{$totalRow}", "=SUM(E2:E{$highestRow})");
                    $sheet->setCellValue("F{$totalRow}", "=SUM(F2:F{$highestRow})");
                } else {
                    // Tidak ada data -> nol
                    $sheet->setCellValue("E{$totalRow}", 0);
                    $sheet->setCellValue("F{$totalRow}", 0);
                }

                // Format angka pada total
                $sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("F{$totalRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');

                // Tebal + border baris total
                $sheet->getStyle("A{$totalRow}:{$highestCol}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // ====== Table Excel (jangan gandakan dengan AutoFilter) ======
                $dataRange   = "A1:{$highestCol}{$highestRow}";
                $canUseTable = ($highestRow >= 2)
                    && class_exists(Table::class)
                    && class_exists(TableStyle::class);

                if ($canUseTable) {
                    $table = new Table($dataRange, 'SalesTable');

                    $style = new TableStyle();
                    $style->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
                    $style->setShowRowStripes(true);

                    $table->setStyle($style);
                    $sheet->addTable($table);
                } else {
                    // Hanya pakai AutoFilter kalau TIDAK membuat Table
                    $sheet->setAutoFilter($dataRange);
                }
            },
        ];
    }
}
