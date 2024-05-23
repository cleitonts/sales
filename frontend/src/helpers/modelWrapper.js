import { computed } from "vue";

export default function useModelWrapper(props, emit, name = "modelValue") {
  return computed({
    get: () => props.modelValue,
    set: (value) => emit(`update:${name}`, value),
  });
}
