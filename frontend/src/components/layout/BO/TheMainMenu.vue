<template>
  <v-navigation-drawer
    v-model="mini"
    permanent
    :rail="!interfaceStore.menuOpen"
    expand-on-hover
  >
    <v-list dense class="main-menu">
      <template v-for="item in items" :key="item.title">
        <template v-if="item.group">
          <v-list-group
            :value="item.title"
            color="primary"
            expand-icon="fa-solid fa-sort-down fa-sm"
            collapse-icon="fa-solid fa-sort-up fa-sm"
          >
            <template #activator="{ props }">
              <v-list-item
                v-bind="props"
                :prepend-icon="item.icon"
                :title="item.title"
              />
            </template>

            <TheMenuItem
              v-for="ii in item.items"
              :key="ii.title"
              :item="ii"
              variant="tonal"
            />
          </v-list-group>
        </template>
        <TheMenuItem v-else :item="item" />
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { useInterfaceStore } from "~/stores";
import TheMenuItem from "~/components/layout/TheMenuItem.vue";

export default {
  name: "TheMainMenu",
  components: { TheMenuItem },
  setup() {
    const interfaceStore = useInterfaceStore();
    const mini = true;
    const items = [
      {
        group: true,
        icon: "fa fa-person",
        title: "People",
        items: [
          {
            title: "Customer",
            routeName: "customerList",
          },
          {
            title: "Supplier",
            routeName: "supplierList",
          },
        ],
      },
      {
        title: "Users",
        icon: "fa fa-user",
        routeName: "usersList",
      },
    ];

    return { interfaceStore, mini, items };
  },
};
</script>

<style lang="scss">
.main-menu {
  i.fa::before {
    margin-left: 5px;
  }
}
</style>
