<template>
  <TheFOAppBar />

  <v-main ref="mainView">
    <v-container ref="mainContainer">
      <router-view v-slot="{ Component, route }" name="default">
        <transition
          :key="route.path"
          :name="route.meta.transition"
          mode="out-in"
          :duration="300"
        >
          <suspense>
            <template #default>
              <component :is="Component" :key="route.path" />
            </template>
            <template #fallback>
              <the-spinner :height="containerHeight + 'px'"></the-spinner>
            </template>
          </suspense>
        </transition>
      </router-view>
      <suspense>
        <the-notifications />
      </suspense>
    </v-container>
  </v-main>
</template>

<script setup>
import { TheNotifications } from "~/components";
import { ref } from "vue";
import TheFOAppBar from "@/components/layout/FO/TheFOAppBar.vue";

const mainContainer = ref(null);
let containerHeight = ref(0);
</script>
