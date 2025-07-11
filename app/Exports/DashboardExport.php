<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DashboardExport implements WithMultipleSheets
{
    public function __construct(
        protected int $harian,
        protected int $mingguan,
        protected Collection $topItems,
        protected Collection $stokKritis) {}

    public function sheets(): array
    {
        return [
            new RingkasanSheet ($this->harian,$this->mingguan),
            new TopItemSheet   ($this->topItems),
            new StokKritisSheet($this->stokKritis),
        ];
    }
}

class RingkasanSheet implements FromArray, WithTitle, ShouldAutoSize {
    public function __construct(private int $harian, private int $mingguan){}
    public function array(): array{ return [['Ringkasan'],['Harian',$this->harian],['7 Hari',$this->mingguan]]; }
    public function title(): string{ return 'Ringkasan'; }
}
class TopItemSheet implements FromArray, WithTitle, ShouldAutoSize {
    public function __construct(private Collection $rows){}
    public function array(): array{
        $arr=[['Nama','Qty','Omzet']];
        foreach($this->rows as $r) $arr[]=[$r->nama,$r->qty,$r->omzet];
        return $arr;
    }
    public function title(): string{ return 'Top 10'; }
}
class StokKritisSheet implements FromArray, WithTitle, ShouldAutoSize {
    public function __construct(private Collection $rows){}
    public function array(): array{
        $arr=[['Nama','Stok Pcs','Stok Paket']];
        foreach($this->rows as $b) $arr[]=[$b->nama,$b->stok_satuan,$b->stok_paket];
        return $arr;
    }
    public function title(): string{ return 'Stok Kritis'; }
}
