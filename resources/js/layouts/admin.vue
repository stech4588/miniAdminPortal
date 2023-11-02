<template>
    <div class="main-layout">
        <Navbar />
        <div class="container-fluid">
            <div class="row flex-nowrap">
                <div class="col-auto px-0 ">
                    <div id="sidebar"  class="border-end">
                        <div id="sidebar-nav"   class="list-group border-0 rounded-0 text-sm-start min-vh-100">
                            <router-link :to="{ name: 'dashboard' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                                <h5 class="sidebar-title" :class="{ 'active': $route.name === 'dashboard' }">
                                    <font-awesome-icon icon="home" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Home</span>
                                </h5>
                            </router-link>
                            <router-link  :to="{ name: 'companies' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                                <h5 class="sidebar-title" :class="{ 'active': $route.name === 'companies' }">
                                    <font-awesome-icon icon="user" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Companies</span>
                                </h5>
                            </router-link>
                            <router-link  :to="{ name: 'employees' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                                <h5 class="sidebar-title" :class="{ 'active': $route.name === 'employees' }">
                                    <font-awesome-icon icon="users" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Employees</span>
                                </h5>
                            </router-link>

                        </div>
                    </div>
                </div>
                <main class="col ps-md-2 pt-2 content " >
                    <a href="#" @click="toggleSidebar"   class="border rounded-3 p-1 text-decoration-none"><font-awesome-icon icon="bars" fixed-width /></a>
                    <child />
                </main>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: 'MainLayout',
    data(){
        return {
            isSidebarCollapsed: false,

        }
    },
    async mounted() {

    },
    methods: {
        toggleSidebar() {
            this.isSidebarCollapsed = !this.isSidebarCollapsed;
        },
    },

}
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
