<template>
  <div v-if="loading">
    <LoadingSpinner style="margin-top: 100px; margin-left: 200px" />
  </div>
  <div v-else>
    <div class="d-flex justify-content-center">
      <select class="form-select-lg text-center border" v-model="selectedAuthor" @change="updateChart()">
        <option disabled value="" >Vyberte autora</option>
        <option v-for="author in authors" :key="author" :value="author">{{ author }}</option>
      </select>
    </div>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import {Bar} from 'vue-chartjs';
import LoadingSpinner from "@/components/LoadingSpinner.vue";

export default {
  name: 'AuthorsStatsBar',
  components: {LoadingSpinner, Bar},
  data() {
    return {
      loading: false,
      chartData: {
        labels: [],
        datasets: [
          {
            backgroundColor: ['#43a047'],
            data: []
          }
        ]
      },
      availableYears: ['2020', '2021', '2022'],
      selectedYear: '2020',
      selectedAuthor: '',
      chartOptions: {
        plugins: {
          legend: {
            display: false
          }
        }
      },
      authors: {}
    };
  },
  async mounted() {
    await this.updateChart();
  },

  methods: {
    async updateChart() {
      try {
        this.loading = true;
        const response = await fetch('http://localhost:8000/statistics/author');
        const stats = await response.json();
        this.authors = Object.keys(stats);

        if (!this.selectedAuthor) {
          // Ak nie je vybratý žiadny autor, nastav kľúče a hodnoty na 0
          this.chartData.labels = [0];
          this.chartData.datasets[0].data = [0];
        } else {
          // Ak je vybratý autor, nastav kľúče a hodnoty na hodnoty toho autora
          const authorData = stats[this.selectedAuthor];
          const keys = Object.keys(authorData);
          const values = Object.values(authorData);
          this.chartData.labels = keys;
          this.chartData.datasets[0].data = values;
        }
        this.loading = false;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    }




  }
}
</script>
<style>
  .author-select{
    background-color: #43a047;
    color: #ffffff;
  }
</style>