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
        <v-form @submit.prevent validate-on="submit lazy" style="margin-top: 200px; text-align: center">
          <InputFile label="Import dotácií projektov VEGA"></InputFile>
          <InputFile label="Import dotácií projektov KEGA"></InputFile>
          <InputFile label="Import dotácií projektov APVV"></InputFile>
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
export default {
  name: "ImportView",
  components: {
    InputFile,
  }

}
</script>

<style scoped>

</style>