import { defineStore } from "pinia";
import { useLocalStorage } from "~/helpers/localStorage";
import { EMessageType, useNotificationStore } from "~/stores/notificationStore";
import { inject, ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
  const configVars = inject("configVars");

  const emptyUser = {
    id: "",
    token: "",
    roles: [],
    exp: 0,
    iat: 0,
    username: "",
  };
  const STATE = {
    user: ref(useLocalStorage("user", "") || emptyUser),
    returnUrl: ref(""),
    list: ref([]),
  };

  const ACTIONS = {
    login: async function (username, password) {
      try {
        const data = {
          username: username,
          password: password,
        };
        // { headers: { "Content-Type": "application/json" }
        const returned = await configVars.$http.post(
          `${configVars.$apiUrl}/api/login_check`,
          JSON.stringify(data),
          { headers: { "Content-Type": "application/json" } }
        );
        STATE.user.value = {
          ...JSON.parse(atob(returned.data.token.split(".")[1])),
          token: returned.data.token,
        };

        localStorage.setItem("user", JSON.stringify(STATE.user.value));
        await configVars.$router.push({ name: "BOHome" });
      } catch (e) {
        const { addMessage } = useNotificationStore();
        addMessage({ text: "Invalid credentials.", type: EMessageType.Danger });
      }
    },
    logout: async function () {
      STATE.user.value = emptyUser;
      localStorage.removeItem("user");
      await configVars.$router.push({ name: "login" });
    },
  };

  return { ...STATE, ...ACTIONS };
});
