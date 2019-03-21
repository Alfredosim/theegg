import Home from './components/Home.vue';
import Transacciones from './components/transacciones/Main.vue';
import Categoria from './components/categorias/Main.vue';
import Usuario from './components/usuarios/Main.vue';
import Login from './components/auth/login.vue';

export const routes = [
	{
		path: '/',
		component: Home,
		meta: {
            requiresAuth: true
        }
	},
	{
		path: '/transacciones',
		component: Transacciones,
		meta: {
            requiresAuth: true
        }
	},
	{
		path: '/categorias',
		component: Categoria,
		meta: {
            requiresAuth: true
        }
	},
	{
		path: '/usuarios',
		component: Usuario,
		meta: {
            requiresAuth: true
        }
	},
	{
		path: '/login',
		component: Login
	}
];
