import Vue from 'vue'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

//Vue.component('Board', Board);

Vue.use(BootstrapVue);

export const eventBus = new Vue();

new Vue({
  el: '#app',
  render: h => h(App)
})

