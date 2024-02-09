<template>
  <v-container>
    <v-row style="margin-top: 100px; text-align: center">
      <v-col
          v-for="year in years"
          :key="year"
          offset-lg="1"
          offset-md="1"
          offset-sm="1"
          lg="3"
          md="5"
          sm="5"
          xs="1">
        <ImportYearCard :cardText="year" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import ImportYearCard from "@/components/ImportYearCard";
export default {
  name: "ImportViewYear",
  components: {
    ImportYearCard
  },
  data() {
    return {
      years: [2020, 2021, 2022, 2023],
    }
  }

}
</script>

<style scoped>

</style>