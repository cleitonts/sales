<template>
  <v-card class="overflow-visible">
    <the-card-title
      text="Supplier"
      icon="fas fa-person"
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
              <em class="fas fa-person" />
              <small class="pt-1">Who</small>
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
          <v-form ref="personForm">
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="supplier.name"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="Name"
                  required
                />
              </v-col>

              <v-col cols="6">
                <v-text-field
                  v-model="supplier.nickName"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="Nickname"
                  required
                />
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="supplier.document"
                  v-mask="'###.###.###'"
                  :rules="[(v) => !!v || 'Item is required']"
                  label="NIF"
                  required
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="supplier.secondaryDocument"
                  label="NISS"
                />
              </v-col>
            </v-row>

            <v-textarea
              v-model="supplier.additionalInformation"
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
import { useSupplierStore } from "~/stores";
import { useRoute, useRouter } from "vue-router";
import {
  TheAddressAddList,
  TheContactAddList,
  TheCardTitle,
} from "~/components";
import { mask } from "vue-the-mask";
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "SupplierEditView",
  components: {
    TheContactAddList,
    TheAddressAddList,
    TheCardTitle,
  },
  directives: { mask },
  async setup() {
    const route = useRoute();
    const router = useRouter();
    const supplierStore = useSupplierStore();
    if (route.name === "supplierEdit") {
      await supplierStore.getOne(route.params.id);
    }

    const data = {
      tab: ref(0),
      personForm: ref(null),
      addressForm: ref(null),
      contactForm: ref(null),
    };

    const methods = {
      validate: async function () {
        if (!(await data.personForm.value.validate()).valid) {
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

        const supplierStore = useSupplierStore();
        let method = "POST";
        if (route.name === "supplierEdit") {
          method = "PUT";
        }

        await supplierStore.send(method);
        await router.push({ name: "supplierList" });
      },
    };

    const { supplier } = supplierStore;
    return { supplier, ...data, ...methods };
  },
  unmounted() {
    const store = useSupplierStore();
    store.$reset();
  },
});
</script>
