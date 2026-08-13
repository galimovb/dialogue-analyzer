<script setup lang="ts">
import { computed } from 'vue'
import { useDialoguesStore } from '@/stores/dialogues'
import InitialsAvatar from '@/components/InitialsAvatar.vue'
import OutcomeBadge from '@/components/OutcomeBadge.vue'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'

defineProps<{ selectedId: number | null }>()
const emit = defineEmits<{ select: [id: number] }>()

const store = useDialoguesStore()
const hasMore = computed(() => store.dialoguesPagination?.has_more ?? false)
</script>

<template>
  <div class="flex h-full flex-col">
    <div class="flex items-center justify-between border-b px-4 py-3.5">
      <h2 class="text-sm font-semibold tracking-tight">Диалоги</h2>
      <span class="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground">
        {{ store.dialoguesPagination?.total ?? '—' }}
      </span>
    </div>

    <div class="flex-1 overflow-y-auto">
      <div v-if="store.isLoadingDialogues && !store.dialogues.length" class="space-y-1 p-2">
        <Skeleton v-for="i in 7" :key="i" class="h-16 w-full rounded-lg" />
      </div>

      <button
        v-for="d in store.dialogues"
        :key="d.id"
        class="flex w-full items-center gap-3 border-l-2 px-4 py-3 text-left transition-colors"
        :class="d.id === selectedId
          ? 'border-l-primary bg-accent'
          : 'border-l-transparent hover:bg-muted/60'"
        @click="emit('select', d.id)"
      >
        <InitialsAvatar :name="d.client.name" role="client" />
        <div class="min-w-0 flex-1">
          <div class="flex items-center justify-between gap-2">
            <span class="truncate text-sm font-medium">{{ d.client.name }}</span>
            <OutcomeBadge :outcome="d.outcome" />
          </div>
          <div class="mt-0.5 flex items-center justify-between gap-2 text-xs text-muted-foreground">
            <span class="truncate">{{ d.manager.name }}</span>
            <span class="shrink-0">{{ d.messages_count }} сообщ.</span>
          </div>
        </div>
      </button>
    </div>

    <div v-if="hasMore" class="border-t p-2">
      <Button
        variant="ghost"
        class="w-full"
        :disabled="store.isLoadingDialogues"
        @click="store.loadMoreDialogues()"
      >
        {{ store.isLoadingDialogues ? 'Загрузка…' : 'Загрузить ещё' }}
      </Button>
    </div>
  </div>
</template>
