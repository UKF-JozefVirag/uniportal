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
        <v-btn @click="fetchProjectsVegaData">
          Show Vega Projects
        </v-btn>
        <v-btn class="mx-2" @click="fetchProjectsKegaData">
          Show Kega Projects
        </v-btn>
        <v-btn @click="fetchProjectsApvvData">
          Show Apvv Projects
        </v-btn>
        <v-data-table
            :items="projects"
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
            :search="searchProgram"
            items-per-page="3"
            :loading="loading"
            v-model="selectedProgram"
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

</template>


<script>
import axios from "axios";
import { useToast } from "vue-toastification";

export default {
  name: "ProjectManualSynchronizationView",
  components: {
  },
  data() {
    return {
      projects: [],
      projectsProgram: [],
      search: '',
      searchProgram: '',
      loading: false,
      selected: [],
      selectedProgram: []
    };
  },
  methods: {
    async fetchProjectsData() {
      try {
        const response = await axios.get("http://localhost:8000/projects/");
        this.projects = response.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    async fetchProjectsVegaData() {
      try {
        const response = await axios.get("http://localhost:8000/projects/vega");
        this.projects = response.data;
        const vega = await axios.get("http://localhost:8000/vega");
        this.projectsProgram = vega.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    async fetchProjectsKegaData() {
      try {
        const response = await axios.get("http://localhost:8000/projects/kega");
        this.projects = response.data;
        const kega = await axios.get("http://localhost:8000/kega");
        this.projectsProgram = kega.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    async fetchProjectsApvvData() {
      try {
        const response = await axios.get("http://localhost:8000/projects/apvv");
        this.projects = response.data;
        const apvv = await axios.get("http://localhost:8000/apvv");
        this.projectsProgram = apvv.data;
      } catch (error) {
        console.error("Error fetching data:", error);
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
        console.error("Error fetching data:", error);
      }
    },

    async synchronizeProjects() {
      try {
        const project = this.selected;
        const programProject = this.selectedProgram;

        if (project.length == 0 || programProject.length == 0) {
          const toast = useToast();
          toast.error("One or two checkboxes are not checked", {
            timeout: 3000
          });
          return;
        }

        await axios.post('http://localhost:8000/import/synchronize/manual', {
          project: project,
          projectProgram: programProject
        });

      } catch (error) {
        console.error(error);
      }
    }


  }
};
</script>

<style scoped>
.v-btn {
  background: #43a047;
  color: white;
}
</style>