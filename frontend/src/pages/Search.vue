<template>
  <div>
    <h3 class="mb-3">Search Variants</h3>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <input
          v-model="filters.q"
          @keyup.enter="run"
          class="form-control"
          placeholder="Type to search..."
        />
      </div>
      <div class="col-md-3">
        <select v-model="filters.category_id" class="form-select">
          <option :value="null">All Categories</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
      </div>
      <div class="col-md-3">
        <select v-model="filters.manufacturer_id" class="form-select">
          <option :value="null">All Manufacturers</option>
          <option v-for="m in manufacturers" :key="m.id" :value="m.id">{{ m.name }}</option>
        </select>
      </div>
      <div class="col-md-2">
        <div class="form-check mt-2">
          <input
            id="avail"
            class="form-check-input"
            type="checkbox"
            v-model="filters.is_available"
          />
          <label for="avail" class="form-check-label">Available only</label>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <input v-model.number="filters.price_min" class="form-control" placeholder="Price min" />
      </div>
      <div class="col-md-3">
        <input v-model.number="filters.price_max" class="form-control" placeholder="Price max" />
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-primary" @click="run">Search</button>
      </div>
    </div>

    <div v-if="loading">Loading...</div>

    <div v-else class="row g-3">
      <div v-for="v in results" :key="v.id" class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">{{ v.name }}</h5>
            <p class="card-text">
              <small class="text-muted">SKU: {{ v.sku }}</small>
            </p>
            <div v-if="v.best_prices?.length">
              <div class="mt-2">
                <strong>Top Pharmacies:</strong>
                <ul class="mb-0">
                  <li v-for="p in v.best_prices.slice(0, 3)" :key="p.pharmacy_id">
                    {{ p.pharmacy_name }} - {{ p.price }} (qty: {{ p.quantity }})
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { searchVariants } from "@/api/search";
import { listCategories } from "@/api/categories";
import { listManufacturers } from "@/api/manufacturers";

const filters = ref({
  q: "",
  category_id: null,
  manufacturer_id: null,
  is_available: false,
  price_min: null,
  price_max: null,
});
const results = ref([]);
const categories = ref([]);
const manufacturers = ref([]);
const loading = ref(false);

const run = async () => {
  loading.value = true;
  try {
    const params = { ...filters.value };
    // Clean nulls for nicer requests
    Object.keys(params).forEach(
      (k) => (params[k] === null || params[k] === "") && delete params[k]
    );
    const res = await searchVariants(params);
    results.value = res?.data || res; // depending on your Resource shape
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  const [cats, mans] = await Promise.all([listCategories(), listManufacturers()]);
  categories.value = cats?.data || cats;
  manufacturers.value = mans?.data || mans;
});
</script>
