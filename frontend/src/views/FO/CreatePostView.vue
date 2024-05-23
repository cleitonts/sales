<template>
  <v-card class="overflow-visible">
    <the-card-title
      text="Product"
      icon="fas fa-gifts"
      bg-color="bg-success-gradient"
      text-color="white"
    >
      <template #after>
        <v-col cols="6" class="pa-0 d-flex justify-end">
          <v-tabs
            v-model="tab"
            class="bg-success-gradient position-absolute rounded mt-n5 mb-3"
            stacked
            grow
          >
            <v-tab value="1">
              <em class="far fa-file-lines" />
              <small class="pt-1">Description</small>
            </v-tab>
            <v-tab value="2">
              <em class="fas fa-address-book" />
              <small class="pt-1">Address</small>
            </v-tab>
            <v-tab value="3">
              <em class="fas fa-mobile-alt" />
              <small class="pt-1">Contact</small>
            </v-tab>
          </v-tabs>
        </v-col>
      </template>
    </the-card-title>
    <v-window v-model="tab">
      <v-window-item value="1" eager>
        <v-card-text>
          <v-form ref="productForm">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="product.name"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="Name"
                  required
                />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="4">
                <v-text-field
                  v-model="product.brand"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="Brand"
                  required
                />
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="product.category"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="Category"
                  required
                />
              </v-col>
              <v-col cols="4">
                <the-currency-input
                  v-model.lazy="product.value"
                  label="Base value"
                  :rules="[(v) => !!v || 'Item is required']"
                />
              </v-col>
            </v-row>

            <v-textarea
              v-model="product.additionalInformation"
              label="Addition information"
            />
          </v-form>
        </v-card-text>
      </v-window-item>
      <v-window-item value="2" eager>
        <v-card-text>
          <TheAddressAddList ref="addressForm" />
        </v-card-text>
      </v-window-item>
      <v-window-item value="3" eager>
        <v-card-text>
          <TheContactAddList ref="contactForm" />
        </v-card-text>
      </v-window-item>
    </v-window>
    <v-container fluid class="justify-end d-flex">
      <v-btn class="success" @click="validate"> Send</v-btn>
    </v-container>
  </v-card>
</template>

<script>
import { useProductBOStore } from "~/stores";
import { useRoute, useRouter } from "vue-router";
import {
  TheAddressAddList,
  TheContactAddList,
  TheCardTitle,
} from "~/components";
import { mask } from "vue-the-mask";
import { defineComponent, ref } from "vue";
import TheCurrencyInput from "@/components/form/TheCurrencyInput.vue";

export default defineComponent({
  components: {
    TheCurrencyInput,
    TheContactAddList,
    TheAddressAddList,
    TheCardTitle,
  },
  directives: { mask },
  async setup() {
    const route = useRoute();
    const router = useRouter();
    const productStore = useProductBOStore();
    if (route.name === "productEdit") {
      await productStore.getProduct(route.params.id);
    }

    const data = {
      tab: ref(0),
      productForm: ref(null),
      addressForm: ref(null),
      contactForm: ref(null),
    };

    const methods = {
      validate: async function () {
        if (!(await data.productForm.value.validate()).valid) {
          data.tab.value = 0;
          return;
        }

        if (
          true !== (await data.addressForm.value.$refs.form.validate()).valid
        ) {
          data.tab.value = 1;
          return;
        }
        if (
          true !== (await data.contactForm.value.$refs.form.validate()).valid
        ) {
          data.tab.value = 2;
          return;
        }

        const productStore = useProductBOStore();
        let method = "POST";
        if (route.name === "productEdit") {
          method = "PUT";
        }

        await productStore.send(method);
        await router.push({ name: "productList" });
      },
    };

    const { product } = productStore;
    return { product, ...data, ...methods };
  },
  unmounted() {
    const store = useProductBOStore();
    store.$reset();
  },
});
</script>
