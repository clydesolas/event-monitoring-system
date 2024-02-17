import { createRouter, createWebHistory } from 'vue-router'
import DashboardView from '../views/DashboardView.vue';
import LoginView from '../views/LoginView.vue';
import EventsView from '../views/EventsView.vue';
import EventDetails from '../views/EventDetails.vue';
import ProfileView from '@/views/ProfileView.vue';
import StudentRecordView from '@/views/StudentRecordView.vue';
import ReportView from '@/views/ReportView.vue';
import ArchivedFilesView from '@/views/ArchivedFilesView.vue';
import store from "../store";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: LoginView
    },
    {
      path: '/Dashboard',
      name: 'DashboardView',
      component: DashboardView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/Events',
      name: 'EventsView',
      component: EventsView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/Events/Details/:pageId',
      name: 'EventDetails',
      component: EventDetails,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/StudentRecord',
      name: 'StudentRecordView',
      component: StudentRecordView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/Report',
      name: 'ReportView',
      component: ReportView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/Archived',
      name: 'ArchivedFilesView',
      component: ArchivedFilesView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    {
      path: '/Profile',
      name: 'ProfileView',
      component: ProfileView,
      beforeEnter: (to, from, next) => {
        const isAuthenticated = store.getters.getToken;
        const user = store.getters.getUser;
        if(isAuthenticated && user.role == 'admin'){
          next();
        }
      }
    },
    
  ]
})

export default router
