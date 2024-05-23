<template>
  <v-container fluid>
    <v-card class="mx-auto mt-16" elevation="15" max-width="400">
      <v-card-title class="text-center">
        <h3 class="pa-3">User Registration</h3>
      </v-card-title>
      <v-card-text>
        <v-text-field
          v-model="first"
          label="First name"
          variant="underlined"
          color="primary"
        />

        <v-text-field
          v-model="last"
          label="Last name"
          variant="underlined"
          color="primary"
        />

        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="Email"
          variant="underlined"
          color="primary"
        />

        <v-text-field
          v-model="password"
          :rules="passwordRules"
          label="Password"
          type="password"
          variant="underlined"
          color="primary"
        />

        <v-checkbox
          v-model="terms"
          label="I agree to site terms and conditions"
          color="primary"
        />
      </v-card-text>

      <v-divider />

      <router-link
        class="text-decoration-none d-block pa-3 text-center text-secondary"
        :to="{ name: 'login' }"
      >
        Sign in
      </router-link>

      <v-card-actions>
        <v-spacer />

        <v-btn block color="success" variant="elevated">
          Complete Registration
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import { router } from "~/router";
import { defineComponent } from "vue";
import { useUserStore } from "~/stores";

export default defineComponent({
  setup: async function () {
    console.log(router.currentRoute.value.name);
    const userStore = useUserStore();
    if (router.currentRoute.value.name === "resetPassword") {
      await userStore.getByToken(router.currentRoute.value.params.token);
    }
    const { user } = userStore;

    const data = {
      first: null,
      last: null,
      email: null,

      password: null,
      terms: false,
      passwordRules: [(v) => !!v || "Password is required"],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
    };

    return { user, ...data };
  },
});
</script>
