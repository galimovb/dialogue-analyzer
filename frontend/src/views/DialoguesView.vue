<script setup lang="ts">
import { computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useDialoguesStore } from '@/stores/dialogues'
import DialogueList from '@/components/DialogueList.vue'
import DialogueConversation from '@/components/DialogueConversation.vue'
import { Button } from '@/components/ui/button'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const store = useDialoguesStore()

const selectedId = computed(() => {
  const value = route.query.dialog_id
  return value ? Number(value) : null
})

function select(id: number): void {
  router.push({ query: { dialog_id: id } })
}

async function logout(): Promise<void> {
  await auth.logout()
  router.push({ name: 'login' })
}

onMounted(() => {
  store.loadDialogues()
  if (selectedId.value) {
    store.openDialogue(selectedId.value)
  }
})

watch(selectedId, (id) => {
  if (id) {
    store.openDialogue(id)
  }
})
</script>

<template>
  <div class="flex h-screen flex-col bg-background">
    <header class="flex items-center justify-between border-b bg-primary px-5 py-3 text-primary-foreground">
      <div class="flex items-center gap-2.5">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-foreground/15 text-sm font-bold">
          DA
        </div>
        <div>
          <h1 class="font-semibold leading-none tracking-tight">Dialogue Analyzer</h1>
          <p class="mt-0.5 text-xs text-primary-foreground/70">Анализ диалогов менеджеров</p>
        </div>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <span class="text-primary-foreground/85">{{ auth.user?.name }}</span>
        <Button variant="secondary" size="sm" :disabled="auth.isLoading" @click="logout">Выйти</Button>
      </div>
    </header>

    <div class="grid min-h-0 flex-1 grid-cols-[340px_1fr]">
      <aside class="min-h-0 border-r bg-card">
        <DialogueList :selected-id="selectedId" @select="select" />
      </aside>
      <main class="min-h-0 bg-background">
        <DialogueConversation />
      </main>
    </div>
  </div>
</template>
