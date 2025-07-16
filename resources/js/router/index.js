import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      component: () => import("../layouts/blank.vue"),
        children: [
        {
          path: "",
          component: () => import("../pages/user/dashboard.vue"),
        },
      ],
    },
    {
      path: "/login",
      component: () => import("../layouts/blank.vue"),
      children: [
        {
          path: "",
          component: () => import("../pages/auth/login.vue"),
        },
      ],
      beforeEnter: (to, from, next) => {
        const userToken = localStorage.getItem("userToken");

        if (userToken ) {
            next("/dashboard");
        } else {
          next();
        }
      },
    },
    {
      path: "/:pathMatch(.*)*",
      component: () => import("../layouts/blank.vue"),
      children: [
        {
          path: "",
          component: () => import("../pages/[...all].vue"),
        },
      ],
    },
    {
      path: "/unauthorized",
      component: () => import("../layouts/blank.vue"),
      children: [
        {
          path: "",
          component: () => import("../pages/auth/unauthorized.vue"),
        },
      ],
    },

    // authenticated

    //admin
    // {
    //   path: "/dashboard",
    //   component: () => import("../layouts/admin/default.vue"),
    //   children: [
    //     {
    //       path: "",
    //       component: () => import("../pages/admin/dashboard.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: "/users",
    //   component: () => import("../layouts/admin/default.vue"),
    //   children: [
    //     {
    //       path: "",
    //       component: () => import("../pages/admin/user/index.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: "/areas",
    //   component: () => import("../layouts/admin/default.vue"),
    //   children: [
    //     {
    //       path: "",
    //       component: () => import("../pages/admin/area/index.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: "/findings",
    //   component: () => import("../layouts/admin/default.vue"),
    //   children: [
    //     {
    //       path: "",
    //       component: () => import("../pages/admin/findings/index.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: "/performance",
    //   component: () => import("../layouts/admin/default.vue"),
    //   children: [
    //     {
    //       path: "",  
    //       name: "performance-overview",
    //       component: () => import("../pages/admin/performance/index.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //     {
    //       path: "analytics", 
    //       name: "performance-analytics",
    //       component: () => import("../pages/admin/performance/index.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkAdminLogin(next);
    //       },
    //     },
    //   ],
    // }

    //user
    // {
    //   path: "/user-dashboard",
    //   component: () => import("../layouts/user/default.vue"),
    //   children: [
    //     {
    //       path: "",
    //       component: () => import("../pages/user/dashboard.vue"),
    //       beforeEnter: (to, from, next) => {
    //         checkLogin(next);
    //       },
    //     },
    //   ],
    // },
  ],
});

function checkLogin(next) {
  const userToken = localStorage.getItem("userToken");
  if (userToken) {
    next();
  } else {
    localStorage.removeItem("userToken");
    localStorage.removeItem("userData");

    alert("You need to login to access this page.");
    next("/login");
  }
}

function checkAdminLogin(next) {
  const userToken = localStorage.getItem("userToken");
  const userData = JSON.parse(localStorage.getItem("userData"));
  if (userToken) {
    next();
  } else {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
      },
    });
    Toast.fire({
      icon: "error",
      title: "Oops...",
      text: "Silahkan Login ulang",
    });
  }
}

export default router;
