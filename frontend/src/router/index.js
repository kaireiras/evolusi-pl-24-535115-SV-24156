import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CommentView from '../views/CommentView.vue'

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/komentar', name: 'komentar', component: CommentView }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router