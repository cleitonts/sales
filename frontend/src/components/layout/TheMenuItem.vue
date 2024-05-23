<template>
  <v-list-item :key="item.title" link :to="{ name: item.routeName }">
    <template v-if="item.icon" #prepend>
      <v-icon :icon="item.icon" size="small" />
    </template>
    <v-list-item-title>
      {{ item.title }}
    </v-list-item-title>
  </v-list-item>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "TheMenuItem",
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
});
</script>
