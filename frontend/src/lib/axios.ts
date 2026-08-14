import axios from "axios";

// Один инстанс для всего приложения.
// baseURL '/' — запросы к /api и /sanctum относительные:
// в дев их проксирует Vite, в проде — nginx (один origin → куки работают).
const api = axios.create({
  baseURL: "/",
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
});

// Перед любым изменяющим запросом убеждаемся, что CSRF-кука получена.
// Дальше axios сам подставит X-XSRF-TOKEN из куки (withXSRFToken).
const mutating = ["post", "put", "patch", "delete"];

api.interceptors.request.use(async (config) => {
  const isMutating = mutating.includes((config.method ?? "").toLowerCase());
  const hasToken = document.cookie
    .split("; ")
    .some((c) => c.startsWith("XSRF-TOKEN="));

  if (isMutating && !hasToken) {
    await api.get("/sanctum/csrf-cookie");
  }

  return config;
});

// Разворачиваем конверт:
//  - обычный  { success, data }             → data
//  - постранично { success, data, pagination } → { data, pagination }
api.interceptors.response.use((response) => {
  const body = response.data;
  if (body && typeof body === "object" && body.success === true) {
    response.data =
      "pagination" in body
        ? { data: body.data, pagination: body.pagination }
        : body.data;
  }
  return response;
});

export default api;
