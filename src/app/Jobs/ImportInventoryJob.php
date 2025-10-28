<?php

namespace App\Jobs;

use App\Domain\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $pharmacyId;
    public array $items;

    public function __construct(int $pharmacyId, array $items)
    {
        $this->pharmacyId = $pharmacyId;
        $this->items = $items;
    }

    public function handle(InventoryService $svc): void
    {
        $svc->bulkImport($this->pharmacyId, $this->items);
    }
}
