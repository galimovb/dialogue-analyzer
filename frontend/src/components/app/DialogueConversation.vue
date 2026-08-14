<script setup lang="ts">
import { computed } from "vue";
import { useDialoguesStore } from "@/stores/dialogues.ts";
import type { Message } from "@/types/dialogue.ts";
import InitialsAvatar from "@/components/app/InitialsAvatar.vue";
import OutcomeBadge from "@/components/app/OutcomeBadge.vue";
import AnalysisPanel from "@/components/app/AnalysisPanel.vue";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import { formatDateTime } from "@/lib/format.ts";

const store = useDialoguesStore();
const hasEarlier = computed(() => store.messagesPagination?.has_more ?? false);

function isManager(message: Message): boolean {
  return message.sender.role === "manager";
}
</script>

<template>
  <div
    v-if="!store.dialogue && !store.isLoadingMessages"
    class="flex h-full flex-col items-center justify-center gap-2 text-muted-foreground"
  >
    <div
      class="flex h-12 w-12 items-center justify-center rounded-full bg-muted text-lg"
    >
      💬
    </div>
    <p class="text-sm">Выберите диалог слева</p>
  </div>

  <div v-else class="flex h-full flex-col">
    <div
      v-if="store.dialogue"
      class="flex items-center justify-between border-b bg-muted/40 px-5 py-3"
    >
      <div class="flex items-center gap-3">
        <InitialsAvatar :name="store.dialogue.client.name" role="client" />
        <div>
          <div class="text-sm font-semibold">
            {{ store.dialogue.client.name }}
          </div>
          <div class="text-xs text-muted-foreground">
            Менеджер: {{ store.dialogue.manager.name }}
          </div>
        </div>
      </div>
      <OutcomeBadge :outcome="store.dialogue.outcome" />
    </div>

    <AnalysisPanel v-if="store.dialogue" />

    <div class="flex-1 overflow-y-auto px-5 py-4">
      <div v-if="hasEarlier" class="mb-4 flex justify-center">
        <Button
          variant="outline"
          size="sm"
          :disabled="store.isLoadingMessages"
          @click="store.loadEarlierMessages()"
        >
          {{ store.isLoadingMessages ? "Загрузка…" : "Загрузить ранние" }}
        </Button>
      </div>

      <div
        v-if="store.isLoadingMessages && !store.messages.length"
        class="flex flex-col gap-4"
      >
        <Skeleton
          v-for="i in 5"
          :key="i"
          class="h-14 w-1/2"
          :class="i % 2 ? 'self-start' : 'self-end'"
        />
      </div>

      <div class="flex flex-col gap-4">
        <div
          v-for="m in store.messages"
          :key="m.id"
          class="flex gap-2.5"
          :class="isManager(m) ? 'flex-row-reverse' : 'flex-row'"
        >
          <InitialsAvatar :name="m.sender.name" :role="m.sender.role" />
          <div
            class="flex max-w-[72%] flex-col"
            :class="isManager(m) ? 'items-end' : 'items-start'"
          >
            <span class="mb-1 px-1 text-xs text-muted-foreground">
              {{ m.sender.name }} · {{ formatDateTime(m.sent_at) }}
            </span>
            <div
              class="rounded-2xl px-3.5 py-2 text-sm whitespace-pre-wrap"
              :class="
                isManager(m)
                  ? 'rounded-tr-sm bg-primary text-primary-foreground'
                  : 'rounded-tl-sm bg-muted text-foreground'
              "
            >
              {{ m.text }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
