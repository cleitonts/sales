<template>
  <v-card class="overflow-visible">
    <the-card-title
      text="User"
      icon="fas fa-person"
      bg-color="bg-success-gradient"
      text-color="white"
    />

    <v-card-text>
      <v-form ref="usersForm">
        <v-row>
          <v-col cols="6">
            <v-text-field
              v-model="user.email"
              :rules="emailRules"
              label="E-mail"
              required
            />
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="user.profile"
              label="Profile"
              required
              :items="profileList"
            >
            </v-select>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>

    <v-container fluid class="justify-end d-flex">
      <v-btn class="success" @click="validate"> Send </v-btn>
    </v-container>
  </v-card>
</template>

<script>
import { useUserStore } from "~/stores";
import { useRoute } from "vue-router";
import { TheCardTitle } from "~/components";
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "UserEditView",
  components: { TheCardTitle },
  async setup() {
    const route = useRoute();
    const usersStore = useUserStore();
    await usersStore.getProfile();

    if (route.name !== "usersNew") {
      await usersStore.getById(route.params.id.toString());
    }

    const data = {
      tab: ref(1),
      usersForm: ref(null),
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          // eslint-disable-next-line max-len
          /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            v
          ) || "E-mail must be valid",
      ],
    };

    const { user, addUser: usersSend, profileList } = usersStore;
    return { user, usersSend, ...data, profileList };
  },
  unmounted() {
    const store = useUserStore();
    store.$reset();
  },
  methods: {
    validate: async function () {
      if (
        !(await this.usersForm.validate()).valid ||
        typeof this.user.email === "undefined"
      ) {
        this.tab = 1;
        return;
      }

      const user = await this.usersSend(this.user.email);
      this.$router.push({ name: "usersEdit", params: { id: user.id } });
    },
  },
});
</script>
