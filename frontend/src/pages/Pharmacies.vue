<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Pharmacies</h3>
      <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary" @click="refresh">Refresh</button>
        <button class="btn btn-sm btn-success" @click="openBulk">Bulk Inventory (CSV)</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th style="width: 70px">ID</th>
            <th>Name</th>
            <th>Address</th>
            <th style="width: 120px">Lat</th>
            <th style="width: 120px">Lng</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td colspan="5" class="text-muted">Loading...</td>
          </tr>
          <tr v-else-if="!items.length">
            <td colspan="5" class="text-muted">No pharmacies found.</td>
          </tr>
          <tr v-else v-for="p in items" :key="p.id">
            <td>{{ p.id }}</td>
            <td>{{ p.name }}</td>
            <td>{{ p.address }}</td>
            <td>{{ p.latitude }}</td>
            <td>{{ p.longitude }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      <Pager v-model:page="page" :hasMore="hasMore" />
    </div>

    <!-- Bulk CSV Modal -->
    <div class="modal fade" id="bulkModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Bulk Inventory Import
              <small class="text-muted d-block fs-6"
                >Upload CSV with headers: <code>sku,price,quantity</code></small
              >
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" />
          </div>

          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Pharmacy ID</label>
                <input
                  v-model.number="bulkForm.pharmacyId"
                  class="form-control"
                  placeholder="e.g. 101"
                />
              </div>

              <div class="col-md-8">
                <label class="form-label d-flex align-items-center justify-content-between">
                  <span>CSV File (.csv)</span>
                  <button class="btn btn-link btn-sm p-0" type="button" @click="downloadTemplate">
                    Download template
                  </button>
                </label>
                <input type="file" accept=".csv,text/csv" class="form-control" @change="onFile" />
                <div class="form-text">Example row: <code>PAN-500-TAB-24,110.50,30</code></div>
              </div>

              <div class="col-12">
                <div
                  v-if="uploadMsg"
                  class="alert"
                  :class="uploadOk ? 'alert-success' : 'alert-danger'"
                >
                  {{ uploadMsg }}
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" :disabled="uploading" @click="submitBulk">
              <span
                v-if="uploading"
                class="spinner-border spinner-border-sm me-2"
                role="status"
                aria-hidden="true"
              ></span>
              Submit & Queue Import
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Bulk CSV Modal -->
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import Pager from "@/components/Pager.vue";
import { listPharmacies } from "@/api/pharmacies";
import { bulkInventoryCsv } from "@/api/pharmacies";

// Table data + pagination
const items = ref([]);
const page = ref(1);
const hasMore = ref(false);
const loading = ref(false);

// Bulk modal state
let modal;
const bulkForm = ref({
  pharmacyId: null,
});
const fileRef = ref(null);
const uploading = ref(false);
const uploadMsg = ref("");
const uploadOk = ref(false);

const fetchData = async () => {
  loading.value = true;
  try {
    const { data, meta } = await listPharmacies({ page: page.value });
    items.value = data || [];
    hasMore.value = !!meta?.next_page_url;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const refresh = () => fetchData();

onMounted(async () => {
  await fetchData();
  const modalEl = document.getElementById("bulkModal");
  if (modalEl) {
    const bs = await import("bootstrap");
    modal = new bs.Modal(modalEl);
  }
});

watch(page, fetchData);

const openBulk = () => {
  // reset state
  bulkForm.value.pharmacyId = null;
  fileRef.value = null;
  uploadMsg.value = "";
  uploadOk.value = false;
  modal?.show();
};

const onFile = (e) => {
  fileRef.value = e.target.files?.[0] || null;
};

const submitBulk = async () => {
  try {
    uploadMsg.value = "";
    uploadOk.value = false;

    if (!bulkForm.value.pharmacyId) {
      uploadMsg.value = "Please enter Pharmacy ID.";
      uploadOk.value = false;
      return;
    }
    if (!fileRef.value) {
      uploadMsg.value = "Please choose a CSV file.";
      uploadOk.value = false;
      return;
    }

    uploading.value = true;
    await bulkInventoryCsv(bulkForm.value.pharmacyId, fileRef.value);
    uploadMsg.value = "Bulk inventory CSV queued successfully!";
    uploadOk.value = true;
    // close after short delay
    setTimeout(() => modal?.hide(), 800);
  } catch (e) {
    console.error(e);
    uploadMsg.value = "Upload failed. Please check CSV format or try again.";
    uploadOk.value = false;
  } finally {
    uploading.value = false;
  }
};

const downloadTemplate = () => {
  const csv = "sku,price,quantity\nPAN-500-TAB-24,110.50,30\nPAN-250-SYRUP,75.00,12\n";
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = "inventory_template.csv";
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
}
</style>
