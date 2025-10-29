import { api } from "./http";

export const listPharmacies = (params = {}) =>
  api.get("/pharmacies", { params }).then((res) => res.data);
export const createPharmacy = (payload) => api.post("/pharmacies", payload).then((res) => res.data);
export const updatePharmacy = (id, payload) =>
  api.put(`/pharmacies/${id}`, payload).then((res) => res.data);
export const deletePharmacy = (id) => api.delete(`/pharmacies/${id}`).then((res) => res.data);

export const bulkInventoryCsv = (pharmacyId, file) => {
  const formData = new FormData();
  formData.append("file", file);
  return api
    .post(`/pharmacies/${pharmacyId}/inventory/bulk`, formData, {
      headers: { "Content-Type": "multipart/form-data" },
    })
    .then((res) => res.data);
};
