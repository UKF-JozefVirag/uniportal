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
        <div class="actionButtons">
          <v-btn @click="fetchProjectsVegaData">
            Zobraziť VEGA Projekty
          </v-btn>
          <v-btn class="mx-2" @click="fetchProjectsKegaData">
            Zobraziť KEGA Projekty
          </v-btn>
          <v-btn @click="fetchProjectsApvvData">
            Zobraziť APVV Projekty
          </v-btn>
          <v-btn @click="autoSyncProjects">
            Automatická synchronizácia
          </v-btn>
        </div>
        <v-data-table
            :items="filteredData"
            :headers="projectHeaders"
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
        >
          <template v-slot:[`item.actions`]="{ item }">
            <v-icon small class="mr-2" @click="showProjectRow(item), $refs.projectdatamodal.openDialog()">mdi-eye</v-icon>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>

  <v-container class="datatablePrograms">
    <v-row>
      <v-col
          lg="4"
          md="4"
          sm="6"
          offset-lg="2"
          offset-md="2"
          offset-sm="2">
        <v-text-field
            v-model="searchProgram"
            label="Search project programs"
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
            class="program-datatable"
            :items="projectsProgram"
            :headers="filteredHeaders"
            :search="searchProgram"
            :loading="loading"
            items-per-page="3"
            v-model="selectedProgram"
            density="compact"
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
        >
          <template v-slot:[`item.actions`]="{ item }">
            <v-icon small class="mr-2" @click="showRow(item), $refs.datamodal.openDialog()">mdi-eye</v-icon>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>


  <v-col
      style="margin-top: 100px"
      offset="6">
    <v-btn size="x-large" @click="synchronizeProjects"
    >
      Submit
    </v-btn>
  </v-col>

  <DataModal :projectInfo="firstProjectModalText" ref="projectdatamodal" />
  <DataModal :projectInfo="projectModalText" ref="datamodal" />
  <SynchronizeProjectModal @acceptSync="as = true" ref="syncModal" />

</template>


<script>
import axios from "axios";
import {useToast} from "vue-toastification";
import DataModal from "@/components/DataModal";
import SynchronizeProjectModal from "@/components/SynchronizeProjectModal";

