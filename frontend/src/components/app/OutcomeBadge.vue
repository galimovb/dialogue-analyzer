<script setup lang="ts">
import { Badge } from "@/components/ui/badge";

defineProps<{ outcome: "purchased" | "not_purchased" }>();
</script>

<template>
  <Badge :variant="outcome === 'purchased' ? 'default' : 'secondary'">
    {{ outcome === "purchased" ? "Купил" : "Не купил" }}
  </Badge>
</template>
