<template>
  <v-card class="overflow-visible">
    <v-card-title class="mt-4 mb-5 d-flex">
      <v-col cols="6" class="pa-0">
        <div
          class="icon-wrapper text-center rounded-lg mt-n5 position-absolute d-flex align-center justify-center bg-info-gradient"
        >
          <v-icon icon="fa fa-book" color="white" />
        </div>
        <h3 class="pl-16 ml-3">Categories</h3>
      </v-col>
    </v-card-title>

    <v-card-text>
      <v-row>
        <v-col
          :key="category.id"
          v-for="category in categories"
          :cols="(12 / categories).toFixed(0)"
          class="pa-2"
        >
          <v-card class="pa-2">
            <h3 class="mb-4">{{ category.name }}</h3>
            <v-expansion-panels variant="accordion" class="category-selector">
              <v-expansion-panel
                :key="cat.id"
                v-for="cat in category.children"
                :title="cat.name"
              >
                <v-expansion-panel-text>
                  <v-list lines="one" density="compact">
                    <v-list-item :key="child.id" v-for="child in cat.children">
                      <router-link
                        :to="{
                          name: 'results',
                          query: {
                            categories: JSON.stringify([child.id]),
                          },
                        }"
                        class="text-decoration-none"
                        >{{ child.name }}</router-link
                      >
                    </v-list-item>
                  </v-list>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-card class="pa-2 mt-6">
            <h3>Brands</h3>
            <v-list lines="one" density="compact">
              <v-list-item :key="brand.brand" v-for="brand in brands">
                <router-link
                  class="text-decoration-none"
                  :to="{
                    name: 'results',
                    query: {
                      brands: JSON.stringify([brand.name]),
                    },
                  }"
                >
                  {{ brand.name }}
                </router-link>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import { useRouter } from "vue-router";
import { useProductFOStore } from "~/stores";
import { storeToRefs } from "pinia";

export default {
  name: "FOHomeView",
  async setup() {
    const router = useRouter();

    const productStore = useProductFOStore();
    await productStore.getCategories();
    await productStore.getBrands();
    const { categoriesList: categories, brandsList: brands } =
      storeToRefs(productStore);

    return { router, categories, brands };
  },
};
</script>
