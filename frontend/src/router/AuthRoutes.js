import { TheEmptyLayout } from "@/components/index.js";
import { LoginView, RegisterView, ResetPasswordView } from "@/views/index.js";

export const AuthRoutes = {
  path: "/auth",
  component: TheEmptyLayout,
  meta: {
    middleware: { requiresAuth: false },
  },
  children: [
    {
      path: "",
      redirect: () => {
        return { name: "login" };
      },
      meta: {
        pageTitle: "Authentication",
      },
    },
    {
      path: "login",
      name: "login",
      component: LoginView,
      meta: {
        pageTitle: "Authentication",
      },
    },
    {
      path: "register",
      name: "register",
      component: RegisterView,
      meta: {
        pageTitle: "User Registration",
      },
    },
    {
      path: "resetPassword/:token",
      name: "resetPassword",
      component: ResetPasswordView,
      meta: {
        pageTitle: "Password reset",
      },
    },
  ],
};
