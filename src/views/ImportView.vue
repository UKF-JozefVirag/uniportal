<template>
  <v-row style="margin-top: 100px" >
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-file-input id="pFile" ref="pFile" label="Sem vložte súbor s projektami" />
    </v-col>
    <v-col>
      <v-btn type="submit" height="55" @click="projectsUploadFile">
        Submit
      </v-btn>
    </v-col>
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-file-input id="vFile" ref="vFile" label="Sem vložte VEGA súbor" />
    </v-col>
    <v-col>
      <v-btn type="submit" height="55"  @click="vegaUploadFile">
        Submit
      </v-btn>
    </v-col>
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-file-input id="kFile" ref="kFile" label="Sem vložte KEGA súbor" />
    </v-col>
    <v-col>
      <v-btn type="submit" height="55"  @click="kegaUploadFile">
        Submit
      </v-btn>
    </v-col>
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-file-input id="aFile" ref="aFile" label="Sem vložte APVV súbor" />
    </v-col>
    <v-col>
      <v-btn type="submit" height="55" @click="apvvUploadFile">
        Submit
      </v-btn>
    </v-col>
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-file-input id="pubFile" ref="pubFile" label="Sem vložte súbor s publikáciami" />
    </v-col>
    <v-col>
      <v-btn type="submit" height="55" @click="publicationsUploadFile">
        Submit
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
import axios from "axios";
import {useToast} from "vue-toastification";
export default {
  name: "ImportView",
  data() {
    return {
      pfile:'',
      vFile:'',
      kFile:'',
      aFile:'',
      pubFile:'',
      projectFile:'',
      vegaFile: '',
      kegaFile: '',
      apvvFile: '',
      publicationsFile: '',
    }
  },
  methods: {
    projectsUploadFile() {
      const toast = useToast();
      const fileInput = this.$refs.pFile;
      if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        toast.error('Musíte vybrať súbor');
        return;
      }

      const projectFile = fileInput.files[0];
      if (!projectFile) {
        toast.error('Musíte vybrať súbor');
        return;
      }

      if (!projectFile.name.endsWith('xlsx') && !projectFile.name.endsWith('csv')) {
        toast.error("Podporované súbory sú iba xlsx alebo csv", {
          timeout: 8000,
        });
        return;
      }

      let formData = new FormData();
      formData.append('file', projectFile);
      formData.append('name', projectFile.name);

      axios.post('http://localhost:8000/import/projects', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then(function (response) {
        if (response.status === 200) {
          toast.success('Súbor bol úspešne nahratý do systému', {
            timeout: 8000,
          });
        }
      }).catch(function (error) {
        toast.error(error.message, {
          timeout: 8000
        });
      });
    },
    vegaUploadFile() {
      const toast = useToast();
      this.vegaFile = this.$refs.vFile.files[0];
      if (!this.vegaFile) {
        toast.error('Musíte vybrať súbor');
        return;
      }
      if (!this.vegaFile.name.endsWith('xlsx') && !this.vegaFile.name.endsWith('csv')) {
        toast.error("Podporované súbory sú iba xlsx alebo csv", {
          timeout: 8000,
        });
        return;
      }

      let formData = new FormData();
      formData.append('file', this.vegaFile);
      formData.append('name', this.vegaFile.name);
      axios.post('http://localhost:8000/import/vega', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then(function (response) {
        if (response.status === 200) {
          toast.success('Súbor bol úspešne nahratý do systému', {
            timeout: 8000,
          });
        }
      }).catch(function (error) {
        console.log(error);
        toast.error('Nastala chyba pri nahrávaní súboru', {
          timeout: 8000,
        });
      });
    },
    kegaUploadFile() {
      const toast = useToast();
      this.kegaFile = this.$refs.kFile.files[0];
      if (!this.kegaFile) {
        toast.error('Musíte vybrať súbor');
        return;
      }
      if (!this.kegaFile.name.endsWith('xlsx') && !this.kegaFile.name.endsWith('csv')) {
        toast.error("Podporované súbory sú iba xlsx alebo csv", {
          timeout: 8000,
        });
        return;
      }

      let formData = new FormData();
      formData.append('file', this.kegaFile);
      formData.append('name', this.kegaFile.name);
      axios.post('http://localhost:8000/import/kega', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then((response) => {
        if (response.status === 200) {
          toast.success('Súbor bol úspešne nahratý do systému', {
            timeout: 8000,
          });
        }
      }).catch((error) => {
        console.log(error);
        toast.error('Nastala chyba pri nahrávaní súboru', {
          timeout: 8000,
        });
      });

    },
    apvvUploadFile() {
      const toast = useToast();
      this.apvvFile = this.$refs.aFile.files[0];
      if (!this.apvvFile) {
        toast.error('Musíte vybrať súbor');
        return;
      }
      if (!this.apvvFile.name.endsWith('xlsx') && !this.apvvFile.name.endsWith('csv')) {
        toast.error("Podporované súbory sú iba xlsx alebo csv", {
          timeout: 8000,
        });
        return;
      }

      let formData = new FormData();
      formData.append('file', this.apvvFile);
      formData.append('name', this.apvvFile.name);
      axios.post('http://localhost:8000/import/apvv', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then(function (response) {
        if (response.status === 200) {
          toast.success('Súbor bol úspešne nahratý do systému', {
            timeout: 8000,
          });
        }
      }).catch(function (error) {
        console.log(error);
        toast.error('Nastala chyba pri nahrávaní súboru', {
          timeout: 8000,
        });
      });
    },
    publicationsUploadFile() {
      const toast = useToast();
      const fileInput = this.$refs.pFile;
      if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        toast.error('Musíte vybrať súbor');
        return;
      }

      const projectFile = fileInput.files[0];
      if (!projectFile) {
        toast.error('Musíte vybrať súbor');
        return;
      }

      if (!projectFile.name.endsWith('xlsx') && !projectFile.name.endsWith('csv')) {
        toast.error("Podporované súbory sú iba xlsx alebo csv", {
          timeout: 8000,
        });
        return;
      }

      let formData = new FormData();
      formData.append('file', projectFile);
      formData.append('name', projectFile.name);

      axios.post('http://localhost:8000/import/publications', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then(function (response) {
        if (response.status === 200) {
          toast.success('Súbor bol úspešne nahratý do systému', {
            timeout: 8000,
          });
        }
      }).catch(function (error) {
        toast.error(error.message, {
          timeout: 8000
        });
      });
    },
  },


}
</script>
