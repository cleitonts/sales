import { createRouter, createWebHistory } from "vue-router";
import { FORoutes } from "@/router/FORoutes.js";
import { BORoutes } from "@/router/BORoutes.js";
import { AuthRoutes } from "@/router/AuthRoutes.js";

import { useAuthStore, useInterfaceStore } from "~/stores";

export const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  linkActiveClass: "active",
  routes: [
    BORoutes,
    FORoutes,
    AuthRoutes,

    // catch all redirect to home page
    { path: "/:pathMatch(.*)*", redirect: "/" },
  ],
});

router.beforeEach(async (to, from, next) => {
  if (
    !["login", "resetPassword"].includes(to.name) &&
    !useAuthStore().user &&
    to.meta.requiresAuth
  ) {
    next({ name: "login" });
  }
  next();
});

router.afterEach((to) => {
  const interfaceStore = useInterfaceStore();
  const pageTitle = to.meta.pageTitle || interfaceStore.pageTitle;
  document.title = `${import.meta.env.VITE_APP_NAME} - ${pageTitle}`;
});
