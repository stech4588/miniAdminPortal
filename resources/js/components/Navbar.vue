<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            Mini Admin Portal
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Other navigation items -->
                    <li v-if="!authStore.isAuthenticated" class="nav-item">
                        <router-link to="/login" class="nav-link">Login</router-link>
                    </li>
                    <li v-if="!authStore.isAuthenticated" class="nav-item">
                        <router-link to="/register" class="nav-link">Register</router-link>
                    </li>
                    <li v-else class="nav-item">
                        <a class="nav-link logout-link" @click="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    inject: ['authStore'],
    name: 'Navbar',
    methods: {
        async logout() {
            try {
                await this.LogoutApiCall();
                this.authStore.logout(); // No need to pass data here, assuming the authStore handles logout
                this.$toast.success('User Logout successfully', { position: 'bottom-right', duration: 3000 });
                // Redirect to the landing page
                this.$router.push('/');
            } catch (e) {
                this.handleError(e); // Call the defined handleError function here
            }
        },
        async LogoutApiCall() {
            try {
                // Include the token in the request headers
                const response = await this.$axios.post('/api/logout');
                return response.data;
            } catch (e) {
                throw e;
            }
        },
        handleError(error) {
            console.error('An error occurred:', error);
            // You can handle the error here, show a message, or take other actions
        }
    }
};
</script>

<style scoped>
/* You can add any custom styles here */
.logout-link {
    cursor: pointer;
}
</style>
