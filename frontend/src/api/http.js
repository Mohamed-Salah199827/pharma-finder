import axios from "axios";

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api/v1",
  timeout: 15000,
});

api.interceptors.response.use(
  (r) => r,
  (e) => {
    console.error("API error:", e?.response?.data || e.message);
    return Promise.reject(e);
  }
);
