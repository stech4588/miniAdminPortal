<template>
    <div >
        <div >
            <h1 class="pt-5">
                Add Company
            </h1>
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <form @submit.prevent="addCompanies" @keydown="form.onKeydown($event)">
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
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Logo') }}</label>
                            <div class="col-md-7">
                                <input @change="handleImageChange" class="form-control" type="file" name="logo" accept="image/*" >
                                <has-error :form="form" field="logo" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Website') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.website" class="form-control" type="text" name="website" >
                                <has-error :form="form" field="website" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <v-button :loading="form.busy" class="btn text-dark search-grey-bg">
                                    {{ ('Save') }}
                                </v-button>

                                <router-link :to="{ name: 'companies' }" class="btn grey-bg  ms-3">
                                    {{ ('Cancel') }}
                                </router-link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

export default {
    name:'addCompanies',
    data() {
        return {
            form: new this.$form({
                name: '',
                email: '',
                logo: null,
                website: '',
            }),
            loading: true,
        }
    },
    async mounted() {
        this.$useHead({
            title: 'addCompanies',
            description: 'addCompanies'
        });


    },

    methods: {
        async addCompanies() {
            try {
                await this.form.post('/api/companies')
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
