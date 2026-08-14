<script setup lang="ts">
import { computed } from "vue";
import { initials } from "@/lib/format.ts";

const props = defineProps<{ name: string; role?: string }>();
const label = computed(() => initials(props.name));
</script>

<template>
  <div
    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
    :class="
      role === 'manager'
        ? 'bg-primary text-primary-foreground'
        : 'bg-muted text-muted-foreground'
    "
  >
    {{ label }}
  </div>
</template>
