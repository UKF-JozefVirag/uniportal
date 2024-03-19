<template>
  <div v-if="loading">
    <LoadingSpinner style="margin-top: 100px; margin-left: 200px" />
  </div>
  <div v-else>
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
              label="Search projects"
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
              :items="filteredData"
              :search="search"
              items-per-page="3"
              :loading="loading"
              :headers="headers"
              :items-per-page-options="[
                  {value: 3, title: '3'},
                  {value: 5, title: '5'},
                  {value: 10, title: '10'},
                  {value: 25, title: '25'},
                  {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'},
              ]"
              select-strategy="single"
          >
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="showRow(item), $refs.datamodal.openDialog()">mdi-eye</v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>

  <DataModal :projectInfo="projectModalText" ref="datamodal" />
</template>

<script>
import axios from "axios";
import DataModal from "@/components/DataModal.vue";
import LoadingSpinner from "@/components/LoadingSpinner.vue";
import {useToast} from "vue-toastification";

export default {
  name: "ProjektyView",
  components: {
    LoadingSpinner,
    DataModal
  },
  data() {
    return {
      filteredData: [],
      unfilteredData: [],
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
        },
        {
          text: "Actions", value: "actions", sortable: false
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
        const response = await axios.get("http://localhost:8000/projects/synced");
        const uniqueIds = new Set(); // Vytvoriť množinu pre ukladanie unikátnych ID
        const filteredData = response.data.reduce((acc, item) => {
          if (!uniqueIds.has(item['ID Projektu'])) { // Ak ID ešte nie je v množine, pridaj ho do výsledku a do množiny
            uniqueIds.add(item['ID Projektu']);
            acc.push({
              "ID Projektu": item['ID Projektu'],
              "Názov": item['Názov'],
              "Typ": item['Typ'],
              "Rok": item['Rok'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(filteredData)
        this.loading = false;
      } catch (error) {
        const toast = useToast();
        toast.error("Chyba pri načítaní dát: " + error, {
          timeout: 6000
        });
      }
    },
    showRow(value) {
      // ziskanie ID Publikácie z aktuálneho riadka tabuľky
      const projectId = value["ID Projektu"];
      // vyhladavanie všetkych záznamov v unfilteredData s rovnakým ID Publikácie
      const matchingProjects = this.unfilteredData.filter(item => item["ID Projektu"] === projectId);
      // vytvorenie pola reťazcov s formátovanými údajmi o autoroch
      let authorsFormatted = matchingProjects.map(project => {
        const { Meno, Rok, Podiel } = project;
        return `${Meno} - ${Rok}: ${Podiel}%`;
      });
      const newObject = {
        "ID Projektu": value["ID Projektu"],
        "Názov": value["Názov"],
        "Typ": value["Typ"],
        "Autori": authorsFormatted.join(", ") // spojenie vsetkych autorov do jedného reťazca s čiarkou ako oddelením
      };
      this.projectModalText = newObject;
    }
  }
};
</script>

<style scoped>
</style>