import { api } from "./http";

export const listVariants = (params = {}) =>
  api.get("/product-variants", { params }).then((r) => r.data);
export const createVariant = (payload) =>
  api.post("/product-variants", payload).then((r) => r.data);
export const updateVariant = (id, payload) =>
  api.put(`/product-variants/${id}`, payload).then((r) => r.data);
export const deleteVariant = (id) => api.delete(`/product-variants/${id}`).then((r) => r.data);
