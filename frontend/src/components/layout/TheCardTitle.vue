<template>
  <v-card-title class="mt-4 mb-5 d-flex">
    <v-col cols="6" class="pa-0">
      <div
        v-if="icon"
        class="icon-wrapper text-center rounded-lg mt-n5 position-absolute d-flex align-center justify-center"
        :class="bgColor"
      >
        <v-icon :icon="icon" :color="textColor" />
      </div>
      <h3 class="pl-16 ml-3">
        {{ text }}
      </h3>
    </v-col>

    <slot name="after" />
  </v-card-title>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "TheCardTitle",
  props: {
    icon: String,
    text: String,
    bgColor: {
      type: String,
      default: "bg-primary",
    },
    textColor: {
      type: String,
      default: "grey-darken-4",
    },
  },
});
</script>
