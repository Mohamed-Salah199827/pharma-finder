<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Products</h3>
      <button class="btn btn-sm btn-success" @click="openCreate">+ New</button>
    </div>

    <div class="row g-3">
      <div v-for="p in items" :key="p.id" class="col-md-4">
        <ProductCard :product="p">
          <template #footer>
            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-outline-primary btn-sm" @click="edit(p)">Edit</button>
              <button class="btn btn-outline-danger btn-sm" @click="remove(p)">Delete</button>
            </div>
          </template>
        </ProductCard>
      </div>
    </div>

    <div class="mt-3">
      <Pager v-model:page="page" :hasMore="hasMore" />
    </div>

    <!-- Simple Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ form.id ? "Edit" : "Create" }} Product</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input v-model="form.name" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" @click="save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { listProducts, createProduct, updateProduct, deleteProduct } from "@/api/products";
import ProductCard from "@/components/ProductCard.vue";
import Pager from "@/components/Pager.vue";

let modal;
const items = ref([]);
const page = ref(1);
const hasMore = ref(false);
const form = reactive({ id: null, name: "", description: "" });

const fetchData = async () => {
  const { data, meta } = await listProducts({ page: page.value });
  items.value = data;
  hasMore.value = meta?.next_page_url ? true : false;
};

onMounted(async () => {
  await fetchData();
  const modalEl = document.getElementById("productModal");
  if (modalEl) {
    const bs = await import("bootstrap");
    modal = new bs.Modal(modalEl);
  }
});

watch(page, fetchData);

const openCreate = () => {
  Object.assign(form, { id: null, name: "", description: "" });
  modal?.show();
};
const edit = (p) => {
  Object.assign(form, p);
  modal?.show();
};
const save = async () => {
  if (form.id) await updateProduct(form.id, { name: form.name, description: form.description });
  else await createProduct({ name: form.name, description: form.description });
  modal?.hide();
  await fetchData();
};
const remove = async (p) => {
  if (confirm("Delete this product?")) {
    await deleteProduct(p.id);
    await fetchData();
  }
};
</script>
