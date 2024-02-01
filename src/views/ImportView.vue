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
        <v-form @submit.prevent style="margin-top: 200px">
          <InputFile label="Import dotácií projektov"></InputFile>
          <v-btn
              class="me-5"
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