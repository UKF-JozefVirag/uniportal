<template>
  <div v-if="loading">
    <LoadingSpinner style="margin-top: 100px; margin-left: 200px" />
  </div>
  <div v-else>
    <div class="year-buttons d-flex justify-content-center">
      <v-btn class="mx-3 h5" v-for="year in availableYears" :key="year" @click="updateChart(year)">{{ year }}</v-btn>
    </div>
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Title, Tooltip, Legend } from 'chart.js/auto';
import LoadingSpinner from "@/components/LoadingSpinner.vue";

ChartJS.register(ArcElement, Title, Tooltip, Legend);

export default {
  name: 'FacultyStatsBar',
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
      chartOptions: {
        plugins: {
          legend: {
            display: false
          }
        }
      }
    };
  },
  async mounted() {
    await this.updateChart(this.selectedYear);
  },
  methods: {
    async updateChart(year) {
      try {
        this.loading = true;
        const response = await fetch('http://localhost:8000/statistics/TCategory');
        const stats = await response.json();

        this.chartData.labels = Object.keys(stats[year]);
        this.chartData.datasets[0].data = Object.values(stats[year]);
        this.selectedYear = year;
        this.loading = false;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    }
  }
};
</script>

<style>
</style>
