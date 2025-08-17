import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import "../bootstrap.js";
import "semantic-ui-css/semantic.min.css";
import "semantic-ui-css/semantic.min.js";
import axios from "axios";
import Task from "../tasks/components/Task.vue";

axios.defaults.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const routes = [
    { path: "/tasks", component: Task },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const App = {
    template: `
        <router-view></router-view>
    `,
};

createApp(App).use(router).mount("#tasks");
