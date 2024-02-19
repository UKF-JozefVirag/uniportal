<template>

  <v-container class="datatable" style="margin-top: 100px">
    <v-row>
      <v-col
          lg="4"
          md="4"
          sm="6"
          offset-lg="2"
          offset-md="2"
          offset-sm="2">
        <v-text-field
            v-model="search"
            label="Search"
            prepend-inner-icon="mdi-magnify"
            single-line
            variant="outlined"
            hide-details
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col
          lg="10"
          md="10"
          sm="10"
          offset-lg="2"
          offset-md="2"
          offset-sm="2">
        <v-data-table
            :items="apiData"
            :search="search"
            items-per-page="3"
            :loading="loading"
            v-model="selected"
            :items-per-page-options="[
                {value: 3, title: '3'},
                {value: 5, title: '5'},
                {value: 10, title: '10'},
                {value: 25, title: '25'},
                {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'},
            ]"
            show-select
            select-strategy="single"
            return-object
        ></v-data-table>
        <pre>
          {{selected}}
        </pre>
      </v-col>
    </v-row>
  </v-container>





  <v-col
      offset-lg="9"
      offset-md="5"
      offset-sm="5">
    <v-btn @click="getSelectedRows"
    >
      Button
    </v-btn>
  </v-col>
</template>


<script>
import axios from "axios";

export default {
  name: "PublikacieView",
  components: {
  },
  data() {
    return {
      apiData: [],
      search: '',
      loading: false,
      selected: []
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get("http://localhost:8000/projects");
        this.apiData = response.data;
        console.log(response.data)
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    
    async getSelectedRows() {
      console.log();
    }
  }
};
</script>