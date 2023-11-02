<template>
    <div v-if="loading === false">
        <div >
            <h1 class="pt-5 mb-3">
                Company Detail {{ $route.params.id }}
            </h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-4 boldText">ID</th>
                    <th class="col-4">{{ company.id }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="boldText">Image</td>
                    <td>
                        <img :src="company.logo ? company.logo : '/images/no_image.jpg'" alt="companies Image" :width="150" :height="100">
                    </td>
                </tr>
                <tr>
                    <td class="boldText">Name</td>
                    <td>{{ company.name }}</td>
                </tr>
                <tr>
                    <td class="boldText">Email</td>
                    <td>{{ company.email }}</td>
                </tr>
                <tr>
                    <td class="boldText">Website</td>
                    <td>{{ company.website }}</td>
                </tr>

                </tbody>
            </table>
            <div class="text-center mt-2">
                <router-link :to="`/updateCompanies/${company.id}`" class="btn grey-bg mx-2">Edit</router-link>
                <router-link :to="`/companies`" class="btn grey-bg mx-2">back</router-link>
            </div>
        </div>

    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    name:"viewCompanies",
    data() {
        return {
            company: [],
            loading: true
        }
    },

    async mounted() {
        try {
            const response = await this.$axios.get(`/api/companies/${this.$route.params.id}`);
            this.company = response.data.data;
            this.loading = false;
        } catch (e) {
            handleError(e,this.$toast);
            this.loading = false;
        }
    },

}
</script>
