import { createApp } from 'vue';
import { createHead } from "@vueuse/head";
import App from './components/App.vue';
import router from './router/routes.js';
import AxiosPlugin from './plugins/axios.js';
import fontAwesomePlugin from './plugins/fontawesome.js';
import Form from 'vform';
import { createPinia } from 'pinia';
import { useAuthStore } from './store/auth.js';
import { useHead } from '@vueuse/head';
import installToast from './plugins/toast.js';
import '../../public/css/bootstrap.css'
import '../../public/js/bootstrap.js'
import Swal from 'sweetalert2'

const app = createApp(App);
const head = createHead();

app.config.productionTip = true;

// Import and register your components
import Navbar from "./components/Navbar.vue";
import Loader from "./components/Loader.vue";
import Child from "./components/Child.vue";
import VButton from './components/Button.vue'
import AppPagination from './components/AppPagination.vue'
import { HasError, AlertError, AlertSuccess } from 'vform/components/bootstrap5'

[  Navbar, Child, HasError, AlertError, AlertSuccess, Loader, VButton, AppPagination].forEach(Component => {
    app.component(Component.name, Component);
});
app.component('fa', fontAwesomePlugin);

app.use(AxiosPlugin);
app.use(fontAwesomePlugin);
installToast(app);

app.use(createPinia());
// Provide the useAuthStore function globally
app.provide('authStore', useAuthStore());
useAuthStore().hydrateState();
// Use the Vue Router instance
app.use(router);
app.use(head);
app.config.globalProperties.$useHead = useHead;
app.config.globalProperties.$form = Form;


window.Title = "S-Tech Store";

function handleError(e,toast) {
    if (e.response && e.response.status === 429) {
        toast.error('Too many login attempts. Please try again later.', { position: 'bottom-right', duration: 3000 });
    } else if (e.response && e.response.status === 422) {
        toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
    } else if (e.response && e.response.status === 403) {
        toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
    } else {
        toast.error('An error occurred. Please try again later.', { position: 'bottom-right', duration: 3000 });
    }
}
window.handleError = handleError;

app.config.globalProperties.showConfirmationDialog = async function (title) {
    const result = await Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel it!'
    });

    return result.isConfirmed;
};

app.mount('#app');
