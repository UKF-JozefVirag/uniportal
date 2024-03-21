<template>
  <v-row>
    <v-col
        lg="6"
        md="6"
        sm="6"
        offset-lg="4"
        offset-md="4"
        offset-sm="2"
    >
      <v-form @submit.prevent="uploadFile" validate-on="submit lazy" style="margin-top: 200px; text-align: center">
        <InputFile :label="`Import dotácií projektov VEGA - ${cardText}`" type="VEGA"/>
        <InputFile :label="`Import dotácií projektov KEGA - ${cardText}`" type="KEGA" @change="onFileChange" />
        <InputFile :label="`Import dotácií projektov APVV - ${cardText}`" type="APVV" @change="onFileChange" />
        <v-btn
            type="submit"
        >
          Submit
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import InputFile from "@/components/InputFile.vue";
import axios from "axios";
export default {
  name: "ImportView",
  data() {
    return {
      cardText: null,
      selectedFiles: {}, // Uchováva vybrané súbory pre každý typ
    }
  },
  components: {
    InputFile,
  },
  created() {
    this.cardText = this.$route.params.year
  },
  methods: {
    onFileChange(file, type) {
      // Uloží vybraný súbor podľa jeho typu
      this.selectedFiles[type] = file;
    },

    uploadFile(e) {
      const file = e.target.files[0];
      const formData = new FormData();
      formData.append('name', file.name);
      formData.append('file', file);

      axios.post('http://localhost:8000/import/projects', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        console.log(response);
      }).catch(error => {
        console.log(error);
      });
    },


  }
}
</script>
