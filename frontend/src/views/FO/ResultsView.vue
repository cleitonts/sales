<template>
  <v-card class="overflow-visible">
    <v-card-title class="mt-4 mb-5 d-flex">
      <v-col cols="6" class="pa-0">
        <div
          class="icon-wrapper text-center rounded-lg mt-n5 position-absolute d-flex align-center justify-center bg-info-gradient"
        >
          <v-icon icon="fa fa-book" color="white" />
        </div>
        <h3 class="pl-16 ml-3">Products</h3>
      </v-col>
    </v-card-title>

    <v-card-text class="px-0 px-sm-2">
      <v-row class="align-items-start">
        <v-col cols="6" class="d-sm-none" offset="3">
          <v-btn
            block
            color="info"
            class="d-sm-none"
            dark
            @click="dialog = true"
          >
            Filter
          </v-btn>
        </v-col>
        <v-col cols="3" class="d-none d-sm-block">
          <the-sidebar></the-sidebar>
        </v-col>
        <v-col cols="12" sm="9" class="d-flex flex-wrap" v-if="products[0]">
          <v-col
            :key="product.id"
            v-for="product in products"
            cols="6"
            sm="4"
            md="3"
            class="pa-2 d-flex product-item"
          >
            <v-card class="flex-column d-flex">
              <v-img src="/image.jpg" height="200px" cover></v-img>
              <v-card-title
                class="text-subtitle-2 font-weight-bold text-wrap pa-2"
              >
                {{ product.name }}
              </v-card-title>
              <v-card-text class="pb-1 px-2 text-body-2">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris
                sed gravida nisl.
              </v-card-text>
              <v-card-text class="text-caption py-0 px-2 color-primary">
                {{ product.brand }}
              </v-card-text>
              <v-card-text class="text-caption py-0 px-2 color-primary">
                {{ product.category.name }}
              </v-card-text>
              <v-card-actions
                class="text-h6 font-weight-bold align-self-end color-primary"
              >
                {{ formatter.format(product.value) }}
              </v-card-actions>
            </v-card>
          </v-col>
        </v-col>
        <v-col
          v-else
          cols="9"
          class="d-flex flex-wrap justify-center mt-6 text-subtitle-1"
        >
          No products found!
        </v-col>
      </v-row>
      <div class="text-center" v-if="products[0]">
        <v-container>
          <v-row justify="center">
            <v-col cols="8">
              <v-container class="max-width">
                <v-pagination
                  v-model="page"
                  class="my-4"
                  :length="Math.ceil(products[0].totalItems / 32)"
                  @update:model-value="search"
                ></v-pagination>
              </v-container>
            </v-col>
          </v-row>
        </v-container>
      </div>
    </v-card-text>
  </v-card>

  <v-dialog v-model="dialog" fullscreen>
    <v-card>
      <v-toolbar dark color="primary">
        <v-btn icon dark @click="dialog = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Filter</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn variant="text" @click="dialog = false"> Close </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text>
        <the-sidebar></the-sidebar>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
import { useRoute, useRouter } from "vue-router";
import { useProductFOStore } from "~/stores";
import { ref } from "vue";
import { storeToRefs } from "pinia";
import { TheSidebar } from "@/components/";

export default {
  name: "ResultsView",
  components: { TheSidebar },
  async setup() {
    const router = useRouter();
    const route = useRoute();

    let page = ref(1);
    let dialog = ref(false);

    let selectedBrands = ref([]);
    let selectedCategories = ref([]);
    if (route.query.brands) {
      selectedBrands = ref(JSON.parse(route.query.brands));
    }
    if (route.query.categories) {
      selectedCategories = ref(JSON.parse(route.query.categories));
    }

    console.log(selectedBrands.value)
    const productStore = useProductFOStore();
    await productStore.getProducts({
      brands: selectedBrands.value,
      categories: selectedCategories.value,
      term: route.params.search,
      page: page.value,
    });
    const { productsList: products } = storeToRefs(productStore);

    const formatter = new Intl.NumberFormat("pt-pt", {
      style: "currency",
      currency: "EUR",
    });

    return {
      page,
      route,
      router,
      products,
      formatter,
      productStore,
      selectedBrands,
      dialog,
      selectedCategories,
    };
  },
  methods: {
    search: async function () {
      window.scrollTo(0, 0);

      await this.productStore.getProducts({
        brands: this.selectedBrands,
        categories: this.selectedCategories,
        term: this.route.params.search,
        page: this.page,
      });

      console.log(this.route.params.search);
      await this.router.push({
        name: "results",
        params: { search: this.route.params.search },
        query: {
          brands: JSON.stringify(this.selectedBrands),
          categories: JSON.stringify(this.selectedCategories),
          page: this.page,
        },
      });
    },
  },
};
</script>
<style lang="sass">
@import "@/assets/template/variables/colors.scss"
.product-item
  & .v-card-title
    line-height: 1.3rem

.color-primary
  color: lighten($primary, 20%)

.category-selector
  & .v-expansion-panel-title
    padding: 10px

  & .v-checkbox-btn
    align-items: baseline

.v-expansion-panel--active > .v-expansion-panel-title:not(.v-expansion-panel-title--static)
  min-height: 48px !important

.align-items-start
  align-items: start
</style>
