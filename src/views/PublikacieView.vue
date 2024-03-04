<template>
  <DataTable :records="apiData" style="margin-top: 100px;"></DataTable>
</template>

<script>
import DataTable from "@/components/DataTable.vue";
import axios from "axios";

export default {
  name: "PublikacieView",
  components: {
    DataTable
  },
  data() {
    return {
      apiData: []
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get("http://localhost:8000/publications");
        this.apiData = response.data;
        console.log(response.data)
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }
  }
};
</script>

<style scoped>
</style>