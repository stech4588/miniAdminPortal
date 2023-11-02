<template>
    <div v-if="loading === false">
        <div >
            <h1 class="pt-5 mb-3">
                Edit Company
            </h1>
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <form @submit.prevent="updateCompanies" @keydown="form.onKeydown($event)">
                        <!-- Name -->
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.name" class="form-control" type="text" name="name" required>
                                <has-error :form="form" field="name" />
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
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Website') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.website" class="form-control" type="text" name="website" >
                                <has-error :form="form" field="website" />
                            </div>
                        </div>

                        <!-- Show image and Companies fields only when form.type is "child" -->

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Logo') }}</label>
                            <div class="col-md-7">
                                <input @change="handleImageChange" class="form-control" type="file" name="logo" accept="image/*" >
                                <has-error :form="form" field="logo" />
                                <img :src="logo" alt="Companies Image" v-if="logo" :width="150" :height="100" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <VButton :loading="form.busy" class="btn text-dark search-grey-bg">
                                    {{ ('Update') }}
                                </VButton>
                                <router-link :to="{name:'companies' }" class="btn grey-bg ms-3">
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
    name:'updateCompanies',
    data() {
        return {
            form: new this.$form({
                name: '',
                email: null,
                logo: '',
                website: null,
            }),
            logo: '',
            loading: true
        }
    },
    async mounted() {
        this.$useHead({
            title: 'Companies',
            description: 'Companies'
        });
        await this.fetchCompaniesData();
    },


    methods: {
        async fetchCompaniesData() {
            try {
                const response = await this.$axios.get(`/api/companies/${this.$route.params.id}`);
                this.form.name = response.data.data.name;
                this.form.email = response.data.data.email;
                // this.form.image = response.data.data.image_url;
                this.logo = response.data.data.logo;
                this.form.website = response.data.data.website;
                this.loading = false;
            } catch (e) {
                handleError(e,this.$toast);
                this.loading = false;

            }
        },


        async updateCompanies() {
            try {
                if (this.form.type === 'parent') {
                    // Set image and parent_Companies_id to null if parent type is selected
                    // this.form.image = null;
                    this.form.parent_Companies_id = null;
                }

                await this.form.post(`/api/companies/${this.$route.params.id}`)
                    .then(response => {
                        if (response.data.status === 200) {
                            this.$toast.success(response.data.message, { position: 'top-right', duration: 3000 });
                            // Redirect to the landing page
                            this.$router.push('/companies');
                        }
                    });
            } catch (e) {
                handleError(e,this.$toast);
            }
        },
        handleImageChange(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                // Create an Image object to check the dimensions
                const image = new Image();
                image.src = URL.createObjectURL(file);

                image.onload = () => {
                    const minWidth = 100; // Minimum width in pixels
                    const minHeight = 100; // Minimum height in pixels

                    if (image.width >= minWidth && image.height >= minHeight) {
                        // Image meets the minimum size requirement
                        this.form.logo = file; // Store the selected file
                    } else {
                        event.target.value = ''; // Clear the input
                        this.form.logo = null; // Reset the stored file
                        this.$toast.error(
                            `The image dimensions must be at least ${minWidth}x${minHeight} pixels.`,
                            { position: 'top-right', duration: 3000 }
                        );
                    }
                };
            } else {
                event.target.value = ''; // Clear the input
                this.form.logo = null; // Reset the stored file
                this.$toast.error('Please select a valid image file.', {
                    position: 'top-right',
                    duration: 3000
                });
            }
        }

    }
}
</script>

<style scoped> </style>
