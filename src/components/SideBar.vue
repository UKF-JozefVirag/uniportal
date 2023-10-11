<template>
    <v-navigation-drawer
        expand-on-hover
        rail
      >
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
            title="John Doe"
            subtitle="jdoe22@gmail.com"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item prepend-icon="mdi-folder" title="Projekty" value="projekty"></v-list-item>
          <v-list-item prepend-icon="mdi-file-document-multiple-outline" title="Publikácie" value="publikacie"></v-list-item>
          <v-list-item prepend-icon="mdi-chart-bar" title="Štatistiky" value="statistiky"></v-list-item>
        </v-list>
      </v-navigation-drawer>
  </template>