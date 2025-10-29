<?php

namespace App\Jobs;

use App\Domain\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $pharmacyId;
    public string $path;

    // اوبشنال: زوّد وقت وتكرار المحاولات لو حابب
    public $timeout = 600; // 10 minutes
    public int $tries = 2;

    public function __construct(int $pharmacyId, string $path)
    {
        $this->pharmacyId = $pharmacyId;
        $this->path = $path;
    }

    public function handle(InventoryService $svc): void
    {
        $full = storage_path('app/' . $this->path);

        if (!is_file($full)) {
            Log::warning('ImportInventoryCsvJob: file not found', ['path' => $full]);
            return;
        }

        $fh = fopen($full, 'r');
        if (!$fh) {
            Log::warning('ImportInventoryCsvJob: unable to open file', ['path' => $full]);
            return;
        }

        $headers = fgetcsv($fh);
        if (!$headers) {
            fclose($fh);
            return;
        }

        // Normalize header names (lowercase/trim)
        $headers = array_map(fn($h) => strtolower(trim($h)), $headers);
        $skuIdx = array_search('sku', $headers);
        $priceIdx = array_search('price', $headers);
        $qtyIdx = array_search('quantity', $headers);

        if ($skuIdx === false || $priceIdx === false || $qtyIdx === false) {
            fclose($fh);
            Log::error('ImportInventoryCsvJob: missing required headers (sku,price,quantity).', ['headers' => $headers]);
            return;
        }

        $batch = [];
        $batchSize = 1000;

        while (($row = fgetcsv($fh)) !== false) {
            if (!isset($row[$skuIdx], $row[$priceIdx], $row[$qtyIdx])) {
                continue;
            }

            $sku = trim((string) $row[$skuIdx]);
            if ($sku === '')
                continue;

            $priceRaw = str_replace(',', '.', (string) $row[$priceIdx]);
            $price = (float) $priceRaw;

            $qty = (int) $row[$qtyIdx];
            if ($price < 0 || $qty < 0) {
                continue;
            }

            $batch[] = [
                'sku' => $sku,
                'price' => $price,
                'quantity' => $qty,
            ];

            if (count($batch) >= $batchSize) {
                $svc->bulkImport($this->pharmacyId, $batch);
                $batch = [];
            }
        }

        if ($batch) {
            $svc->bulkImport($this->pharmacyId, $batch);
        }

        fclose($fh);
    }
}