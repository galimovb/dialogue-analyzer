<script setup lang="ts">
import { useDialoguesStore } from "@/stores/dialogues.ts";
import SeverityBadge from "@/components/app/SeverityBadge.vue";

const store = useDialoguesStore();
</script>

<template>
  <div class="border-b bg-card">
    <div class="flex items-center gap-2 px-5 pt-3 pb-2">
      <span
        class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
      >
        Анализ диалога
      </span>
      <span
        class="rounded-full px-2 py-0.5 text-xs font-medium"
        :class="
          store.events.length
            ? 'bg-primary/10 text-primary'
            : 'bg-muted text-muted-foreground'
        "
      >
        {{ store.events.length }}
      </span>
    </div>

    <p
      v-if="!store.events.length"
      class="px-5 pb-3 text-xs text-muted-foreground"
    >
      Проблемных ситуаций не обнаружено.
    </p>

    <ul v-else class="flex max-h-44 flex-col gap-1.5 overflow-y-auto px-3 pb-3">
      <li
        v-for="e in store.events"
        :key="e.id"
        class="flex items-start gap-3 rounded-lg border bg-background px-3 py-2"
      >
        <SeverityBadge :severity="e.severity" />
        <div class="min-w-0 flex-1">
          <p class="text-sm text-foreground">{{ e.description }}</p>
        </div>
      </li>
    </ul>
  </div>
</template>
