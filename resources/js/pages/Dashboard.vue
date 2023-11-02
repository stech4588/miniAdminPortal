<template>
    <div class="dashboard text-center align-items-center" >
      <h1>Dashboard</h1>
    </div>
</template>

<script>

export default {
    inject: ['authStore'],
    name: "dashboard",
    data () {
        return {
            adminUser: null
        }
    },

}
</script>

<style scoped>

</style>
