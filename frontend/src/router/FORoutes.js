import { TheFOLayout } from "@/components/index.js";
import {
  FOHomeView,
  ResetPasswordView,
  ResultsView,
  CreatePostView,
} from "@/views/index.js";

export const FORoutes = {
  path: "/",
  component: TheFOLayout,
  meta: {
    middleware: { requiresAuth: false },
  },
  children: [
    {
      path: "",
      name: "FOHome",
      component: FOHomeView,
    },
    {
      path: "create",
      name: "FOCreatePost",
      component: CreatePostView,
    },
    {
      path: "resetPassword/:token",
      name: "map",
      component: ResetPasswordView,
      meta: {
        pageTitle: "Password reset",
      },
    },
    {
      path: "results/:search?",
      name: "results",
      component: ResultsView,
      meta: {
        pageTitle: "Search results",
      },
    },
  ],
};
