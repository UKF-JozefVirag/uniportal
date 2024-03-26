<template>
  <v-app-bar color="green-darken-1" class="flex-grow-0" app dark>
    <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
    <v-app-bar-title>UKF portál</v-app-bar-title>
  </v-app-bar>
  <v-navigation-drawer app v-model="drawer" class="sidebar" position="fixed" top="0" bottom="0" left="0">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="text-h6 text-center my-3">Prihlásený</v-list-item-title>
        <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
            title="Ferko Mrkvička"
            subtitle="fmrkvicka@ukf.sk"
        ></v-list-item>
      </v-list-item-content>
    </v-list-item>
    <v-divider></v-divider>
    <v-list class="sidelist">
      <router-link v-for="item in items" :key="item.title" :to="`/${item.link}`" style="text-decoration: none; color: inherit;">
        <v-list-item link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
          {{ item.title }}
        </v-list-item>
      </router-link>
    </v-list>
  </v-navigation-drawer>
  <slot>

  </slot>
</template>

<script>
export default {
  name: 'SideBar',
  data: () => ({
    drawer: true, //prepisat na false, teraz to iba debugujeme s true
    items: [
      { title: 'Projekty', icon: 'mdi-folder', link: '' },
      { title: 'Synchronizácia projektov', icon: 'mdi-sync', link: 'projektySynchronizacia' },
      { title: 'Publikácie', icon: 'mdi-file-document-multiple-outline', link: 'publications' },
      { title: 'Štatistiky', icon: 'mdi-chart-bar', link: 'statistics' },
      { title: 'Import', icon: 'mdi-import', link: 'import' },
      { title: 'Logout', icon: 'mdi-logout', link: 'logout' },
    ],
  }),
};
</script>

<style>
</style>
