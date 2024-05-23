import { defineStore } from "pinia";
import { ref } from "vue";

export const EMessageType = {
  Danger: "error",
  Success: "success",
  Warning: "warning",
  Info: "info",
};

export const useNotificationStore = defineStore("notification", () => {
  const STATE = {
    lastId: ref(0),
    messages: ref([]),
  };

  const ACTIONS = {
    addMessage: function ({ text, type, time = 5000 }) {
      STATE.lastId.value++;
      const message = {
        text,
        type,
        time,
        id: STATE.lastId.value,
      };
      STATE.messages.value.unshift(message);
    },
    removeMessage: function (id) {
      const indexToDelete = STATE.messages.value.findIndex((n) => n.id === id);
      if (indexToDelete !== -1) {
        STATE.messages.value.splice(indexToDelete, 1);
      }
    },
    processReturn: function (arr) {
      for (const r in arr) {
        this.addMessage({ text: arr[r].text, type: arr[r].type });
      }
    },
  };

  return { ...STATE, ...ACTIONS };
});
