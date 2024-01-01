import { createWebHistory, createRouter } from "vue-router";

const routes = [

    // auth
    {
        path: "/",
        component: () => import("@/views/layouts/HomeLayout.vue"),
        children: [
            {
                path: "/",
                name: "Home Page",
                component: () => import("@/views/pages/HomePage.vue"),
            },
            {
                path: "/login",
                name: "Login Page",
                component: () => import("@/views/pages/LoginPage.vue"),
            },
        ],
    },

    // main app
    {
        path: "/app",
        component: () => import("@/views/layouts/AppLayout.vue"),
        children: [
            {
                path: "",
                name: "App Home Page",
                component: () => import("@/views/pages/app/HomePage.vue"),
            },
        ],
    },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;