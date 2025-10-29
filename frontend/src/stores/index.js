import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useUiStore = defineStore("ui", {
  state: () => ({
    loading: false,
    toast: null,
  }),
  actions: {
    start() {
      this.loading = true;
    },
    stop() {
      this.loading = false;
    },
    notify(msg) {
      this.toast = msg;
      setTimeout(() => (this.toast = null), 3000);
    },
  },
});
