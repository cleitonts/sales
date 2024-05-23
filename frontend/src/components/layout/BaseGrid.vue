<template>
  <div>
    <v-row v-if="limit" class="justify-content-between px-0">
      <v-col cols="3" md="2" class="ml-auto">
        <v-select
          v-model="internalLimit"
          label="Page limit"
          item-text="text"
          item-value="value"
          required
          :items="profiles"
          :rules="[(v) => !!v || 'Le type doit être renseignée']"
          @input="$emit('update:limit', $event)"
        />
      </v-col>
    </v-row>
    <v-table v-if="getCleanedMatrix.length">
      <slot name="thead">
        <thead>
          <tr>
            <th v-for="item in getHeader" :key="item">
              {{ item }}
            </th>
          </tr>
        </thead>
      </slot>
      <slot name="tbody">
        <tbody>
          <tr v-for="(item, index) in getCleanedMatrix" :key="index">
            <template v-for="(r, i) in item">
              <slot
                :item="item"
                :index="index"
                :element="item"
                :text="r"
                :name="i"
              >
                <td :key="i" :title="r">
                  <span v-if="i === 'id'">
                    {{ r ? r.substring(0, 4) + " .." : "-" }}
                  </span>
                  <span v-else>
                    {{ r ? r : "-" }}
                  </span>
                </td>
              </slot>
            </template>
          </tr>
        </tbody>
      </slot>
    </v-table>
    <div v-else>
      <div class="subtitle-bread text-center mt-15">
        There's no item to list. You can modify the filter or the page.
      </div>
    </div>
    <v-row v-if="page" class="justify-center mt-0">
      <slot name="pagination">
        <div class="text-center mt-4">
          <v-pagination
            v-model="internalPage"
            density="comfortable"
            active-color="secondary"
            :length="Math.ceil(matrix?.[0]?.totalItems / limit) || page + 6"
            :total-visible="7"
          />
        </div>
      </slot>
    </v-row>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "BaseGrid",
  props: {
    matrix: Array,
    // this order the columns in the table, you can switch with the given array
    header: Object,
    formatter: Object,
    page: {
      type: Number,
    },
    limit: {
      type: Number,
    },
  },
  emits: ["update:page", "updateSearch", "update:limit"],
  data: () => ({
    profiles: [10, 50, 100, 500],
  }),
  computed: {
    internalPage: {
      get() {
        return this.page;
      },
      set(e) {
        this.$emit("update:page", e);
        this.$emit("updateSearch");
      },
    },
    internalLimit: {
      get() {
        return this.limit;
      },
      set(e) {
        this.$emit("update:limit", e);
        this.$emit("updateSearch");
      },
    },
    getCleanedMatrix: function () {
      const array = [];
      const formatter = this.formatter;
      const header = this.header;
      if (typeof this.matrix === "undefined") {
        return [];
      }
      for (const element of this.matrix) {
        const item = element;
        const temp = {};
        for (const headerKey in header) {
          temp[headerKey] = item[headerKey];
        }
        // formatter
        for (const formatterKey in formatter) {
          temp[formatterKey] = this.doFormat(
            formatter[formatterKey],
            temp[formatterKey]
          );
        }
        array.push(temp);
      }
      return array;
    },
    getHeader: function () {
      if (this.header) {
        return this.header;
      }
      if (typeof this.matrix !== "undefined" && this.matrix.length) {
        return Object.keys(this.matrix[0]);
      }
      return 0;
    },
  },
  methods: {
    doFormat: function (type, value) {
      if (!value) {
        return value;
      }

      switch (type) {
        case "date":
          return Date.formatDate(value);
        default:
          return value;
      }
    },
  },
});
</script>
