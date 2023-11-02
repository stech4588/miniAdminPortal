<template>
    <div class="login-container">
        <h2>Login</h2>
        <fa :icon="['fas', 'user']" />
        <form @submit.prevent="handleLogin">
            <div class="form-group">
                <label for="email">Email:</label>
                <input v-model="email" type="text" id="email" class="form-control" />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input v-model="password" type="password" id="password" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</template>

<script>
import { inject } from 'vue';

export default {
    name: 'Login',

    setup() {
        const authStore = inject('authStore'); // Inject the authStore
        return {
            authStore
        };
    },

    data() {
        return {
            email: '',
            password: ''
        };
    },

    mounted() {
        this.$useHead({
            title: 'Login123',
            description: 'Login page'
        });
    },

    methods: {
        async handleLogin() {
            try {
                const response = await this.LoginApiCall(this.email, this.password);
                this.authStore.login(response.data);
                this.$toast.success('User Login successfully', { position: 'bottom-right', duration: 3000 });
                // Redirect to the landing page
                this.$router.push('/dashboard');
            } catch (e) {
                this.handleError(e);
            }
        },

        handleError(e) {
            if (e.response && e.response.status === 429) {
                this.$toast.error('Too many login attempts. Please try again later.', { position: 'bottom-right', duration: 3000 });
            } else if (e.response && e.response.status === 422) {
                this.$toast.error('Invalid email or password.', { position: 'bottom-right', duration: 3000 });
            } else {
                this.$toast.error('An error occurred. Please try again later.', { position: 'bottom-right', duration: 3000 });
            }
        },

        async LoginApiCall(email, password) {
            try {
                const response = await this.$axios.post('/api/login', {
                    email: email,
                    password: password,
                });
                return response.data;
            } catch (e) {
                throw e;
            }
        }
    }
};
</script>

<style scoped>
.login-container {
    max-width: 300px;
    margin: 0 auto;
    padding: 20px;
}

</style>
