import { defineStore } from "pinia";
import { inject, ref } from "vue";

export const useProductFOStore = defineStore("productFO", () => {
  const configVars = inject("configVars");

  const STATE = {
    productsList: ref([]),
    categoriesList: ref([]),
    brandsList: ref([]),
  };

  const ACTIONS = {
    getCategories: async function () {
      if (STATE.categoriesList.value.length) {
        return;
      }
      const returned = await configVars.$http.get(
        `${configVars.$apiUrl}/FO/category`
      );
      STATE.categoriesList.value = { ...returned.data.data };
    },
    getBrands: async function () {
      if (STATE.brandsList.value.length) {
        return;
      }
      const returned = await configVars.$http.get(
        `${configVars.$apiUrl}/FO/product/brands`
      );
      STATE.brandsList.value = { ...returned.data.data };
    },
    getProducts: async function (params) {
      const returned = await configVars.$http.get(
        `${configVars.$apiUrl}/FO/product`,
        { params: params }
      );
      STATE.productsList.value = { ...returned.data.data };
    },
  };

  return { ...STATE, ...ACTIONS };
});
