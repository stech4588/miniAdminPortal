<template>
    <div v-if="loading === false">
        <div >
            <h1 class="pt-5 mb-3">
                Employee Detail {{ $route.params.id }}
            </h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-4 boldText">ID</th>
                    <th class="col-4">{{ Employees.id }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="boldText">First Name</td>
                    <td>{{ Employees.first_name }}</td>
                </tr>
                <tr>
                    <td class="boldText">Last Name</td>
                    <td>{{ Employees.last_name }}</td>
                </tr>
                <tr>
                    <td class="boldText">Email</td>
                    <td>{{ Employees.email }}</td>
                </tr>
                <tr>
                    <td class="boldText">Phone</td>
                    <td>{{ Employees.phone }}</td>
                </tr>
                <tr>
                    <td class="boldText">Company Name</td>
                    <td v-if="Employees.company">{{ Employees.company.name }}</td>
                </tr>
                <tr>
                    <td class="boldText">Company Email</td>
                    <td v-if="Employees.company">{{ Employees.company.email }}</td>
                </tr>

                </tbody>
            </table>
            <div class="text-center mt-2">
                <router-link :to="`/updateemployees/${Employees.id}`" class="btn grey-bg mx-2">Edit</router-link>
                <router-link :to="`/employees`" class="btn grey-bg mx-2">back</router-link>
            </div>
        </div>

    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    name:"viewEmployees",
    data() {
        return {
            Employees: [],
            loading: true
        }
    },

    async mounted() {
        try {
            const response = await this.$axios.get(`/api/employees/${this.$route.params.id}`);
            this.Employees = response.data.data;
            this.loading = false;
        } catch (e) {
            handleError(e,this.$toast);
            this.loading = false;
        }
    },

}
</script>
