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
      <v-form @submit.prevent="uploadExcel" validate-on="submit lazy" style="margin-top: 200px; text-align: center">
        <InputFile :label="`Import dotácií projektov VEGA - ${cardText}`" type="VEGA" @change="uploadFile" />
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

    uploadFile(event) {
      const file = event.target.files[0];
      console.log('Nahraný súbor:', file.name, ', typ:', file.type);

      const formData = new FormData();
      formData.append('file', file);

      // Create a Blob from the file
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        const blob = new Blob([reader.result], { type: file.type });
        this.uploadExcel(blob);
      };
    },


    uploadExcel(blob) {
      const formData = new FormData();
      formData.append('file', blob);

      axios.post('http://localhost:8000/import/ti', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
          .then(function (response) {
            console.log(response.data.success);
          })

          .catch(function (error) {
            console.log(error);
          });
    },

    submitForm() {
      // Pred odoslaním formulára skontroluje, či boli vybrané súbory pre všetky typy
      const types = ["VEGA", "KEGA", "APVV"];
      // for (const type of types) {
      //   if (!this.selectedFiles[type]) {
      //     console.error(`Súbor pre typ ${type} nebol vybraný.`);
      //     return;
      //   }
      // }
      console.log("123" + this.selectedFiles["vega"]);
      const formData = new FormData();
      formData.append('year', this.cardText);

      // Pridá vybrané súbory do formData podľa ich typu
      for (const type of types) {
        formData.append(type.toLowerCase(), this.selectedFiles[type]);
      }

      axios.post('/asd', formData)
          .then((response) => {
            console.log(response);
          })
          .catch((error) => {
            console.log(error);
          });
    },
  }
}
</script>
