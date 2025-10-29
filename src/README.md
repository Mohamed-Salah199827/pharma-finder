# Pharma Finder

Pharma Finder helps users find medicines at the best prices from nearby pharmacies.

## Features

-   Manage products and variants (Product Variants)
-   Manage pharmacies and their locations
-   Connect pharmacies and products through Inventory
-   Fast search using **Meilisearch**
-   Import inventory from CSV files

---

## Backend Setup (Laravel)

1. Make sure PHP and Composer are installed.
2. Copy the environment file:

```bash
cp .env.example .env
```

3. Generate the app key:

```bash
php artisan key:generate
```

4. Run migrations and seeders:

```bash
php artisan migrate --seed
```

5. For Meilisearch Cloud setup, edit your `.env`:

```dotenv
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=https://ms-fcbbf13629f2-33409.fra.meilisearch.io
MEILISEARCH_KEY=7f0596e25905f3fb073989c0295162905d8e0c76
```

Then import product data:

```bash
php artisan scout:import "App\Models\ProductVariant"
```

---

## Frontend Setup (Vue 3)

1. Go to the `frontend` folder
2. Install dependencies:

```bash
npm install
```

3. Run the app:

```bash
npm run dev
```

---

## CSV Inventory Upload

Example CSV format:

```
sku,price,quantity
PAN-500-TAB-24,110.50,30
PAN-250-SYRUP,75.00,12
```

API endpoint:

```
POST /api/pharmacies/{id}/inventory/bulk
```

Uploads and updates inventory based on SKU.

---

## Notes

-   If Redis is not installed, use:

```dotenv
QUEUE_CONNECTION=database
```

-   If Meilisearch local is not running, use the Cloud version.

---

## License

MIT License © 2025
