import { api } from "./http";

export const listManufacturers = (params = {}) =>
  api.get("/manufacturers", { params }).then((r) => r.data);
export const createManufacturer = (payload) =>
  api.post("/manufacturers", payload).then((r) => r.data);
export const updateManufacturer = (id, payload) =>
  api.put(`/manufacturers/${id}`, payload).then((r) => r.data);
export const deleteManufacturer = (id) => api.delete(`/manufacturers/${id}`).then((r) => r.data);
