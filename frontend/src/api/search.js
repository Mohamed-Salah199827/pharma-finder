import { api } from "./http";

export const searchVariants = (params = {}) =>
  api.get("/search/variants", { params }).then((r) => r.data);
