<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg mb-4 border-bottom">
      <router-link :to="routes[0].path" class="navbar-brand d-flex align-items-center text-decoration-none">
        <h1>Fruity Vice App</h1>
      </router-link>
      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item text-uppercase" v-for="route in routes" :key="route.path">
            <router-link :to="route.path" class="nav-link" :title="route.children[0].name"
              :class="{ active: isActive(route.path) }" v-if="!['Fruit', 'View Fruit'].includes(route.children[0].name)">
              <i class="bi bi-house-fill" v-if="route.path === `${siteUrl}/`"></i>
              {{ route.path !== `${siteUrl}/` ? route.children[0].name : "" }}
            </router-link>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import { useRouter } from "vue-router";
import { routes } from "@/router";

const siteUrl = import.meta.env.VITE_BUILD_ADDRESS;
const router = useRouter();
const activeRoute = computed(() => router.currentRoute.value.path);
const isActive = (path: string) => path === activeRoute.value;
</script>

<style lang="scss" scoped>
ul {
  list-style: none;
}
</style>
