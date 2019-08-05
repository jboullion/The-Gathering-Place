import Vue from 'vue'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'

//import 'bootstrap/dist/css/bootswatch.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

//Vue.component('Board', Board);

Vue.use(BootstrapVue);

export const eventBus = new Vue();

new Vue({
  el: '#app',
  render: h => h(App)
})

function interval(func, wait, times){
  var interv = function(w, t){
      return function(){
          if(typeof t === "undefined" || t-- > 0){
              setTimeout(interv, w);
              try{
                  func.call(null);
              }
              catch(e){
                  t = 0;
                  throw e.toString();
              }
          }
      };
  }(wait, times);

  setTimeout(interv, wait);
}

Array.prototype.remove = function() {
  var what, a = arguments, L = a.length, ax;
  while (L && this.length) {
      what = a[--L];
      while ((ax = this.indexOf(what)) !== -1) {
          this.splice(ax, 1);
      }
  }
  return this;
};