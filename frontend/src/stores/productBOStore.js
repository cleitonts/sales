import { defineStore } from "pinia";
import { inject, ref } from "vue";

export const useProductBOStore = defineStore("productBO", () => {
  const configVars = inject("configVars");

  const STATE = {
    product: ref({}),
    productsList: ref([]),
  };

  const ACTIONS = {
    getProducts: async function (params) {
      const returned = await configVars.$http.get(
        `${configVars.$apiUrl}/api/product/`,
        { params: params }
      );
      STATE.productsList.value = [...returned.data.data];
    },
    getProduct: async function (id) {
      const returned = await configVars.$http.get(
        `${configVars.$apiUrl}/api/product/${id}`
      );
      STATE.productsList.value = { ...returned.data.data };
    },
  };

  return { ...STATE, ...ACTIONS };
});
