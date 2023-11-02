<template>
    <div class="p-1" v-if="loading === false">
        <h1>Employees</h1>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form @submit.prevent="Searchemployees" @keydown="form.onKeydown($event)">
                        <div class="input-group mb-3">
                            <input v-model="form.searchInput" :class="{ 'is-invalid': form.errors.has('searchInput') }"
                                   placeholder="Search Records By User Name" class="form-control" type="text" name="searchInput"
                            >
                            <has-error :form="form" field="searchInput" />
                            <div class="input-group-append">
                                <button class="btn grey-bg ms-2" :disabled="form.busy">
                                    {{ 'Search' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-end my-3">
            Add New &nbsp;
            <router-link  :to="{ name: 'addEmployees' }" class="btn btn-outline-primary linked-icon">
                <font-awesome-icon icon="plus" fixed-width />
            </router-link>
        </div>

        <div v-if="currentPageData.length > 0">
            <table class="table table-bordered ">
                <thead class="text-white grey-bg">
                <tr>
                    <th class="">
                        ID
                    </th>
                    <th class="col-4">
                        Name
                    </th>
                    <th class="col-2">
                        Email
                    </th>
                    <th class="col-8 text-center">
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in currentPageData" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.first_name + ' ' +  item.last_name }}</td>
                    <td>{{ item.email }}</td>
                    <td class="text-center">
                        <router-link :to="{ path: `/viewEmployees/${item.id}` }" class="btn linked-icon">
                            <font-awesome-icon icon="eye" fixed-width />
                        </router-link>
                        <router-link  :to="{ path: `/updateEmployees/${item.id}` }" class="btn linked-icon">
                            <font-awesome-icon icon="pen" fixed-width />
                        </router-link>
                        <button  class="btn linked-icon" @click="deleteemployees(item.id)">
                            <font-awesome-icon icon="trash" fixed-width />
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <div>
                <pagination
                    :currentPage="currentPage"
                    :totalPages="totalPages"
                    :visiblePaginationLinks="visiblePaginationLinks"
                    :changePage="changePage"
                ></pagination>

            </div>
        </div>
        <div v-else class="d-flex justify-content-center align-items-center">
            <h3>NO RECORD FOUND</h3>
        </div>

    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    name: 'employees',
    scrollToTop: false,

    data () {
        return {
            form: new this.$form({
                searchInput: ''
            }),
            currentPage: 1,
            totalPages: 1,
            currentPageData: [],
            loading: true,
            view: 5
        }
    },

    computed: {
        visiblePaginationLinks () {
            // Limit the number of visible pagination links to a reasonable number (e.g., 5)
            const maxVisibleLinks = 5
            const halfVisibleLinks = Math.floor(maxVisibleLinks / 2)
            let startPage = Math.max(1, this.currentPage - halfVisibleLinks)
            let endPage = Math.min(this.totalPages, startPage + maxVisibleLinks - 1)

            if (this.totalPages >= maxVisibleLinks && endPage === this.totalPages) {
                startPage = Math.max(1, endPage - maxVisibleLinks + 1)
            }

            const links = []
            for (let i = startPage; i <= endPage; i++) {
                links.push(i)
            }
            return links
        },

    },

    async mounted() {
        this.$useHead({
            title: 'comapanies',
            description: 'comapanies'
        });

        await this.fetchemployeesList();
    },

    methods: {
        async changePage (page) {
            const response = await this.$axios.get(`api/employees?page=${page}&view=${this.view}&search=${this.form.searchInput}`)
            this.currentPage = response.data.data.current_page
            this.currentPageData = response.data.data.data
        },

        async fetchemployeesList() {
            try {
                await this.$axios
                    .get(`/api/employees?view=${this.view}`)
                    .then(response => {
                        this.currentPageData = response.data.data.data
                        this.totalPages = response.data.data.last_page
                        this.loading = false;
                    })
            } catch (e) {
                this.loading = false;
                handleError(e,this.$toast);
            }
        },

        async deleteemployees(id) {
            const permission = await this.showConfirmationDialog('Are you sure you want to delete this Comapany?')
            if (permission) {
                try {
                    await this.$axios
                        .delete(`/api/employees/${id}`)
                        .then(response => {
                            if (response.data.status === 200) {
                                this.$toast.success(response.data.message, { position: 'top-right', duration: 3000 });
                                // Fetch and update the product Unit list after successful delete
                                this.fetchemployeesList();
                            }
                        })
                } catch (e) {
                    handleError(e,this.$toast);
                }
            }
        },

        async Searchemployees () {
            await this.$axios
                .get('/api/employees', {
                    params: {
                        search: this.form.searchInput,
                        view: this.view
                    }
                })
                .then(response => {
                    this.currentPageData = response.data.data.data
                    this.totalPages = response.data.data.last_page
                })
                .catch(e => {
                    handleError(e,this.$toast);
                })
        },

    }
}
</script>

<style scoped></style>
