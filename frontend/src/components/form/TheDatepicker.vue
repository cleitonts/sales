<template>
  <Datepicker
    v-model="dateModel"
    auto-apply
    text-input
    model-type="dd/MM/yyyy"
    dark
    :enable-time-picker="!hours"
  >
    <template #dp-input>
      <v-text-field v-model="dateModel" :rules="rules" :label="label" />
    </template>
  </Datepicker>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import { mask } from "vue-the-mask";
import { useModelWrapper } from "~/helpers";
import { defineComponent } from "vue";

export default defineComponent({
  name: "TheDatepicker",
  components: { Datepicker },
  directives: { mask },
  props: {
    modelValue: String,
    hours: { type: Boolean, default: false },
    label: String,
    rules: {
      type: Array,
      required: false,
    },
  },
  emits: ["update:modelValue"],
  setup(props, { emit }) {
    return {
      dateModel: useModelWrapper(props, emit, "modelValue"),
    };
  },
});
</script>
