<script setup lang="ts">
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth.ts";
import { Button } from "@/components/ui/button";

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

function navClass(name: string): string {
  const base = "rounded-md px-3 py-1.5 text-sm font-medium transition-colors";
  return route.name === name
    ? `${base} bg-primary-foreground/15 text-primary-foreground`
    : `${base} text-primary-foreground/70 hover:bg-primary-foreground/10 hover:text-primary-foreground`;
}

async function logout(): Promise<void> {
  await auth.logout();
  router.push({ name: "login" });
}
</script>

<template>
  <header
    class="flex items-center justify-between border-b bg-primary px-5 py-3 text-primary-foreground"
  >
    <nav class="flex items-center gap-1">
      <RouterLink :to="{ name: 'dialogues' }" :class="navClass('dialogues')"
        >Диалоги</RouterLink
      >
      <RouterLink
        v-if="auth.isAdmin"
        :to="{ name: 'rules' }"
        :class="navClass('rules')"
        >Правила</RouterLink
      >
    </nav>

    <div class="flex items-center gap-3 text-sm">
      <span class="text-primary-foreground/85">{{ auth.user?.email }}</span>
      <Button
        variant="secondary"
        size="sm"
        :disabled="auth.isLoading"
        @click="logout"
        >Выйти</Button
      >
    </div>
  </header>
</template>
