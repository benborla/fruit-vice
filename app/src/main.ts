import { createApp } from "vue";

import App from "@/App.vue";
import { router } from "@/router";
import helpers from '@/services/helper';

import "bootstrap/scss/bootstrap.scss";
import "bootstrap-icons/font/bootstrap-icons.css";

const app = createApp(App)
  .use(helpers)
  .use(router)

router.isReady().then(() => app.mount("#app"));
