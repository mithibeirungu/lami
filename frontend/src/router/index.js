import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CarList from '../components/CarList.vue'
import CarDetail from '../components/CarDetail.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminCreateCarView from '../views/AdminCreateCarView.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import AdminCarsListView from '../views/AdminCarsListView.vue'
import AdminEditCarView from '../views/AdminEditCarView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/articles',
      name: 'articles',
      component: CarList,
    },
    {
      path: '/brands',
      name: 'brands',
      component: () => import('../views/BrandsView.vue'),
    },
    {
      path: '/brands/:brand',
      name: 'brand-detail',
      component: () => import('../views/BrandDetailView.vue'),
    },
    {
      path: '/cars/:id',
      name: 'car',
      component: CarDetail,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },
    {
      path: '/admin/create',
      name: 'admin-create',
      component: AdminCreateCarView,
    },
    {
      path: '/admin/cars',
      name: 'admin-cars',
      component: AdminCarsListView,
    },
    {
      path: '/admin/cars/:id/edit',
      name: 'admin-edit-car',
      component: AdminEditCarView,
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminHome.vue')
    },
    {
      path: '/admin/dashboard',
      name: 'admin-dashboard',
      component: AdminDashboard,
      beforeEnter: (to, from, next) => {
        try {
          const user = JSON.parse(localStorage.getItem('user') || 'null')
          if (!user || user.type_of_user !== 'admin') {
            return next({ name: 'login' })
          }
        } catch (e) {
          return next({ name: 'login' })
        }
        next()
      }
    },
    {
      path: '/admin/register',
      name: 'admin-register',
      component: () => import('../views/AdminRegisterView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
  ],
})

export default router
