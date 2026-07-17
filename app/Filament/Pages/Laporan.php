<?php

namespace App\Filament\Pages;

use App\Models\Cat;
use App\Models\Pembelian;
use App\Models\Penjualan;
use BackedEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan';

    protected static string|UnitEnum|null $navigationGroup = 'Toko Cat';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.laporan';

    public string $periode = 'bulanan';

    public ?string $tanggal_mulai = '';

    public ?string $tanggal_akhir = '';

    public array $summary = [];

    public array $penjualanData = [];

    public array $pembelianData = [];

    public array $produkData = [];

    private const CURRENCY = '"Rp"#,##0';

    public function mount(): void
    {
        $this->tanggal_mulai = now()->startOfMonth()->toDateString();
        $this->tanggal_akhir = now()->toDateString();
        $this->loadData();
    }

    public function loadData(): void
    {
        $startDate = match ($this->periode) {
            'harian' => now()->startOfDay(),
            'mingguan' => now()->startOfWeek(),
            'bulanan' => now()->startOfMonth(),
            default => Carbon::parse($this->tanggal_mulai)->startOfDay(),
        };

        $endDate = match ($this->periode) {
            'custom' => Carbon::parse($this->tanggal_akhir)->endOfDay(),
            default => now()->endOfDay(),
        };

        $penjualans = Penjualan::whereBetween('tanggal_penjualan', [$startDate, $endDate])->with('cat')->get();
        $pembelians = Pembelian::whereBetween('tanggal_pembelian', [$startDate, $endDate])->with('cat')->get();

        $totalPenjualan = $penjualans->sum('total_harga');
        $totalPembelian = $pembelians->sum('total_harga');
        $totalLaba = $totalPenjualan - $totalPembelian;
        $jumlahTransaksi = $penjualans->count();
        $hari = max(1, $startDate->diffInDays($endDate) + 1);

        $this->summary = [
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'total_laba' => $totalLaba,
            'jumlah_transaksi' => $jumlahTransaksi,
            'rata_per_hari' => (int) round($totalPenjualan / $hari),
        ];

        $this->penjualanData = $penjualans->map(fn ($p) => [
            'tanggal' => $p->tanggal_penjualan->format('d M Y'),
            'pelanggan' => $p->pelanggan->nama ?? '-',
            'produk' => $p->cat->nama ?? '-',
            'jumlah' => $p->jumlah,
            'harga_satuan' => $p->harga_satuan,
            'total' => $p->total_harga,
        ])->toArray();

        $this->pembelianData = $pembelians->map(fn ($p) => [
            'tanggal' => $p->tanggal_pembelian->format('d M Y'),
            'supplier' => $p->supplier->nama ?? '-',
            'produk' => $p->cat->nama ?? '-',
            'jumlah' => $p->jumlah,
            'harga_satuan' => $p->harga_satuan,
            'total' => $p->total_harga,
        ])->toArray();

        $this->produkData = Cat::select('id', 'nama', 'warna', 'harga', 'harga_beli', 'stok', 'satuan')
            ->where('stok', '>', 0)
            ->get()
            ->map(fn ($c) => [
                'nama' => $c->nama,
                'warna' => $c->warna ?? '-',
                'stok' => $c->stok,
                'satuan' => $c->satuan,
                'harga_jual' => $c->harga,
                'harga_beli' => $c->harga_beli,
                'margin' => $c->harga - $c->harga_beli,
                'nilai_stok' => $c->stok * $c->harga_beli,
            ])
            ->toArray();

        Notification::make()->title('Data laporan diperbarui')->success()->send();
    }

    public function exportExcel(): StreamedResponse
    {
        $this->loadData();

        $filename = 'laporan-toko-cat-'.now()->format('Y-m-d').'.xlsx';

        $callback = function () {
            $spreadsheet = new Spreadsheet;
            $spreadsheet->getProperties()
                ->setTitle('Laporan Toko Cat')
                ->setDescription('Laporan toko cat periode '.$this->periode)
                ->setCreator('Toko Cat POS')
                ->setLastModifiedBy('Toko Cat POS');

            $this->buildRingkasanSheet($spreadsheet);
            $this->buildPenjualanSheet($spreadsheet);
            $this->buildPembelianSheet($spreadsheet);
            $this->buildInventorySheet($spreadsheet);

            if ($spreadsheet->getSheetCount() > 4) {
                $spreadsheet->removeSheetByIndex(0);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function writeTitleBar(Worksheet $ws, string $title, string $darkColor, int $lastCol): void
    {
        $endCol = Coordinate::stringFromColumnIndex($lastCol);
        $ws->mergeCells("A1:{$endCol}1");
        $ws->setCellValue('A1', $title);
        $ws->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
        $ws->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(1)->setRowHeight(38);
    }

    private function writeSummaryBar(Worksheet $ws, string $text, string $primaryColor, int $lastCol): void
    {
        $endCol = Coordinate::stringFromColumnIndex($lastCol);
        $ws->mergeCells("A2:{$endCol}2");
        $ws->setCellValue('A2', $text);
        $ws->getStyle('A2')->getFont()->setSize(9)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($primaryColor);
        $ws->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(2)->setRowHeight(24);
    }

    private function writeHeaders(Worksheet $ws, array $headers, array $widths, string $darkColor): void
    {
        $cols = array_map(fn ($i) => Coordinate::stringFromColumnIndex($i + 1), range(0, count($headers) - 1));
        foreach ($headers as $i => $header) {
            $c = $cols[$i];
            $ws->setCellValue("{$c}3", $header);
            $ws->getStyle("{$c}3")->getFont()->setBold(true)->setSize(10)->setColor(new Color('FFFFFF'));
            $ws->getStyle("{$c}3")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("{$c}3")->getAlignment()->setHorizontal('center')->setVertical('center');
            $ws->getColumnDimension($c)->setWidth($widths[$i]);
        }
        $ws->getRowDimension(3)->setRowHeight(30);
        $ws->freezePane('A4');
    }

    private function buildRingkasanSheet(Spreadsheet $spreadsheet): void
    {
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('Ringkasan');
        $ws->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $ws->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $ws->getTabColor()->setRGB('D97706');

        // Title bar
        $ws->mergeCells('A1:F1');
        $ws->setCellValue('A1', 'TOKO CAT');
        $ws->getStyle('A1')->getFont()->setBold(true)->setSize(20)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D97706');
        $ws->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(1)->setRowHeight(45);

        // Subtitle bar
        $ws->mergeCells('A2:F2');
        $ws->setCellValue('A2', 'LAPORAN KEUANGAN & INVENTORY');
        $ws->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F59E0B');
        $ws->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(2)->setRowHeight(28);

        // Period info
        $ws->mergeCells('A3:F3');
        $ws->setCellValue('A3', 'Periode: '.ucfirst($this->periode).' | Diperbarui: '.now()->format('d M Y H:i'));
        $ws->getStyle('A3')->getFont()->setSize(9)->setColor(new Color('9CA3AF'))->setItalic(true);
        $ws->getStyle('A3')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(3)->setRowHeight(22);

        $ws->getRowDimension(4)->setRowHeight(8);

        // Section: METRIK UTAMA
        $ws->mergeCells('A5:F5');
        $ws->setCellValue('A5', 'METRIK UTAMA');
        $ws->getStyle('A5')->getFont()->setBold(true)->setSize(12)->setColor(new Color('D97706'));
        $ws->getRowDimension(5)->setRowHeight(28);

        // Card 1: Total Penjualan
        $ws->mergeCells('A7:C7');
        $ws->setCellValue('A7', 'TOTAL PENJUALAN');
        $ws->getStyle('A7')->getFont()->setBold(true)->setSize(8)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F59E0B');
        $ws->getStyle('A7')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(7)->setRowHeight(26);

        $ws->mergeCells('A8:C8');
        $ws->setCellValue('A8', $this->summary['total_penjualan'] ?? 0);
        $ws->getStyle('A8')->getFont()->setBold(true)->setSize(14)->setColor(new Color('D97706'));
        $ws->getStyle('A8')->getNumberFormat()->setFormatCode(self::CURRENCY);
        $ws->getStyle('A8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');
        $ws->getStyle('A8')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(8)->setRowHeight(38);

        // Card 2: Total Pembelian
        $ws->mergeCells('D7:F7');
        $ws->setCellValue('D7', 'TOTAL PEMBELIAN');
        $ws->getStyle('D7')->getFont()->setBold(true)->setSize(8)->setColor(new Color('FFFFFF'));
        $ws->getStyle('D7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('3B82F6');
        $ws->getStyle('D7')->getAlignment()->setHorizontal('center')->setVertical('center');

        $ws->mergeCells('D8:F8');
        $ws->setCellValue('D8', $this->summary['total_pembelian'] ?? 0);
        $ws->getStyle('D8')->getFont()->setBold(true)->setSize(14)->setColor(new Color('1D4ED8'));
        $ws->getStyle('D8')->getNumberFormat()->setFormatCode(self::CURRENCY);
        $ws->getStyle('D8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DBEAFE');
        $ws->getStyle('D8')->getAlignment()->setHorizontal('center')->setVertical('center');

        $cardBorder = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];
        $ws->getStyle('A7:C8')->applyFromArray($cardBorder);
        $ws->getStyle('D7:F8')->applyFromArray($cardBorder);

        // Card 3: Laba Bersih
        $laba = $this->summary['total_laba'] ?? 0;
        $labaColor = $laba >= 0 ? '10B981' : 'EF4444';
        $labaLight = $laba >= 0 ? 'D1FAE5' : 'FEE2E2';
        $labaLabel = $laba >= 0 ? 'LABA BERSIH (UNTUNG)' : 'LABA BERSIH (RUGI)';

        $ws->mergeCells('A10:C10');
        $ws->setCellValue('A10', $labaLabel);
        $ws->getStyle('A10')->getFont()->setBold(true)->setSize(8)->setColor(new Color('FFFFFF'));
        $ws->getStyle('A10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($labaColor);
        $ws->getStyle('A10')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(10)->setRowHeight(26);

        $ws->mergeCells('A11:C11');
        $ws->setCellValue('A11', $laba);
        $ws->getStyle('A11')->getFont()->setBold(true)->setSize(14)->setColor(new Color($labaColor));
        $ws->getStyle('A11')->getNumberFormat()->setFormatCode(self::CURRENCY);
        $ws->getStyle('A11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($labaLight);
        $ws->getStyle('A11')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getRowDimension(11)->setRowHeight(38);

        // Card 4: Jumlah Transaksi
        $ws->mergeCells('D10:F10');
        $ws->setCellValue('D10', 'JUMLAH TRANSAKSI');
        $ws->getStyle('D10')->getFont()->setBold(true)->setSize(8)->setColor(new Color('FFFFFF'));
        $ws->getStyle('D10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('8B5CF6');
        $ws->getStyle('D10')->getAlignment()->setHorizontal('center')->setVertical('center');

        $ws->mergeCells('D11:F11');
        $ws->setCellValue('D11', $this->summary['jumlah_transaksi'] ?? 0);
        $ws->getStyle('D11')->getFont()->setBold(true)->setSize(14)->setColor(new Color('7C3AED'));
        $ws->getStyle('D11')->getNumberFormat()->setFormatCode('#,##0');
        $ws->getStyle('D11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EDE9FE');
        $ws->getStyle('D11')->getAlignment()->setHorizontal('center')->setVertical('center');

        $ws->getStyle('A10:C11')->applyFromArray($cardBorder);
        $ws->getStyle('D10:F11')->applyFromArray($cardBorder);

        // Separator
        $ws->getRowDimension(12)->setRowHeight(8);

        // Section: RATA-RATA HARIAN
        $ws->mergeCells('A13:F13');
        $ws->setCellValue('A13', 'RATA-RATA PENJUALAN HARIAN');
        $ws->getStyle('A13')->getFont()->setBold(true)->setSize(12)->setColor(new Color('D97706'));
        $ws->getRowDimension(13)->setRowHeight(28);

        $ws->mergeCells('A14:F14');
        $ws->setCellValue('A14', $this->summary['rata_per_hari'] ?? 0);
        $ws->getStyle('A14')->getFont()->setBold(true)->setSize(16)->setColor(new Color('D97706'));
        $ws->getStyle('A14')->getNumberFormat()->setFormatCode(self::CURRENCY);
        $ws->getStyle('A14')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFBEB');
        $ws->getStyle('A14')->getAlignment()->setHorizontal('center')->setVertical('center');
        $ws->getStyle('A14:F14')->applyFromArray($cardBorder);
        $ws->getRowDimension(14)->setRowHeight(42);

        // Footer
        $ws->mergeCells('A16:F16');
        $ws->setCellValue('A16', 'Toko Cat POS - Sistem Point of Sale');
        $ws->getStyle('A16')->getFont()->setSize(8)->setColor(new Color('D1D5DB'))->setItalic(true);
        $ws->getStyle('A16')->getAlignment()->setHorizontal('center');

        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $col) {
            $ws->getColumnDimension($col)->setWidth(14);
        }
    }

    private function buildPenjualanSheet(Spreadsheet $spreadsheet): void
    {
        $ws = $spreadsheet->createSheet();
        $ws->setTitle('Penjualan');
        $ws->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $ws->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $ws->getTabColor()->setRGB('F59E0B');

        $darkColor = 'D97706';
        $primaryColor = 'F59E0B';
        $bgLight = 'FFFBEB';
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        $this->writeTitleBar($ws, 'DATA PENJUALAN', $darkColor, 7);
        $this->writeSummaryBar($ws,
            'Periode: '.ucfirst($this->periode).'  |  Total: Rp '.number_format($this->summary['total_penjualan'] ?? 0).'  |  '.count($this->penjualanData).' transaksi',
            $primaryColor, 7
        );
        $this->writeHeaders($ws,
            ['#', 'Tanggal', 'Pelanggan', 'Produk', 'Qty', 'Harga Satuan', 'Total'],
            [5, 15, 22, 28, 8, 18, 20],
            $darkColor
        );

        $rowNum = 4;
        foreach ($this->penjualanData as $index => $row) {
            $bgColor = $index % 2 === 0 ? $bgLight : 'FFFFFF';

            $ws->setCellValue("A{$rowNum}", $index + 1);
            $ws->setCellValue("B{$rowNum}", $row['tanggal']);
            $ws->setCellValue("C{$rowNum}", $row['pelanggan']);
            $ws->setCellValue("D{$rowNum}", $row['produk']);
            $ws->setCellValue("E{$rowNum}", $row['jumlah']);
            $ws->setCellValue("F{$rowNum}", $row['harga_satuan']);
            $ws->setCellValue("G{$rowNum}", $row['total']);

            $ws->getStyle("E{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $ws->getStyle("F{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);

            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("E{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("F{$rowNum}")->getAlignment()->setHorizontal('right');
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right');

            if ($row['total'] >= 1000000) {
                $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setColor(new Color($darkColor));
            }

            foreach ($cols as $c) {
                $ws->getStyle("{$c}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
                $ws->getStyle("{$c}{$rowNum}")->getBorders()->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('E5E7EB'));
            }

            $ws->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        // Total row
        if ($rowNum > 4) {
            $ws->mergeCells("A{$rowNum}:F{$rowNum}");
            $ws->setCellValue("A{$rowNum}", 'TOTAL PENJUALAN');
            $ws->getStyle("A{$rowNum}")->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFF'));
            $ws->getStyle("A{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->setCellValue("G{$rowNum}", collect($this->penjualanData)->sum('total'));
            $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setSize(12)->setColor(new Color('FFFFFF'));
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->getRowDimension($rowNum)->setRowHeight(32);
        }
    }

    private function buildPembelianSheet(Spreadsheet $spreadsheet): void
    {
        $ws = $spreadsheet->createSheet();
        $ws->setTitle('Pembelian');
        $ws->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $ws->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $ws->getTabColor()->setRGB('3B82F6');

        $darkColor = '1D4ED8';
        $primaryColor = '3B82F6';
        $bgLight = 'EFF6FF';
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        $this->writeTitleBar($ws, 'DATA PEMBELIAN', $darkColor, 7);
        $this->writeSummaryBar($ws,
            'Periode: '.ucfirst($this->periode).'  |  Total: Rp '.number_format($this->summary['total_pembelian'] ?? 0).'  |  '.count($this->pembelianData).' transaksi',
            $primaryColor, 7
        );
        $this->writeHeaders($ws,
            ['#', 'Tanggal', 'Supplier', 'Produk', 'Qty', 'Harga Satuan', 'Total'],
            [5, 15, 22, 28, 8, 18, 20],
            $darkColor
        );

        $rowNum = 4;
        foreach ($this->pembelianData as $index => $row) {
            $bgColor = $index % 2 === 0 ? $bgLight : 'FFFFFF';

            $ws->setCellValue("A{$rowNum}", $index + 1);
            $ws->setCellValue("B{$rowNum}", $row['tanggal']);
            $ws->setCellValue("C{$rowNum}", $row['supplier']);
            $ws->setCellValue("D{$rowNum}", $row['produk']);
            $ws->setCellValue("E{$rowNum}", $row['jumlah']);
            $ws->setCellValue("F{$rowNum}", $row['harga_satuan']);
            $ws->setCellValue("G{$rowNum}", $row['total']);

            $ws->getStyle("E{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $ws->getStyle("F{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);

            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("E{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("F{$rowNum}")->getAlignment()->setHorizontal('right');
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right');

            if ($row['total'] >= 1000000) {
                $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setColor(new Color($darkColor));
            }

            foreach ($cols as $c) {
                $ws->getStyle("{$c}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
                $ws->getStyle("{$c}{$rowNum}")->getBorders()->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('E5E7EB'));
            }

            $ws->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        if ($rowNum > 4) {
            $ws->mergeCells("A{$rowNum}:F{$rowNum}");
            $ws->setCellValue("A{$rowNum}", 'TOTAL PEMBELIAN');
            $ws->getStyle("A{$rowNum}")->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFF'));
            $ws->getStyle("A{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->setCellValue("G{$rowNum}", collect($this->pembelianData)->sum('total'));
            $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setSize(12)->setColor(new Color('FFFFFF'));
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->getRowDimension($rowNum)->setRowHeight(32);
        }
    }

    private function buildInventorySheet(Spreadsheet $spreadsheet): void
    {
        $ws = $spreadsheet->createSheet();
        $ws->setTitle('Inventory');
        $ws->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $ws->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $ws->getTabColor()->setRGB('0891B2');

        $darkColor = '0E7490';
        $primaryColor = '0891B2';
        $bgLight = 'ECFEFF';
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $totalNilaiStok = collect($this->produkData)->sum('nilai_stok');
        $totalMargin = collect($this->produkData)->sum('margin');

        $this->writeTitleBar($ws, 'DATA INVENTORY STOK', $darkColor, 8);
        $this->writeSummaryBar($ws,
            'Total Nilai Stok: Rp '.number_format($totalNilaiStok).'  |  '.count($this->produkData).' produk aktif  |  Total Margin: Rp '.number_format($totalMargin),
            $primaryColor, 8
        );
        $this->writeHeaders($ws,
            ['Produk', 'Warna', 'Stok', 'Satuan', 'Harga Jual', 'Harga Beli', 'Margin/Unit', 'Nilai Stok'],
            [28, 15, 10, 10, 16, 16, 16, 18],
            $darkColor
        );

        $rowNum = 4;
        foreach ($this->produkData as $index => $row) {
            $bgColor = $index % 2 === 0 ? $bgLight : 'FFFFFF';
            $margin = $row['margin'];

            $ws->setCellValue("A{$rowNum}", $row['nama']);
            $ws->setCellValue("B{$rowNum}", $row['warna']);
            $ws->setCellValue("C{$rowNum}", $row['stok']);
            $ws->setCellValue("D{$rowNum}", $row['satuan']);
            $ws->setCellValue("E{$rowNum}", $row['harga_jual']);
            $ws->setCellValue("F{$rowNum}", $row['harga_beli']);
            $ws->setCellValue("G{$rowNum}", $margin);
            $ws->setCellValue("H{$rowNum}", $row['nilai_stok']);

            $ws->getStyle("E{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("F{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("H{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);

            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('left');
            $ws->getStyle("C{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("D{$rowNum}")->getAlignment()->setHorizontal('center');
            $ws->getStyle("E{$rowNum}")->getAlignment()->setHorizontal('right');
            $ws->getStyle("F{$rowNum}")->getAlignment()->setHorizontal('right');
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right');
            $ws->getStyle("H{$rowNum}")->getAlignment()->setHorizontal('right');

            // Stok color coding
            if ($row['stok'] <= 5) {
                $ws->getStyle("C{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
                $ws->getStyle("C{$rowNum}")->getFont()->setBold(true)->setColor(new Color('DC2626'));
            } elseif ($row['stok'] <= 15) {
                $ws->getStyle("C{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');
                $ws->getStyle("C{$rowNum}")->getFont()->setBold(true)->setColor(new Color('D97706'));
            } else {
                $ws->getStyle("C{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D1FAE5');
                $ws->getStyle("C{$rowNum}")->getFont()->setBold(true)->setColor(new Color('059669'));
            }

            // Margin color
            $marginColor = $margin >= 0 ? '059669' : 'DC2626';
            $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setColor(new Color($marginColor));

            if ($row['nilai_stok'] >= 5000000) {
                $ws->getStyle("H{$rowNum}")->getFont()->setBold(true)->setColor(new Color($darkColor));
            }

            foreach ($cols as $c) {
                $ws->getStyle("{$c}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
                $ws->getStyle("{$c}{$rowNum}")->getBorders()->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('E5E7EB'));
            }

            $ws->getStyle("C{$rowNum}")->getBorders()->getOutline()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color('D1D5DB'));

            $ws->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        if ($rowNum > 4) {
            $ws->mergeCells("A{$rowNum}:F{$rowNum}");
            $ws->setCellValue("A{$rowNum}", 'TOTAL');
            $ws->getStyle("A{$rowNum}")->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFF'));
            $ws->getStyle("A{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->setCellValue("G{$rowNum}", $totalMargin);
            $ws->getStyle("G{$rowNum}")->getFont()->setBold(true)->setSize(10)->setColor(new Color('FFFFFF'));
            $ws->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("G{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("G{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->setCellValue("H{$rowNum}", $totalNilaiStok);
            $ws->getStyle("H{$rowNum}")->getFont()->setBold(true)->setSize(12)->setColor(new Color('FFFFFF'));
            $ws->getStyle("H{$rowNum}")->getNumberFormat()->setFormatCode(self::CURRENCY);
            $ws->getStyle("H{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($darkColor);
            $ws->getStyle("H{$rowNum}")->getAlignment()->setHorizontal('right')->setVertical('center');

            $ws->getRowDimension($rowNum)->setRowHeight(32);
        }
    }
}
