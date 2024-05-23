<template>
  <div class="wrapper">
    <v-form ref="form">
      <v-row class="justify-center">
        <v-btn class="info" @click="addAddress()"> New</v-btn>
      </v-row>
      <v-row
        v-for="(item, index) in addresses"
        :key="index"
        :class="index % 2 !== 0 ? 'bg-surfaceLighten' : ''"
        class="py-4"
      >
        <v-col cols="2" class="d-flex justify-center align-center flex-column">
          <v-btn
            v-if="item.id && !item.delete"
            title="Delete"
            size="small"
            icon="fa fa-minus"
            color="warning"
            @click="deleteAddress(index)"
          />
          <v-btn
            v-else-if="item.id && item.delete"
            title="keep"
            size="small"
            icon="fa fa-plus"
            color="success"
            @click="deleteAddress(index)"
          />
          <v-btn
            v-else
            size="small"
            icon="fa fa-times"
            color="error"
            @click="deleteAddress(index)"
          />
        </v-col>
        <v-col cols="10">
          <v-row>
            <v-col cols="6" sm="3">
              <v-select
                v-model="item.type"
                label="Type"
                required
                :rules="[(v) => !!v || 'Item is required']"
                :items="types"
              >
              </v-select>
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.zip"
                v-mask="'#####-###'"
                label="Zip"
                density="compact"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.country"
                :rules="[(v) => !!v || 'Item is required']"
                label="Country"
                density="compact"
                required
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.district"
                :rules="[(v) => !!v || 'Item is required']"
                label="District"
                required
                density="compact"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.city"
                :rules="[(v) => !!v || 'Item is required']"
                label="City"
                required
                density="compact"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.address"
                :rules="[(v) => !!v || 'Item is required']"
                label="Address"
                required
                density="compact"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.addressComplement"
                label="Complement"
                density="compact"
              />
            </v-col>
            <v-col cols="6" sm="3">
              <v-text-field
                v-model="item.number"
                label="Number"
                density="compact"
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>
import { mask } from "vue-the-mask";
import { useAddressStore } from "~/stores";
import { defineComponent } from "vue";
import { useRoute } from "vue-router";

export default defineComponent({
  name: "TheAddressAddList",
  directives: { mask },
  setup: async function () {
    const route = useRoute();
    const addressStore = useAddressStore();

    if (["supplierEdit", "supplierNew"].includes(route.name)) {
      addressStore.getTypes("supplier");
    }

    if (["customerEdit", "customerNew"].includes(route.name)) {
      addressStore.getTypes("customer");
    }

    const {
      delete: deleteAddress,
      list: addresses,
      createNewItem: addAddress,
      types,
    } = addressStore;

    return { addresses, deleteAddress, addAddress, types };
  },
  unmounted() {
    const store = useAddressStore();
    store.$reset();
  },
});
</script>
