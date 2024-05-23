<template>
  <v-container fluid>
    <v-card class="mx-auto mt-16" elevation="15" max-width="400px">
      <v-card-title class="text-center">
        <h3 class="pa-3">Welcome back!</h3>
      </v-card-title>
      <v-card-text>
        <v-form v-model="form" @submit.prevent="onSubmit">
          <v-text-field
            v-model="email"
            :rules="emailRules"
            class="mb-2"
            clearable
            color="primary"
            label="Email"
            variant="underlined"
          />

          <v-text-field
            v-model="password"
            :rules="passwordRules"
            type="password"
            clearable
            color="primary"
            label="Password"
            placeholder="Enter your password"
            variant="underlined"
          />

          <router-link
            class="text-decoration-none d-block pa-3 text-center text-secondary"
            :to="{ name: 'register' }"
          >
            Register
          </router-link>

          <v-btn
            type="submit"
            :disabled="!form"
            block
            color="success"
            variant="elevated"
          >
            Sign in
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import { useAuthStore } from "~/stores";
export default {
  setup() {
    const authstore = useAuthStore();
    return { authstore };
  },
  data() {
    return {
      valid: false,
      form: false,
      password: "",
      passwordRules: [(v) => !!v || "Password is required"],
      email: "",
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
    };
  },
  methods: {
    onSubmit: async function () {
      await this.authstore.login(this.email, this.password);
    },
  },
};
</script>
