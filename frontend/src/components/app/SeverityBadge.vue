<script setup lang="ts">
import { computed } from "vue";
import { Badge } from "@/components/ui/badge";
import type { Severity } from "@/types/dialogue.ts";

const props = defineProps<{ severity: Severity }>();

const variant = computed<"default" | "secondary" | "destructive">(() => {
  return { low: "secondary", medium: "default", high: "destructive" }[
    props.severity
  ] as "default" | "secondary" | "destructive";
});

const label = computed(() => {
  return { low: "Низкая", medium: "Средняя", high: "Высокая" }[props.severity];
});
</script>

<template>
  <Badge :variant="variant">{{ label }}</Badge>
</template>
