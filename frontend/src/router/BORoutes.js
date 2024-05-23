import { TheMainLayout } from "@/components/index.js";
import {
  BOHomeView,
  CustomerEditView,
  CustomerListView,
  SupplierEditView,
  SupplierListView,
  UserEditView,
  UserListView,
  ProductEditView,
  ProductListView,
} from "@/views/index.js";

export const BORoutes = {
  path: "/bo",
  meta: {
    middleware: { requiresAuth: true },
  },
  children: [
    {
      path: "home",
      component: TheMainLayout,
      children: [
        {
          path: "",
          name: "BOHome",
          component: BOHomeView,
          meta: {
            pageTitle: "Home",
          },
        },
      ],
    },
    {
      path: "users",
      component: TheMainLayout,
      children: [
        {
          path: "",
          name: "usersList",
          component: UserListView,
          meta: {
            pageTitle: "Users list",
          },
        },
        {
          path: "new",
          name: "usersNew",
          component: UserEditView,
          meta: {
            pageTitle: "Create user",
          },
        },
        {
          path: "edit/:id",
          name: "usersEdit",
          component: UserEditView,
          meta: {
            pageTitle: "Edit user",
          },
        },
      ],
    },
    {
      path: "customer",
      component: TheMainLayout,
      children: [
        {
          path: "",
          name: "customerList",
          component: CustomerListView,
          meta: {
            pageTitle: "Customer list",
          },
        },
        {
          path: "edit/:id",
          name: "customerEdit",
          component: CustomerEditView,
          meta: {
            pageTitle: "Edit customer",
          },
        },
        {
          path: "new",
          name: "customerNew",
          component: CustomerEditView,
          meta: {
            pageTitle: "Create customer",
          },
        },
      ],
    },
    {
      path: "supplier",
      component: TheMainLayout,
      children: [
        {
          path: "",
          name: "supplierList",
          component: SupplierListView,
          meta: {
            pageTitle: "Supplier list",
          },
        },
        {
          path: "edit/:id",
          name: "supplierEdit",
          component: SupplierEditView,
          meta: {
            pageTitle: "Edit supplier",
          },
        },
        {
          path: "new",
          name: "supplierNew",
          component: SupplierEditView,
          meta: {
            pageTitle: "Create person",
          },
        },
      ],
    },
    {
      path: "product",
      component: TheMainLayout,
      children: [
        {
          path: "",
          name: "productList",
          component: ProductListView,
          meta: {
            pageTitle: "Products list",
          },
        },
        {
          path: "edit/:id",
          name: "productEdit",
          component: ProductEditView,
          meta: {
            pageTitle: "Edit product",
          },
        },
        {
          path: "new",
          name: "productNew",
          component: ProductEditView,
          meta: {
            pageTitle: "Create product",
          },
        },
      ],
    },
  ],
};
