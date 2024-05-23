import { defineStore } from "pinia";
import { useLocalStorage } from "~/helpers/localStorage.js";
import { ref } from "vue";

export const useInterfaceStore = defineStore("interface", () => {
  const STATE = {
    menuOpen: ref(useLocalStorage("menuOpen", "false")),
    pageTitle: ref(""),
  };

  const ACTIONS = {
    switchMenu: function () {
      STATE.menuOpen.value = !STATE.menuOpen.value;
    },
  };

  return { ...STATE, ...ACTIONS };
});
