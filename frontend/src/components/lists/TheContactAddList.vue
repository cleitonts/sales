<template>
  <div class="wrapper">
    <v-form ref="form">
      <v-row class="justify-center">
        <v-btn class="info" @click="addContact()"> New</v-btn>
      </v-row>
      <v-row
        v-for="(row, index) in contacts"
        :key="index"
        :class="index % 2 !== 0 ? 'bg-surfaceLighten' : ''"
        class="py-4"
      >
        <v-col cols="2" class="d-flex justify-center align-center flex-column">
          <v-btn
            v-if="row.id && !row.delete"
            size="small"
            icon="fa fa-minus"
            color="warning"
            @click="deleteContact(index)"
          />
          <v-btn
            v-else-if="row.id && row.delete"
            title="keep"
            size="small"
            icon="fa fa-plus"
            color="success"
            @click="deleteContact(index)"
          />
          <v-btn
            v-else
            size="small"
            icon="fa fa-times"
            color="error"
            @click="deleteContact(index)"
          />
        </v-col>
        <v-col cols="10">
          <v-row>
            <v-col cols="6">
              <v-select
                v-model="row.type"
                label="Type"
                required
                :rules="[(v) => !!v || 'Item is required']"
                :items="types"
              >
              </v-select>
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="row.name"
                :rules="[(v) => !!v || 'Item is required']"
                label="Name"
                required
                density="compact"
              />
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="row.email"
                :rules="emailRules"
                label="E-mail"
                density="compact"
              />
            </v-col>
            <v-col v-if="false" cols="6" sm="3">
              <v-autocomplete
                v-model="row.ddi"
                :items="ddiList"
                :menu-props="{ maxHeight: 300 }"
                :rules="[(v) => !!v || 'Item is required']"
                label="Country"
                density="compact"
                autocomplete="nope"
                item-title="name"
                item-value="dial_code"
                :custom-filter="customFilter"
              >
                <template #selection="data">
                  {{ data.item.value }}
                </template>

                <template #item="{ props, item }">
                  <v-list-item
                    v-if="typeof item.raw !== 'object'"
                    v-bind="props"
                  />
                  <v-list-item
                    v-else
                    v-bind="props"
                    :title="item.raw.dial_code + ' ' + item.raw.name"
                  />
                </template>
              </v-autocomplete>
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="row.phone"
                :rules="[(v) => !!v || 'Item is required']"
                label="Phone"
                required
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
import { useContactStore } from "~/stores";
import { defineComponent } from "vue";
import { useRoute } from "vue-router";

export default defineComponent({
  name: "TheContactsAddList",
  setup: async function () {
    const route = useRoute();
    const contactStore = useContactStore();

    if (["supplierEdit", "supplierNew"].includes(route.name)) {
      contactStore.getTypes("supplier");
    }

    if (["customerEdit", "customerNew"].includes(route.name)) {
      contactStore.getTypes("customer");
    }

    const {
      list: contacts,
      ddiList,
      delete: deleteContact,
      createNewItem: addContact,
      types,
    } = contactStore;

    const DATA = {
      currentCountry: false,
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          // eslint-disable-next-line max-len
          /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            v
          ) || "E-mail must be valid",
      ],
    };

    const METHODS = {
      customFilter: function (item, queryText, itemText) {
        return (
          itemText.title
            .toLocaleLowerCase()
            .includes(queryText.toLocaleLowerCase()) ||
          itemText.value
            .toLocaleLowerCase()
            .includes(queryText.toLocaleLowerCase())
        );
      },
    };

    return {
      contacts,
      deleteContact,
      addContact,
      types,
      ddiList,
      ...DATA,
      ...METHODS,
    };
  },
  unmounted() {
    const store = useContactStore();
    store.$reset();
  },
});
</script>
