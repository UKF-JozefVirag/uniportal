<template>
  <v-container style="margin-top: 100px">
    <v-row>

      <v-col offset="3"
             cols="7">
        <h4 class="text-center">Štatistiky fakúlt v získaných peňažných dotáciách</h4>
        <FacultyStatsBar></FacultyStatsBar>
      </v-col>

      <v-col offset="3" cols="7">
        <h4 class="text-center">Štatistiky autorov v získaných peňažných dotáciách</h4>
        <AuthorsStatsBar></AuthorsStatsBar>
      </v-col>

      <v-col offset="3" cols="7">
        <h4 class="text-center">Štatistiky kategórií T1-T5 v získaných peňažných dotáciách</h4>
        <CategoryStatsBar></CategoryStatsBar>
      </v-col>

    </v-row>
  </v-container>
</template>

<script>
import FacultyStatsBar from "@/components/FacultyStatsBar.vue";
import AuthorsStatsBar from "@/components/AuthorsStatsBar.vue";
import CategoryStatsBar from "@/components/CategoryStatsBar.vue";
export default {
  name: 'StatisticsView',
  components: {AuthorsStatsBar, FacultyStatsBar, CategoryStatsBar},

};
</script>
