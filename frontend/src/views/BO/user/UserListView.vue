<template>
  <v-card class="overflow-visible">
    <the-card-title
      text="Users"
      icon="fa fa-person"
      bg-color="bg-secondary-gradient"
      text-color="white"
    >
      <template #after>
        <v-col cols="6" class="pa-0 d-flex justify-end">
          <v-btn
            rounded
            icon="fas fa-plus"
            class="bg-success-gradient position-absolute mt-n5 mb-3 text-white icon-fix"
            :to="{ name: 'usersNew' }"
          />
        </v-col>
      </template>
    </the-card-title>

    <v-card-text>
      <v-form ref="searchForm" @submit.prevent="updateList()">
        <v-row>
          <v-btn type="submit" class="d-none"></v-btn>
          <v-col cols="6">
            <v-text-field
              v-model="email"
              :rules="emailRules"
              label="E-mail"
              required
            />
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="profile"
              label="Profile"
              required
              :items="profileList"
            >
            </v-select>
          </v-col>
        </v-row>
      </v-form>
      <base-grid
        v-model:page="page"
        v-model:limit="limit"
        class="collaborators-table"
        :matrix="users"
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
              :to="{ name: 'usersEdit', params: { id: element.id } }"
            />
          </td>
        </template>

        <template
          v-for="slotName in ['123', 'asd', 'dfasd']"
          #[slotName]="{ text }"
          :key="slotName"
        >
          <td>
            <span class="font-weight-bold">{{ text ? text : "-" }}</span>
          </td>
        </template>
      </base-grid>
    </v-card-text>
  </v-card>
</template>

<script>
import { BaseGrid, TheCardTitle } from "~/components";
import { useUserStore } from "~/stores";
import { defineComponent, ref } from "vue";
import { storeToRefs } from "pinia";

export default defineComponent({
  components: { BaseGrid, TheCardTitle },
  async setup() {
    const data = {
      limit: ref(10),
      page: ref(1),
      searchForm: ref(null),
      headers: {
        action: "#",
        id: "Id",
        email: "Email",
      },
      email: ref(""),
      profile: ref(""),
      search: null,
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          // eslint-disable-next-line max-len
          /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            v
          ) || "E-mail must be valid",
      ],
    };

    const usersStore = useUserStore();
    await usersStore.getAll({
      limit: data.limit.value,
      page: data.page.value,
      email: data.email.value,
      profile: data.profile.value,
    });
    await usersStore.getProfile();

    const { list: users, profileList } = storeToRefs(usersStore);

    return { users, profileList, ...data };
  },
  unmounted() {
    const store = useUserStore();
    store.$reset();
  },
  methods: {
    updateList: async function () {
      const usersStore = useUserStore();
      await usersStore.getAll({
        limit: this.limit,
        page: this.page,
        email: this.email,
        profile: this.profile,
      });
    },
  },
});
</script>
