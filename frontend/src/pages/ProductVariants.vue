<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Product Variants</h3>
      <RouterLink to="/search" class="btn btn-outline-primary btn-sm">Go to Search</RouterLink>
    </div>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Product</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="v in items" :key="v.id">
            <td>{{ v.id }}</td>
            <td>{{ v.name }}</td>
            <td>
              <code>{{ v.sku }}</code>
            </td>
            <td>{{ v.product?.name }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pager v-model:page="page" :hasMore="hasMore" />
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { listVariants } from "@/api/productVariants";
import Pager from "@/components/Pager.vue";

const items = ref([]);
const page = ref(1);
const hasMore = ref(false);

const fetchData = async () => {
  const { data, meta } = await listVariants({ page: page.value, include: "product" });
  items.value = data;
  hasMore.value = meta?.next_page_url ? true : false;
};

onMounted(fetchData);
watch(page, fetchData);
</script>
