<script setup lang="ts">
import { onMounted } from "vue";
import { useRulesStore } from "@/stores/rules";
import AppHeader from "@/components/app/AppHeader.vue";
import RuleCard from "@/components/app/RuleCard.vue";
import { Skeleton } from "@/components/ui/skeleton";

const store = useRulesStore();

onMounted(() => {
  store.loadRules();
});
</script>

<template>
  <div class="flex h-screen flex-col bg-background">
    <AppHeader />

    <div class="min-h-0 flex-1 overflow-y-auto">
      <div class="mx-auto max-w-3xl px-5 py-6">
        <div class="mb-5">
          <h2 class="text-lg font-semibold">Правила анализа</h2>
          <p class="mt-0.5 text-sm text-muted-foreground">
            Включение, критичность и параметры правил. Логика правил задаётся в
            коде; здесь — только их настройка. Изменения применяются к анализу
            автоматически.
          </p>
        </div>

        <div
          v-if="store.isLoading && !store.rules.length"
          class="flex flex-col gap-4"
        >
          <Skeleton v-for="i in 4" :key="i" class="h-40 w-full" />
        </div>

        <div v-else class="flex flex-col gap-4">
          <RuleCard v-for="rule in store.rules" :key="rule.id" :rule="rule" />
        </div>
      </div>
    </div>
  </div>
</template>
