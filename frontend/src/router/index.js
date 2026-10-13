import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'
import CommentView from '../views/CommentView.vue'
import AdminBlogList from '../views/admin/AdminBlogList.vue'
import AdminBlogCreate from '../views/admin/AdminBlogCreate.vue'

const routes = [
  { path: '/', redirect: '/blog' },
  { path: '/login', name: 'login', component: LoginView },

  // User & Admin (Wajib Login)
  { path: '/blog', name: 'home', component: HomeView, meta: { requiresAuth: true } },
  { path: '/komentar', name: 'komentar', component: CommentView, meta: { requiresAuth: true } },

  // Khusus Admin (Wajib Login & Role Admin)
  { path: '/admin/blog', name: 'admin-list', component: AdminBlogList, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/admin/blog/create', name: 'admin-create', component: AdminBlogCreate, meta: { requiresAuth: true, requiresAdmin: true } },

  { path: '/:pathMatch(.*)*', redirect: '/login' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')

  if (to.path === '/login' && token) {
    return next(user.role === 'admin' ? '/admin/blog' : '/blog')
  }

  if (to.meta.requiresAuth && !token) {
    return next('/login')
  }

  if (to.meta.requiresAdmin && user.role !== 'admin') {
    return next('/blog')
  }

  next()
})

export default router