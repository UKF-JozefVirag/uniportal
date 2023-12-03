<template>
  <v-app>
    <SideBar v-if="!isNotLoginOrRegister" />
    <router-view></router-view>
  </v-app>
</template>
<script>
import SideBar from './components/SideBar.vue';
export default {
  name: 'App',
  components: {
    SideBar
  },
  computed: {
    isNotLoginOrRegister() {
      return this.$route.name === 'login' || this.$route.name === 'register';
    }
  }
}
</script>

<style>
</style>
