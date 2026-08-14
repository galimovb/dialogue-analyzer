<script setup lang="ts">
import { computed, ref, watch } from "vue";
import { useRulesStore } from "@/stores/rules.ts";
import type { Rule } from "@/types/rule.ts";
import type { Severity } from "@/types/dialogue.ts";
import { Button } from "@/components/ui/button";

const props = defineProps<{ rule: Rule }>();
const store = useRulesStore();

const isEnabled = ref(props.rule.is_enabled);
const severity = ref<Severity>(props.rule.severity);
const configText = ref(JSON.stringify(props.rule.config, null, 2));
const jsonError = ref(false);
const isSaved = ref(false);

// Слепок текущей формы; сравнение с базой даёт «есть несохранённые правки».
function snapshot(): string {
  return `${isEnabled.value}|${severity.value}|${configText.value}`;
}
const baseline = ref(snapshot());
const isDirty = computed(() => snapshot() !== baseline.value);
const isSaving = computed(() => store.savingId === props.rule.id);

const defaultKeys = computed(() =>
  Object.keys(props.rule.default_config).join(", "),
);

// Любая правка сбрасывает статусы; после сохранения статусы не трогаем.
watch([isEnabled, severity, configText], () => {
  isSaved.value = false;
  jsonError.value = false;
});

function parseConfig(): Record<string, unknown> | null {
  try {
    const value = JSON.parse(configText.value);
    if (typeof value !== "object" || value === null || Array.isArray(value)) {
      return null;
    }
    return value as Record<string, unknown>;
  } catch {
    return null;
  }
}

async function save(): Promise<void> {
  const config = parseConfig();
  if (config === null) {
    jsonError.value = true;
    return;
  }

  const ok = await store.updateRule(props.rule.id, {
    is_enabled: isEnabled.value,
    severity: severity.value,
    config,
  });

  if (ok) {
    baseline.value = snapshot();
    isSaved.value = true;
  }
}
</script>

<template>
  <div
    class="rounded-xl border p-4 transition-colors"
    :class="isEnabled ? 'bg-card' : 'bg-muted/40'"
  >
    <div class="flex items-start justify-between gap-3">
      <div class="min-w-0">
        <h3 class="font-medium">{{ rule.name }}</h3>
        <p class="mt-0.5 font-mono text-xs text-muted-foreground">
          {{ rule.code }}
        </p>
      </div>

      <!-- Переключатель вкл/выкл -->
      <div class="flex shrink-0 items-center gap-2">
        <span class="text-xs text-muted-foreground">{{
          isEnabled ? "Включено" : "Выключено"
        }}</span>
        <button
          type="button"
          role="switch"
          :aria-checked="isEnabled"
          class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors"
          :class="isEnabled ? 'bg-primary' : 'bg-input'"
          @click="isEnabled = !isEnabled"
        >
          <span
            class="inline-block h-4 w-4 rounded-full bg-background shadow transition-transform"
            :class="isEnabled ? 'translate-x-4' : 'translate-x-0.5'"
          />
        </button>
      </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-[160px_1fr]">
      <div>
        <label class="mb-1.5 block text-xs font-medium text-muted-foreground"
          >Критичность</label
        >
        <select
          v-model="severity"
          class="h-9 w-full rounded-md border border-input bg-background px-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
        >
          <option value="low">Низкая</option>
          <option value="medium">Средняя</option>
          <option value="high">Высокая</option>
        </select>
      </div>

      <div>
        <label class="mb-1.5 block text-xs font-medium text-muted-foreground">
          Параметры (JSON)
        </label>
        <textarea
          v-model="configText"
          rows="4"
          spellcheck="false"
          class="w-full rounded-md border bg-background px-3 py-2 font-mono text-xs outline-none focus-visible:ring-2 focus-visible:ring-ring"
          :class="jsonError ? 'border-destructive' : 'border-input'"
        />
        <p v-if="jsonError" class="mt-1 text-xs text-destructive">
          Некорректный JSON-объект.
        </p>
        <p v-else-if="defaultKeys" class="mt-1 text-xs text-muted-foreground">
          Ключи по умолчанию: {{ defaultKeys }}
        </p>
      </div>
    </div>

    <div class="mt-4 flex items-center justify-end gap-3">
      <span v-if="isSaved" class="text-xs text-muted-foreground"
        >Сохранено · анализ пересчитывается</span
      >
      <Button size="sm" :disabled="!isDirty || isSaving" @click="save">
        {{ isSaving ? "Сохранение…" : "Сохранить" }}
      </Button>
    </div>
  </div>
</template>
