import { defineStore } from "pinia";
import { inject, ref } from "vue";

const newContact = {
  name: "",
  phone: "",
  email: "",
  type: "",
};

export const useContactStore = defineStore("contact", () => {
  const configVars = inject("configVars");

  const STATE = {
    list: ref([]),
    types: ref([]),
  };

  const ACTIONS = {
    createNewItem(item = { ...newContact }) {
      if (!item) return;
      STATE.list.value.push(item);
    },
    delete(index) {
      if (STATE.list.value[index].id) {
        STATE.list.value[index].delete = !STATE.list.value[index].delete;
        return;
      }
      STATE.list.value.splice(index, 1);
    },
    getTypes: async function (type) {
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/${type}/contact/types`
      );
      STATE.types.value.push(...data.data.data);
    },
  };

  return { ...STATE, ...ACTIONS };
});
