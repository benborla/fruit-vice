import { createRouter, createWebHistory } from "vue-router";

const baseUrl = import.meta.env.VITE_BUILD_ADDRESS;

export const routes = [
  {
    path: `${baseUrl}/`,
    component: () => import("@/layouts/Default.vue"),
    children: [
      { path: "", name: "Home", component: () => import("@/views/Home.vue") }
    ],
  },
  {
    path: `${baseUrl}/fruit/:id?`,
    component: () => import("@/layouts/Default.vue"),
    children: [
      { path: "", name: "Fruit", component: () => import("@/views/Form.vue") }
    ],
  },
  {
    path: `${baseUrl}/favorites`,
    component: () => import("@/layouts/Default.vue"),
    children: [
      { path: "", name: "Favorites", component: () => import("@/views/Favorites.vue") }
    ],
  },
  {
    path: `${baseUrl}/fruit/:id/view`,
    component: () => import("@/layouts/Default.vue"),
    children: [
      { path: "", name: "View Fruit", component: () => import("@/views/View.vue") }
    ],
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});
