import { defineStore } from "pinia";
import { computed, ref } from "vue";
import axios from "axios";

export const useLocationStore = defineStore("location", () => {
  const count = ref(0);

  const state = {
    elements: ref([]),
  };

  const getters = {
    doubleCount: computed(() => count.value * 2),
  };

  const actions = {
    getData: async function () {
      const query =
        "" +
        "[out:json];" +
        "(" +
        "node['amenity'='car_sharing'](40.50492419507126,-8.050918579101562,40.742574997542924,-7.7872467041015625);" +
        "node['tourism'='museum'](40.50492419507126,-8.050918579101562,40.742574997542924,-7.7872467041015625);" +
        ");" +
        "out body;" +
        ">;" +
        "out skel qt;";
      axios
        .post("https://www.overpass-api.de/api/interpreter?", query, {
          headers: {
            Accept: "application/json",
            "Content-Type": "text/plain",
          },
        })
        .then(function (response) {
          console.log(response.data);
          state.elements.value = response.data.elements;
        });
    },
  };

  return { ...state, ...getters, ...actions };
});