export default {
  name: "ProjectManualSynchronizationView",
  components: {
    DataModal,
    SynchronizeProjectModal
  },
  emits: ['syncAccept'],
  data() {
    return {
      filteredData: [],
      unfilteredData: [],
      projectHeaders: [
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
      ],
      projectsProgram: [],
      search: '',
      searchProgram: '',
      loading: false,
      selected: [],
      selectedProgram: [],
      headers: [],
      filteredHeaders: [],
      projectModalText: [],
      firstProjectModalText: [],
      as: false
    };
  },
  methods: {

    showProjectRow(value) {
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
      this.firstProjectModalText = newObject;
    },

    showRow(value) {
      const newObject = Object.keys(value).reduce((acc, key) => {
        // Použitie názvu stĺpca z "headers" pre porovnanie
        const headerTitle = this.headers.find(header => header.value === key)?.title;
        if (headerTitle) {
          acc[headerTitle] = value[key];
        }
        return acc;
      }, {});

      this.projectModalText = newObject;
    },

    async fetchProjectsData() {
      try {
        const response = await axios.get("http://localhost:8000/projectsNonSynchronized/");
        this.filteredData = response.data;
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },
    async fetchProjectsVegaData() {
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
              "Rok": item['Rok'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(filteredData)
        this.loading = false;


        const vega = await axios.get("http://localhost:8000/vega");
        this.projectsProgram = vega.data;
        this.setProgramDataHeader("vega");
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },
    async fetchProjectsKegaData() {
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
              "Rok": item['Rok'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(filteredData)
        this.loading = false;


        const kega = await axios.get("http://localhost:8000/kega");
        this.projectsProgram = kega.data;
        this.setProgramDataHeader("kega");
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },
    async fetchProjectsApvvData() {
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
              "Rok": item['Rok'],
            });
          }
          return acc;
        }, []);
        this.filteredData = filteredData;
        this.unfilteredData = response.data;
        console.log(filteredData)
        this.loading = false;


        const apvv = await axios.get("http://localhost:8000/apvv");
        this.projectsProgram = apvv.data;
        this.setProgramDataHeader("apvv");
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },

    async fetchProjectsProgramData() {
      try {
        const vega = await axios.get("http://localhost:8000/vega/");
        const kega = await axios.get("http://localhost:8000/kega/");
        const apvv = await axios.get("http://localhost:8000/apvv/");
        this.projectsProgram = vega.data.concat(kega.data);
        this.projectsProgram = this.projectsProgram.concat(apvv.data);
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },

    async synchronizeProjects() {
      try {
        const project = this.selected;
        const programProject = this.selectedProgram;

        if (project.length === 0 || programProject.length === 0) {
          const toast = useToast();
          toast.error("Zabudol si zakliknúť jeden alebo oba checkboxy", {
            timeout: 8000
          });
          return;
        } else {
          await axios.post('http://localhost:8000/import/synchronize/manual', {
            project: project,
            projectProgram: programProject
          });
        }
      } catch (error) {
        const toast = useToast();
        toast.error(error, {
          timeout: 8000
        });
      }
    },

    async sendSyncProjects() {
      const project = this.selected;
      const programProject = this.selectedProgram;
      await axios.post('http://localhost:8000/import/synchronize/manual', {
        project: project,
        projectProgram: programProject
      });
    },

    async autoSyncProjects() {
      await axios.get('http://localhost:8000/import/synchronize/')
    },

    setProgramDataHeader(program) {
      switch (program) {
        case "vega":
          this.headers = [
            {
              title: "ID VEGA",
              value: "idVEGA",
              sortable: true
            },
            {
              title: "Rok začatia riešenia projektu",
              value: "rok_zaciatku_riesenia_projektu",
              sortable: true
            },
            {
              title: "Rok skončenia riešenia projektu",
              value: "rok_skoncenia_riesenia_projektu",
              sortable: true
            },
            {
              title: "Číslo komisie VEGA",
              value: "cislo_komisie_vega",
              sortable: true
            },
            {
              title: "Evidenčné číslo",
              value: "evidencne_cislo",
              sortable: true
            },
            {
              title: "Názov projektu",
              value: "nazov_projektu",
              sortable: true
            },
            {
              title: "Vedúci projektu (zástupca)",
              value: "veduci_projektu_zastupca",
              sortable: true
            },
            {
              title: "Skratka",
              value: "skratka",
              sortable: true
            },
            {
              title: "Bodové hodnotenie",
              value: "bodove_hodnotenie",
              sortable: true
            },
            {
              title: "Poradie",
              value: "poradie",
              sortable: true
            },
            {
              title: "Požadovaná dotácia (€)",
              value: "pozadovana_dotacia",
              sortable: true
            },
            {
              title: "Pridelená dotácia BV (€)",
              value: "pridelena_dotacia_bv",
              sortable: true
            },
            {text: "Actions", value: "actions", sortable: false},
          ];
          this.filteredHeaders = this.headers;
          break;
        case "kega":
          this.headers = [
            {
              title: "ID KEGA",
              value: "idKEGA",
              sortable: true
            },
            {
              title: "Rok začatia riešenia projektu",
              value: "rok_zaciatku_riesenia_projektu",
              sortable: true
            },
            {
              title: "Rok skončenia riešenia projektu",
              value: "rok_skoncenia_riesenia_projektu",
              sortable: true
            },
            {
              title: "Číslo komisie KEGA",
              value: "cislo_komisie_kega",
              sortable: true
            },
            {
              title: "Evidenčné číslo",
              value: "evidencne_cislo",
              sortable: true
            },
            {
              title: "Názov projektu",
              value: "nazov_projektu",
              sortable: true
            },
            {
              title: "Vedúci projektu (zástupca)",
              value: "veduci_projektu_zastupca",
              sortable: true
            },
            {
              title: "Pracovisko",
              value: "pracovisko",
              sortable: true
            },
            {
              title: "Bodové hodnotenie",
              value: "bodove_hodnotenie",
              sortable: true
            },
            {
              title: "Poradie",
              value: "poradie",
              sortable: true
            },
            {
              title: "Podiel školy (%)",
              value: "podiel_skoly_per",
              sortable: true
            },
            {
              title: "Požadovaná dotácia (€)",
              value: "pozadovana_dotacia",
              sortable: true
            },
            {
              title: "Pridelená dotácia BV (€)",
              value: "pridelena_dotacia_bv",
              sortable: true
            },
            {
              text: "Actions", value: "actions", sortable: false
            },
          ];
          this.filteredHeaders = this.headers;
          break;
        case "apvv":
          this.headers = [
            {
              title: "ID APVV",
              value: "idAPVV",
              sortable: true
            },
            {
              title: "Kategória",
              value: "kategoria",
              sortable: true
            },
            {
              title: "Rok dotácie",
              value: "rok_dotacie",
              sortable: true
            },
            {
              title: "Fakulta",
              value: "fakulta",
              sortable: true
            },
            {
              title: "Názov",
              value: "nazov",
              sortable: true
            },
            {
              title: "Meno",
              value: "meno",
              sortable: true
            },
            {
              title: "ID projektu",
              value: "id_projektu",
              sortable: true
            },
            {
              title: "Skupina odborov",
              value: "skupina_odborov",
              sortable: true
            },
            {
              title: "Podskupina odborov",
              value: "podskupina_odborov",
              sortable: true
            },
            {
              title: "Odbor vedy a techniky",
              value: "odbor_vedy_a_techniky",
              sortable: true
            },
            {
              title: "Oblasť výskumu",
              value: "oblast_vyskumu",
              sortable: true
            },
            {
              title: "Podnet na podávanie návrhov",
              value: "podnet_na_podavanie_navrhov",
              sortable: true
            },
            {
              title: "Názov programu podpory",
              value: "nazov_programu_podpory",
              sortable: true
            },
            {
              title: "Názov inštitúcie podpory",
              value: "nazov_institucie_podpory",
              sortable: true
            },
            {
              title: "IČO",
              value: "ico",
              sortable: true
            },
            {
              title: "Dátum podpisu zmluvy o podpore",
              value: "datum_podpisu_zmluvy_o_podpore",
              sortable: true
            },
            {
              title: "Rok začatia riešenia projektu",
              value: "rok_zaciatku_riesenia_projektu",
              sortable: true
            },
            {
              title: "Rok skončenia riešenia projektu",
              value: "rok_skoncenia_riesenia_projektu",
              sortable: true
            },
            {
              title: "Pridelená dotácia BV (€)",
              value: "pridelena_dotacia_bv",
              sortable: true
            },
            {
              title: "Doplňujúce informácie",
              value: "doplnujuce_info",
              sortable: true
            },

            {
              title: "Anotácia",
              value: "anotacia",
              sortable: true
            },
            {
              title: "Zdôvodnenie charakteru práce",
              value: "zdovodnenie_charakteru_prace",
              sortable: true
            },
            {
              title: "Áno/Nie",
              value: "an",
              sortable: true
            },
            {
              title: "Komentár",
              value: "komentar",
              sortable: true
            },
            {text: "Actions", value: "actions", sortable: false},
          ];
          // tento riadok zabezpečuje aby sa nam nezobrazovali tieto stlpce, pretože sú moc dlhé a rozťahuje to riadky tabulky až moc..
          // kvoli tomu sme do tabulky pridali akciu "oko" ktoré nám zobrazí modal s uplne všetkymi udajmi o projekte a sprehladní nám tak výpis údajov
          this.filteredHeaders = this.headers.filter(header => header.title !== 'Zdôvodnenie charakteru práce' && header.title !== 'Anotácia');
          break;
        default:
          this.headers = [];
      }
    }
  },




};
</script>

<style scoped>
.v-btn {
  background: #43a047;
  color: white;
}

.actionButtons > *{
  margin:10px;
}

</style>