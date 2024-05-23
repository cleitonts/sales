import { defineStore } from "pinia";
import {
  useAddressStore,
  useContactStore,
  useNotificationStore,
} from "~/stores";
import { FormHelper } from "~/helpers";
import { ref, inject } from "vue";

export const useCustomerStore = defineStore("customer", () => {
  const configVars = inject("configVars");

  const STATE = {
    list: ref([]),
    customer: ref({}),
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
        .get(`${configVars.$apiUrl}/api/customer/`, params)
        .then((d) => {
          return d.data.data;
        });
    },

    getOne: async function (id) {
      const path = `${configVars.$apiUrl}/api/customer/${id}`;

      const data = await configVars.$http.get(path).then((d) => {
        return d.data.data;
      });
      STATE.customer.value = { ...STATE.customer.value, ...data };
      useAddressStore().list = data.addresses;
      useContactStore().list = data.contacts;
    },

    send: async function (method) {
      if (undefined === STATE.customer.value) {
        throw new Error("Undefined customer.");
      }

      STATE.customer.value.addresses = useAddressStore().list;
      STATE.customer.value.contacts = useContactStore().list;
      const formData = FormHelper.jsonToFormData(STATE.customer);
      if (method == "POST") {
        return await this.post(formData);
      }
      return await this.put();
    },

    post: async function (formData) {
      return await configVars.$http
        .post(`${configVars.$apiUrl}/api/customer/`, formData)
        .then((d) => {
          useNotificationStore().processReturn(d.data.notify);
          return d.data.data;
        });
    },
    put: async function () {
      return await configVars.$http
        .put(
          `${configVars.$apiUrl}/api/customer/${STATE.customer.value.id}/`,
          STATE.customer.value
        )
        .then((d) => {
          useNotificationStore().processReturn(d.data.notify);
          return d.data.data;
        });
    },
  };

  return { ...ACTIONS, ...STATE };
});
