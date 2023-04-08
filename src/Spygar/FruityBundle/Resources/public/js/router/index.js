import Vue from 'vue'
import VueRouter from 'vue-router'

import fruitsList from "@/js/views/list";
import favoriteFruites from "@/js/views/favorites";

const routes = [
        { path: '/', name: 'fruitsList',  component: fruitsList },
        { path: '/favorite/fruites', name: 'favoriteFruites',  component: favoriteFruites },
];
Vue.use(VueRouter)

const router = new VueRouter({routes});

export default router;