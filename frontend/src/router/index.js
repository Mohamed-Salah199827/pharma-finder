import { createRouter, createWebHistory } from "vue-router";
import Home from "@/pages/Home.vue";
import Products from "@/pages/Products.vue";
import ProductVariants from "@/pages/ProductVariants.vue";
import Pharmacies from "@/pages/Pharmacies.vue";
import Search from "@/pages/Search.vue";
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: "/", name: "home", component: Home },
    { path: "/products", name: "products", component: Products },
    { path: "/variants", name: "variants", component: ProductVariants },
    { path: "/pharmacies", name: "pharmacies", component: Pharmacies },
    { path: "/search", name: "search", component: Search },
  ],
  scrollBehavior: () => ({ top: 0, behavior: "smooth" }),
});

export default router;
