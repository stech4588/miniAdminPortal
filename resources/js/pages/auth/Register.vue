<template>
    <div class="register-container">
        <h2>Register</h2>
        <form @submit.prevent="register">
            <div class="form-group">
                <label for="username">Username:</label>
                <input v-model="username" type="text" id="username" class="form-control" />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input v-model="email" type="email" id="email" class="form-control" />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input v-model="password" type="password" id="password" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</template>

<script>
import {useHead} from "@vueuse/head";
import axios from "axios";
export default {
    name: 'Register',
    setup() {
        useHead({
            title: 'Register',
            description: 'Registration page'
        });
    },
    data() {
        return {
            username: '',
            email: '',
            password: '',
        };
    },
    methods: {
        async register() {
            try {
                const response = await this.$axios.post('/api/register', {
                    username: this.username,
                    email: this.email,
                    password: this.password,
                });

                this.$toast.success('User Registered successfully', { position: 'bottom-right', duration: 3000 });
                // Redirect to the landing page
                this.$router.push('/login');
                // After successful registration, you can navigate to another route
                // this.$router.push('/dashboard');
            } catch (error) {
                console.error('Registration error:', error);
            }
        },
    },
};
</script>

<style scoped>
.register-container {
    max-width: 300px;
    margin: 0 auto;
    padding: 20px;
}
</style>
