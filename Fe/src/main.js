import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import Toaster from "@meforma/vue-toaster";
import Default from './layout/wrapper/index.vue'
import Blank from './layout/wrapper/blank.vue'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(Toaster, {
    position: "top-right"
})
app.component("default-layout", Default);
app.component("blank-layout", Blank);

app.mount("#app")
