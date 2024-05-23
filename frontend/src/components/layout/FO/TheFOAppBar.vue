<template>
  <v-app-bar class="bg-primary-gradient">
    <img :width="120" :src="imgUrl" />
    <v-spacer />
    <router-link :to="{ name: 'FOCreatePost' }">
      <v-btn class="fa fa-plus info mr-3" size="small" icon title="New" />
    </router-link>

    <v-btn
      class="fa fa-sign-out"
      icon
      title="Logout"
      @click="authStore.logout()"
    />
  </v-app-bar>
</template>

<script>
import { useAuthStore } from "~/stores";

export default {
  setup() {
    console.log(import.meta.url);
    const imgUrl = new URL("/logo.png", import.meta.url).href;
    const authStore = useAuthStore();
    return { authStore, imgUrl };
  },
};
</script>
