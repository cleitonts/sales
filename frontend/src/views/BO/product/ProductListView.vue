<template>
  <v-card class="overflow-visible">
    <the-card-title
      text="Product"
      icon="fa fa-product"
      bg-color="bg-secondary-gradient"
      text-color="white"
    >
      <template #after>
        <v-col cols="6" class="pa-0 d-flex justify-end">
          <v-btn
            rounded
            icon="fas fa-plus"
            class="bg-success-gradient position-absolute mt-n5 mb-3 text-white icon-fix"
            :to="{ name: 'productNew' }"
          />
        </v-col>
      </template>
    </the-card-title>
    <v-card-text>
      <base-grid
        v-model:page="page"
        v-model:limit="limit"
        :matrix="list"
        :header="headers"
        @update:limit="updateList()"
        @update:page="updateList()"
      >
        <template #action="{ element }">
          <td class="actions to-none pa-1">
            <v-btn
              color="primary"
              size="x-small"
              icon="fa fa-pencil"
              :to="{ name: 'productEdit', params: { id: element.id } }"
            />
          </td>
        </template>
      </base-grid>
    </v-card-text>
  </v-card>
</template>

<script>
import { defineComponent, ref } from "vue";
import { BaseGrid, TheCardTitle } from "~/components";
import { useProductBOStore } from "~/stores";
import { storeToRefs } from "pinia";

export default defineComponent({
  name: "ProductListView",
  components: { BaseGrid, TheCardTitle },
  async setup() {
    const data = {
      limit: ref(10),
      page: ref(1),
      headers: {
        action: "#",
        id: "Id",
        name: "Name",
        brand: "Brand",
        value: "Value",
      },
      search: null,
    };
    const productStore = useProductBOStore();
    await productStore.getProducts({
      limit: data.limit.value,
      page: data.page.value,
    });
    const { productsList: list } = storeToRefs(productStore);
    return { list, ...data };
  },
  unmounted() {
    const productStore = useProductBOStore();
    productStore.$reset();
  },
  methods: {
    updateList: async function () {
      const productStore = useProductBOStore();
      await productStore.getProducts({ limit: this.limit, page: this.page });
    },
  },
});
</script>
