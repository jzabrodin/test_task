import Vue from 'vue';
import {BootstrapVue, IconsPlugin} from "bootstrap-vue";
import ProductInfoController from './components/CompanyHistoricalDataComponent.vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

new Vue({
    el: '#app',
    render: h => h(ProductInfoController)
});