<template>
  <v-main ref="mainView">
    <v-container ref="mainContainer" fluid>
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

<script>
import TheNotifications from "./TheNotifications.vue";
import { ref, defineComponent } from "vue";

export default defineComponent({
  name: "TheEmptyLayout",
  components: { TheNotifications },
  setup() {
    const data = {
      mainContainer: ref(null),
      containerHeight: ref(0),
    };
    return { ...data };
  },
  mounted() {
    this.containerHeight = this.mainContainer.$vuetify.display.height - 152;
  },
});
</script>
