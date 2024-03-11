<template>
  <div v-if="loading">
    <LoadingSpinner style="margin-top: 100px; margin-left: 200px" />
  </div>
  <div v-else>
    <v-container class="datatablePublications" style="margin-top: 100px">
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
              label="Search publications"
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
              class="publication-datatable"
              :items="filteredData"
              :search="search"
              :headers="headers"
              items-per-page="3"
              density="compact"
              :items-per-page-options="[
                {value: 3, title: '3'},
                {value: 5, title: '5'},
                {value: 10, title: '10'},
                {value: 25, title: '25'},
                {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'},
            ]"
              select-strategy="single"

              return-object
          >
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="showRow(item), $refs.datamodal.openDialog()">mdi-eye</v-icon>
            </template>

          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
    <DataModal :projectInfo="projectModalText" ref="datamodal" />
  </div>
</template>

<script>
import axios from "axios";
import DataModal from "@/components/DataModal.vue";
import LoadingSpinner from "@/components/LoadingSpinner.vue";

export default {
  name: "ProjektyView",
  components: {
    LoadingSpinner,
    DataModal,
  },
  data() {
    return {
      unfilteredData: [],
      filteredData: [],
      projectModalText: [],
      search: '',
      loading: false,
      headers: [
        {
          title: "ID Projektu",
          value: "ID Projektu",
          sortable: true
        },
        {
          title: "Názov",
          value: "Názov",
          sortable: true
        },
        {
          title: "Typ",
          value: "Typ",
          sortable: true
        }
        ,
        {
          title: "Actions",
          value: "actions",
          sortable: false
        },
      ]
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("http://localhost:8000/projects");
        const uniqueIds = new Set(); // Vytvoriť množinu pre ukladanie unikátnych ID
        const filteredData = response.data.reduce((acc, item) => {
          if (!uniqueIds.has(item['ID Projektu'])) { // Ak ID ešte nie je v množine, pridaj ho do výsledku a do množiny
            uniqueIds.add(item['ID Projektu']);
            acc.push({
              "ID Projektu": item['ID Projektu'],
              "Názov": item['Názov'],
              "Typ": item['Typ'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(this.unfilteredData)
        this.loading = false;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    showRow(value) {
      const publicationId = value["ID Projektu"];
      const matchingPublications = this.unfilteredData.filter(item => item["ID Projektu"] === publicationId);

      // Odstrániť autora s 0% podielom
      const nonZeroAuthors = matchingPublications.filter(item => item.Podiel !== 0);

      // Získať unikátnych autorov pre každý rok
      const uniqueAuthorsByYear = {};
      nonZeroAuthors.forEach(publication => {
        const { Meno, Podiel, Rok } = publication;
        if (!uniqueAuthorsByYear[Rok]) {
          uniqueAuthorsByYear[Rok] = [];
        }
        uniqueAuthorsByYear[Rok].push(`${Meno}: ${Podiel}%`);
      });

      // Vytvoriť formátovaný zoznam autorov
      let authorsFormatted = [];
      for (const year in uniqueAuthorsByYear) {
        const yearAuthors = uniqueAuthorsByYear[year];
        const yearString = `${year} \n- ${yearAuthors.join("\n")}`;
        authorsFormatted.push(yearString);
      }

      const newObject = {
        "ID Projektu": value["ID Projektu"],
        "Názov": value["Názov"],
        "Typ": value["Typ"],
        "Autori": authorsFormatted.join("\n")
      };
      console.log(authorsFormatted);
      this.projectModalText = newObject;
    }



    ,
  }
};
</script>

<style scoped>
</style>