<template>
    <div v-if="loading === false">
        <div >
            <h1 class="pt-5 mb-3">
                Edit Employees
            </h1>
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <form @submit.prevent="updateEmployees" @keydown="form.onKeydown($event)">
                        <!-- Name -->
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('First Name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.first_name" class="form-control" type="text" name="first_name" required>
                                <has-error :form="form" field="first_name" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Last Name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.last_name" class="form-control" type="text" name="last_name" >
                                <has-error :form="form" field="last_name" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" class="form-control" type="text" name="email" >
                                <has-error :form="form" field="email" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Phone') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.phone" class="form-control" type="text" name="phone" >
                                <has-error :form="form" field="phone" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Company') }}</label>
                            <div class="col-md-7">
                                <select v-model="form.company_id" class="form-control" name="company_id" required>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                                </select>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <VButton :loading="form.busy" class="btn text-dark search-grey-bg">
                                    {{ ('Update') }}
                                </VButton>
                                <router-link :to="{name:'employees' }" class="btn grey-bg ms-3">
                                    {{ ('Cancel') }}
                                </router-link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    name:'updateEmployees',
    data() {
        return {
            form: new this.$form({
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                company_id: null,
            }),
            companies: [],
            loading: true,
        }
    },
    async mounted() {
        this.$useHead({
            title: 'employees',
            description: 'employees'
        });
        await this.fetchemployeesData();
        await this.getCompanies();
    },


    methods: {

        async fetchemployeesData() {
            try {
                const response = await this.$axios.get(`/api/employees/${this.$route.params.id}`);
                this.form.first_name = response.data.data.first_name;
                this.form.last_name = response.data.data.last_name;
                this.form.email = response.data.data.email;
                this.form.phone = response.data.data.phone;
                this.form.company_id = response.data.data.company_id;
                this.loading = false;
            } catch (e) {
                handleError(e,this.$toast);
                this.loading = false;

            }
        },
        async getCompanies() {
            try {
                const response = await this.$axios.get('/api/companies')
                if (response.status === 200){
                    this.companies = response.data.data
                }
            } catch (e) {
                handleError(e, this.$toast);
            }
        },


        async updateEmployees() {
            try {
                if (this.form.type === 'parent') {
                    // Set image and parent_employees_id to null if parent type is selected
                    // this.form.image = null;
                    this.form.parent_employees_id = null;
                }

                await this.form.post(`/api/employees/${this.$route.params.id}`)
                    .then(response => {
                        if (response.data.status === 200) {
                            this.$toast.success(response.data.message, { position: 'top-right', duration: 3000 });
                            // Redirect to the landing page
                            this.$router.push('/employees');
                        }
                    });
            } catch (e) {
                handleError(e,this.$toast);
            }
        },

    }
}
</script>

<style scoped> </style>
