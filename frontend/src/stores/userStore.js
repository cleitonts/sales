import { defineStore } from "pinia";
import { FormHelper } from "~/helpers";
import { inject, ref } from "vue";

export const useUserStore = defineStore("user", () => {
  const configVars = inject("configVars");

  const STATE = {
    profileList: ref([]),
    list: ref([]),
    user: ref({}),
  };

  const ACTIONS = {
    async getAll({ limit = null, page = null, email = null, profile = null }) {
      const params = { params: { limit, page, email, profile } };
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/users/`,
        params
      );
      STATE.list.value = data.data.data;
    },

    async getById(id) {
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/users/${id}`
      );
      STATE.user.value = data.data.data;
    },

    async getByClient(clientId) {
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/users/client/${clientId}`
      );
      STATE.list.value = data.data.data;
    },

    async getByToken(token) {
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/users/token/${token}`
      );
      STATE.user.value = data.data.data[0];
    },

    async getProfile() {
      const data = await configVars.$http.get(
        `${configVars.$apiUrl}/api/users/profile`
      );
      STATE.profileList.value = data.data.data;
    },

    async resetPassword(obj) {
      const data = await configVars.$http.put(
        `${configVars.$apiUrl}/api/user/resetPassword`,
        {
          email: obj.email,
          password: obj.password,
          passwordResetToken: obj.token,
        }
      );
      return data.data.data;
    },

    async addUser(email, clientId) {
      const formData = FormHelper.jsonToFormData({
        email: email,
        client: clientId,
      });
      const data = await configVars.$http.post(
        `${configVars.$apiUrl}/api/user/`,
        formData
      );
      STATE.list.value.push(data.data.data);
      return data.data.data;
    },
  };
  return { ...ACTIONS, ...STATE };
});
