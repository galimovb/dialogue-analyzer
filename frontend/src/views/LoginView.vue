<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

const email = ref("admin@example.com");
const password = ref("password");
const error = ref("");

const auth = useAuthStore();
const router = useRouter();

async function submit() {
  if (auth.isLoading) return;
  error.value = "";
  try {
    await auth.login(email.value, password.value);
    router.push({ name: "dialogues" });
  } catch (e: unknown) {
    const err = e as { response?: { data?: { errorMessage?: string } } };
    error.value = err.response?.data?.errorMessage ?? "Не удалось войти";
  }
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-muted p-4">
    <Card class="w-full max-w-sm">
      <CardHeader>
        <CardTitle class="text-2xl">Вход</CardTitle>
        <CardDescription>Dialogue Analyzer</CardDescription>
      </CardHeader>
      <CardContent>
        <form class="flex flex-col gap-4" @submit.prevent="submit">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              v-model="email"
              type="email"
              required
              autocomplete="username"
            />
          </div>
          <div class="grid gap-2">
            <Label for="password">Пароль</Label>
            <Input
              id="password"
              v-model="password"
              type="password"
              required
              autocomplete="current-password"
            />
          </div>
          <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
          <Button type="submit" class="w-full" :disabled="auth.isLoading">
            {{ auth.isLoading ? "Вход…" : "Войти" }}
          </Button>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
