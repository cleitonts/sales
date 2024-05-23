<template>
  <v-divider></v-divider>
  <div class="font-weight-bold py-2">Categories</div>
  <v-expansion-panels variant="accordion" class="category-selector">
    <v-expansion-panel
      :key="category.id"
      v-for="category in categories"
      :title="category.name"
    >
      <v-expansion-panel-text>
        <div :key="child.id" v-for="child in category.children">
          <div class="font-weight-bold py-2">{{ child.name }}</div>
          <v-divider></v-divider>
          <v-checkbox
            v-model="selectedCategories"
            density="compact"
            hide-details
            :key="sub.id"
            v-for="sub in child.children"
            :label="sub.name"
            :value="sub.id"
            color="info"
            @change="prepareCategory"
          ></v-checkbox>
        </div>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>

  <v-divider></v-divider>
  <div class="font-weight-bold py-2">Brands</div>
  <div>
    <v-checkbox
      v-model="selectedBrands"
      density="compact"
      hide-details
      :key="brand"
      v-for="brand in brands"
      :label="brand.name"
      :value="brand.name"
      @change="prepareBrand"
      color="info"
      :value-comparator="
        (a, b) => {
          return a.toLowerCase() === b.toLowerCase();
        }
      "
    ></v-checkbox>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useProductFOStore } from "~/stores";

export default defineComponent({
  name: "TheSidebar",
  async setup() {
    const router = useRouter();
    const route = useRoute();

    let selectedBrands = ref([]);
    let selectedCategories = ref([]);
    if (route.query.brands) {
      selectedBrands = ref(JSON.parse(route.query.brands));
    }
    if (route.query.categories) {
      selectedCategories = ref(JSON.parse(route.query.categories));
    }

    const productStore = useProductFOStore();
    await productStore.getCategories();
    await productStore.getBrands();
    const { categoriesList: categories, brandsList: brands } =
      storeToRefs(productStore);

    return {
      route,
      router,
      categories,
      brands,
      selectedBrands,
      selectedCategories,
    };
  },
  methods: {
    prepareBrand: function () {
      let brands = this.selectedBrands;
      setTimeout(() => {
        this.search(this.selectedBrands, brands);
      }, 10);
    },
    prepareCategory: function () {
      let categories = this.selectedCategories;
      setTimeout(() => {
        this.search(this.selectedCategories, categories);
      }, 10);
    },
    search: async function (before, value) {
      if (value !== before) return;

      //there's a bug with router query, so it need to force the reload
      const productStore = useProductFOStore();
      await productStore.getProducts({
        brands: this.selectedBrands,
        categories: this.selectedCategories,
        term: this.route.params.search,
        page: 1,
      });

      await this.router.push({
        name: "results",
        query: {
          brands: JSON.stringify(this.selectedBrands),
          categories: JSON.stringify(this.selectedCategories),
          page: 1,
        },
      });
    },
  },
});
</script>
