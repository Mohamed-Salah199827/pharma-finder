<?php

namespace App\Http\Controllers\API\V1;

use App\Domain\Services\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private SearchService $search)
    {
    }

    public function variants(Request $r)
    {
        $result = $this->search->searchVariants($r->all());
        return ProductVariantResource::collection($result)->additional([
            'meta' => [
                'total' => $result->total(),
                'current_page' => $result->currentPage(),
                'per_page' => $result->perPage(),
            ]
        ]);
    }
}
