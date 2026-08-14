import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/lib/axios";
import type { Rule, UpdateRulePayload } from "@/types/rule";

export const useRulesStore = defineStore("rules", () => {
  const rules = ref<Rule[]>([]);
  const isLoading = ref(false);
  const savingId = ref<number | null>(null);

  async function loadRules(): Promise<void> {
    if (isLoading.value) return;
    isLoading.value = true;
    try {
      const { data } = await api.get<Rule[]>("/api/rules");
      rules.value = data;
    } catch (e) {
      console.error(e);
    } finally {
      isLoading.value = false;
    }
  }

  /**
   * Сохранить настройки правила. Возвращает true при успехе,
   * чтобы вью могла показать статус, не зная деталей запроса.
   */
  async function updateRule(
    id: number,
    payload: UpdateRulePayload,
  ): Promise<boolean> {
    if (savingId.value !== null) return false;
    savingId.value = id;
    try {
      const { data } = await api.patch<Rule>(`/api/rules/${id}`, payload);
      const index = rules.value.findIndex((rule) => rule.id === id);
      if (index !== -1) {
        rules.value[index] = data;
      }
      return true;
    } catch (e) {
      console.error(e);
      return false;
    } finally {
      savingId.value = null;
    }
  }

  return { rules, isLoading, savingId, loadRules, updateRule };
});
