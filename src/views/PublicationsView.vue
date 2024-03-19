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
              :headers="headers"
              :items="filteredData"
              :search="search"
              items-per-page="3"
              v-model="selectedPublication"
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
    <DataModal :projectInfo="publicationModalText" ref="datamodal" />
  </div>
</template>

<script>
import axios from "axios";
import LoadingSpinner from "@/components/LoadingSpinner.vue";
import DataModal from "@/components/DataModal.vue";
import {h} from "vue";

export default {
  name: "PublicationsView",
  components: {
    DataModal,
    LoadingSpinner,
  },
  data() {
    return {
      loading: false,
      filteredData: [],
      unfilteredData: [],
      search: '',
      selectedPublication: [],
      publicationModalText: [],
      headers: [
        {
          title: "ID Publikácie",
          value: "ID Publikácie",
          sortable: true
        },
        {
          title: "Názov",
          value: "Názov",
          sortable: true
        },
        {
          title: "Odkaz",
          value: "Odkaz",
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
    h,
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("http://localhost:8000/publications");
        const uniqueIds = new Set(); // Vytvoriť množinu pre ukladanie unikátnych ID
        const filteredData = response.data.reduce((acc, item) => {
          if (!uniqueIds.has(item['ID Publikácie'])) { // Ak ID ešte nie je v množine, pridaj ho do výsledku a do množiny
            uniqueIds.add(item['ID Publikácie']);
            acc.push({
              "ID Publikácie": item['ID Publikácie'],
              "Názov": item['Názov'],
              "Odkaz": item['Odkaz'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(filteredData)
        this.loading = false;
      } catch (error) {
        //pridat error toast
        console.error("Error fetching data:", error);
      }
    },
    showRow(value) {
      // Získajte ID Publikácie z aktuálneho riadka tabuľky
      const publicationId = value["ID Publikácie"];

      // Vyhľadajte všetky záznamy v unfilteredData s rovnakým ID Publikácie
      const matchingPublications = this.unfilteredData.filter(item => item["ID Publikácie"] === publicationId);
      const celyZaznam = matchingPublications.map(publication => publication["Celý záznam"])[0];
      // Vytvorte pole reťazcov s formátovanými údajmi o autoroch
      let authorsFormatted = matchingPublications.map(publication => {
        const { Meno, Podiel } = publication;
        return `${Meno}: ${Podiel}%`;
      });

      const newObject = {
        "ID Publikácie": value["ID Publikácie"],
        "Názov": value["Názov"],
        "Odkaz": value["Odkaz"],
        "Záznam": celyZaznam,
        "Autori": authorsFormatted.join(", ") // Spojte všetky autory do jedného reťazca s čiarkou ako oddelením
      };

      // Nastavte hodnotu publicationModalText na nový objekt
      this.publicationModalText = newObject;
    }

  }
};
</script>

<style scoped>
</style>