<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "footer",
  data() {
    return {
      author: ''
    }
  },
  mounted() {
    this.author = import.meta.env.VITE_APP_AUTHOR
  }
})
</script>

<template>
  <footer class="pt-5 my-5 text-muted border-top">
    {{ author }} &middot; &copy; {{ new Date().getFullYear() }}
  </footer>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  position: relative;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
