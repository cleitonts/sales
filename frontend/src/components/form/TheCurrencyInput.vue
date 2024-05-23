<template>
  <v-text-field
    ref="inputRef"
    v-model="dateModel"
    :color="color"
    :label="label"
    :readonly="readonly"
    :rules="rules"
    @focus="doFocus($event)"
    @blur="focused = false"
    @keydown="isNumber($event)"
  />
</template>

<script>
import { nextTick } from "vue";
export default {
  name: "TheCurrencyInput",
  props: {
    modelValue: Number,
    label: String,
    color: String,
    readonly: { type: Boolean, default: false },
    rules: {
      type: Array,
      required: false,
    },
    format: {
      type: Object,
      default() {
        return {
          style: "currency",
          currency: "EUR",
        };
      },
    },
  },
  emits: ["update:modelValue"],
  data() {
    return {
      focused: false,
    };
  },
  computed: {
    dateModel: {
      get: function () {
        if (this.focused) {
          return this.modelValue;
        }
        if (this.format.style === "percent") {
          return Intl.NumberFormat("pt-PT", this.format).format(
            Number(this.modelValue / 100)
          );
        }
        return Intl.NumberFormat("pt-PT", this.format).format(this.modelValue);
      },
      set: function (e) {
        const thousandSeparator = Intl.NumberFormat("pt-PT")
          .format(11111)
          .replace(/\p{Number}/gu, "");
        const decimalSeparator = Intl.NumberFormat("pt-PT")
          .format(1.1)
          .replace(/\p{Number}/gu, "");

        let decimal = parseFloat(
          e
            .replace(new RegExp("\\" + thousandSeparator, "g"), "")
            .replace(new RegExp("\\" + decimalSeparator), ".")
        );
        if (isNaN(decimal)) decimal = 0;
        this.$emit(`update:modelValue`, Number(decimal.toFixed(2)));
      },
    },
  },
  methods: {
    doFocus: async function (e) {
      this.focused = true;
      await nextTick();
      e.target.select();
    },
    isNumber: function (e) {
      const keysAllowed = [
        "0",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        ".",
        "Tab",
        "Escape",
        "Backspace",
        "Delete",
        "ArrowRight",
        "ArrowLeft",
      ];
      const keyPressed = e.key;

      if (
        !(
          (e.ctrlKey && ["c", "v"].includes(keyPressed)) ||
          keysAllowed.includes(keyPressed)
        )
      ) {
        e.preventDefault();
      }
    },
  },
};
</script>
