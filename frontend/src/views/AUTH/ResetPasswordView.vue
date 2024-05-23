<template>
  <v-container fluid>
    <v-card class="mx-auto mt-16" elevation="15" max-width="400">
      <v-card-title class="text-center">
        <h3 class="pa-3">Password Reset</h3>
      </v-card-title>
      <v-card-text>
        <v-form ref="resetpasswordForm">
          <v-text-field
            v-model="email"
            :rules="emailRules"
            label="Email"
            variant="underlined"
            color="primary"
            :readonly="true"
            :disabled="true"
          />

          <v-text-field
            v-model="password"
            :rules="passwordRules"
            label="Password"
            type="password"
            variant="underlined"
            color="primary"
          />

          <v-text-field
            v-model="password2"
            :rules="passwordRules"
            label="Repeat password"
            type="password"
            variant="underlined"
            color="primary"
          />

          <v-checkbox
            v-model="terms"
            label="I agree to site terms and conditions"
            color="primary"
          />
        </v-form>
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

        <v-btn block color="success" variant="elevated" @click="validate">
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import { router } from "~/router";
import { defineComponent, ref } from "vue";
import {
  useUserStore,
  useNotificationStore,
  EMessageType,
  useAuthStore,
} from "~/stores";

export default defineComponent({
  setup: async function () {
    const userStore = useUserStore();
    const token = router.currentRoute.value.params.token;
    await userStore.getByToken(token);
    const { user, resetPassword } = userStore;

    const data = {
      email: ref(user.email),
      password: ref(""),
      password2: ref(""),
      terms: ref(false),
      passwordRules: [(v) => !!v || "Password is required"],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],

      resetpasswordForm: ref(null),
    };

    return { token, resetPassword, user, ...data };
  },
  methods: {
    validate: async function () {
      if (!(await this.resetpasswordForm.validate()).valid) {
        return;
      }
      if (this.password !== this.password2) {
        const notificationStore = useNotificationStore();
        notificationStore.addMessage({
          type: EMessageType.Danger,
          text: "The given password are differents.",
        });
        return;
      }

      await this.resetPassword({
        email: this.email,
        password: this.password,
        token: this.token,
      });

      const authstore = useAuthStore();
      await authstore.login(this.email, this.password);
    },
  },
});
</script>
