import { defineStore } from "pinia";
import {
  useAddressStore,
  useContactStore,
  useNotificationStore,
} from "~/stores";
import { FormHelper } from "~/helpers";
import { inject, ref } from "vue";

export const useSupplierStore = defineStore("supplier", () => {
  const configVars = inject("configVars");

  const STATE = {
    list: ref([]),
    supplier: ref({}),
  };

  const ACTIONS = {
    getAll: async function ({ limit = null, page = null }) {
      const params = {
        params: {
          limit,
          page,
        },
      };
      STATE.list.value = await configVars.$http
        .get(`${configVars.$apiUrl}/api/supplier/`, params)
        .then((d) => {
          return d.data.data;
        });
    },

    getOne: async function (id) {
      const path = `${configVars.$apiUrl}/api/supplier/${id}`;

      const data = await configVars.$http.get(path).then((d) => {
        return d.data.data;
      });
      STATE.supplier.value = { ...STATE.supplier.value, ...data };
      useAddressStore().list = data.addresses;
      useContactStore().list = data.contacts;
    },

    send: async function (method) {
      if (undefined === STATE.supplier.value) {
        throw new Error("Undefined customer.");
      }
      STATE.supplier.value.addresses = useAddressStore().list;
      STATE.supplier.value.contacts = useContactStore().list;
      const formData = FormHelper.jsonToFormData(STATE.supplier.value);
      if (method == "POST") {
        return await this.post(formData);
      }
      return await this.put(STATE.supplier.value);
    },

    put: async function (formData) {
      return await configVars.$http
        .put(
          `${configVars.$apiUrl}/api/supplier/${STATE.supplier.value.id}/`,
          formData
        )
        .then((d) => {
          useNotificationStore().processReturn(d.data.notify);
          return d.data.data;
        });
    },

    post: async function (formData) {
      return await configVars.$http
        .post(`${configVars.$apiUrl}/api/supplier/`, formData)
        .then((d) => {
          useNotificationStore().processReturn(d.data.notify);
          return d.data.data;
        });
    },
  };

  return { ...ACTIONS, ...STATE };
});
