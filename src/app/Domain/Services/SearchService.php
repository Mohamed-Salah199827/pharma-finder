<?php

namespace App\Domain\Services;

use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchService
{
    public function searchVariants(array $params): LengthAwarePaginator
    {
        $q = trim((string) ($params['q'] ?? ''));

        // 1) Scout search → get ids by relevance
        $builder = ProductVariant::search($q);
        $ids = $builder->keys();

        // fallback لو مفيش q (هات كل حاجة)
        $base = ProductVariant::with('product.manufacturer', 'product.category');
        if ($q !== '') {
            $base->whereIn('id', $ids ?: [0]);
        }

        // 2) Facets
        if (!empty($params['category_id'])) {
            $cat = Category::find($params['category_id']);
            if ($cat) {
                $idsCat = $cat->descendantIds();
                $base->whereHas('product', fn($qq) => $qq->whereIn('category_id', $idsCat));
            }
        }

        if (!empty($params['manufacturer'])) {
            $m = $params['manufacturer'];
            $base->whereHas('product.manufacturer', fn($qq) => $qq->where('name', 'like', "%$m%"));
        }

        if (isset($params['available'])) {
            $avail = filter_var($params['available'], FILTER_VALIDATE_BOOLEAN);
            $base->whereHas('inventories', fn($qq) => $qq->where('is_available', $avail));
        }

        if (isset($params['min_price']) || isset($params['max_price'])) {
            $min = $params['min_price'] ?? 0;
            $max = $params['max_price'] ?? PHP_INT_MAX;
            $base->whereHas('inventories', fn($qq) => $qq->whereBetween('price', [$min, $max]));
        }

        // احفظ ترتيب السكوت (لو في q)
        if ($q !== '' && $ids) {
            $order = implode(',', $ids->toArray());
            $base->orderByRaw("FIELD(id, $order)");
        } else {
            $base->orderBy('id', 'desc');
        }

        $perPage = (int) ($params['per_page'] ?? 15);
        $page = max(1, (int) ($params['page'] ?? 1));
        return $base->paginate($perPage, ['*'], 'page', $page);
    }
}
