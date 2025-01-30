import { createSharedComposable, useActiveElement } from "@vueuse/core";
import { ref, computed, onMounted } from "vue";
export const _useShortcuts = () => {
  const macOS = computed(() => process.client && navigator && navigator.userAgent && navigator.userAgent.match(/Macintosh;/));
  const metaSymbol = ref(" ");
  const activeElement = useActiveElement();
  const usingInput = computed(() => {
    const usingInput2 = !!(activeElement.value?.tagName === "INPUT" || activeElement.value?.tagName === "TEXTAREA" || activeElement.value?.contentEditable === "true");
    if (usingInput2) {
      return activeElement.value?.name || true;
    }
    return false;
  });
  onMounted(() => {
    metaSymbol.value = macOS.value ? "\u2318" : "Ctrl";
  });
  return {
    macOS,
    metaSymbol,
    activeElement,
    usingInput
  };
};
export const useShortcuts = createSharedComposable(_useShortcuts);
