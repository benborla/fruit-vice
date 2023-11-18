/// <reference types="vite/client" />
VITE_APP_NAME="Fruity Vice App"
VITE_APP_AUTHOR="Ben Borla"

/* eslint-disable */
declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}
