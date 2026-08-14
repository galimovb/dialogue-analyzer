import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/lib/axios";
import type {
  AnalysisEvent,
  DialogueListItem,
  Message,
  Pagination,
  Paginated,
} from "@/types/dialogue";

export const useDialoguesStore = defineStore("dialogues", () => {
  // Список диалогов.
  const dialogues = ref<DialogueListItem[]>([]);
  const dialoguesPagination = ref<Pagination | null>(null);
  const isLoadingDialogues = ref(false);

  // Выбранный диалог и его сообщения (oldest → newest для отображения).
  const dialogue = ref<DialogueListItem | null>(null);
  const messages = ref<Message[]>([]);
  const messagesPagination = ref<Pagination | null>(null);
  const isLoadingMessages = ref(false);

  // Результаты анализа выбранного диалога (от критичных к менее критичным).
  const events = ref<AnalysisEvent[]>([]);

  async function loadDialogues(): Promise<void> {
    if (isLoadingDialogues.value) return;
    isLoadingDialogues.value = true;
    try {
      const { data } =
        await api.get<Paginated<DialogueListItem>>("/api/dialogues");
      dialogues.value = data.data;
      dialoguesPagination.value = data.pagination;
    } catch (e) {
      console.error(e);
    } finally {
      isLoadingDialogues.value = false;
    }
  }

  async function loadMoreDialogues(): Promise<void> {
    const pg = dialoguesPagination.value;
    if (isLoadingDialogues.value || !pg?.has_more) return;
    isLoadingDialogues.value = true;
    try {
      const { data } = await api.get<Paginated<DialogueListItem>>(
        "/api/dialogues",
        {
          params: { page: pg.current_page + 1 },
        },
      );
      dialogues.value.push(...data.data);
      dialoguesPagination.value = data.pagination;
    } catch (e) {
      console.error(e);
    } finally {
      isLoadingDialogues.value = false;
    }
  }

  async function openDialogue(id: number): Promise<void> {
    if (isLoadingMessages.value) return;
    isLoadingMessages.value = true;
    messages.value = [];
    messagesPagination.value = null;
    events.value = [];
    try {
      // Инфо о диалоге обычно уже есть в списке — берём оттуда.
      // Детальный запрос нужен только при прямом заходе по ссылке.
      const known = dialogues.value.find((d) => d.id === id);
      const [detail, page, eventsData] = await Promise.all([
        known
          ? Promise.resolve(known)
          : api
              .get<DialogueListItem>(`/api/dialogues/${id}`)
              .then((r) => r.data),
        api.get<Paginated<Message>>(`/api/dialogues/${id}/messages`),
        api
          .get<AnalysisEvent[]>(`/api/dialogues/${id}/events`)
          .then((r) => r.data),
      ]);
      dialogue.value = detail;
      messages.value = [...page.data.data].reverse();
      messagesPagination.value = page.data.pagination;
      events.value = eventsData;
    } catch (e) {
      console.error(e);
      dialogue.value = null;
    } finally {
      isLoadingMessages.value = false;
    }
  }

  async function loadEarlierMessages(): Promise<void> {
    const pg = messagesPagination.value;
    if (isLoadingMessages.value || !pg?.has_more || !dialogue.value) return;
    isLoadingMessages.value = true;
    try {
      const { data } = await api.get<Paginated<Message>>(
        `/api/dialogues/${dialogue.value.id}/messages`,
        { params: { page: pg.current_page + 1 } },
      );
      messages.value = [...[...data.data].reverse(), ...messages.value];
      messagesPagination.value = data.pagination;
    } catch (e) {
      console.error(e);
    } finally {
      isLoadingMessages.value = false;
    }
  }

  return {
    dialogues,
    dialoguesPagination,
    isLoadingDialogues,
    dialogue,
    messages,
    messagesPagination,
    isLoadingMessages,
    events,
    loadDialogues,
    loadMoreDialogues,
    openDialogue,
    loadEarlierMessages,
  };
});
