<script setup lang="ts">
import { computed, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useDialoguesStore } from "@/stores/dialogues";
import AppHeader from "@/components/app/AppHeader.vue";
import DialogueList from "@/components/app/DialogueList.vue";
import DialogueConversation from "@/components/app/DialogueConversation.vue";

const route = useRoute();
const router = useRouter();
const store = useDialoguesStore();

const selectedId = computed(() => {
  const value = route.query.dialog_id;
  return value ? Number(value) : null;
});

function select(id: number): void {
  router.push({ query: { dialog_id: id } });
}

onMounted(() => {
  store.loadDialogues();
  if (selectedId.value) {
    store.openDialogue(selectedId.value);
  }
});

watch(selectedId, (id) => {
  if (id) {
    store.openDialogue(id);
  }
});
</script>

<template>
  <div class="flex h-screen flex-col bg-background">
    <AppHeader />

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
