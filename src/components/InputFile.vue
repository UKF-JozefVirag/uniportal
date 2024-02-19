<template>
  <v-row>
    <v-col>
      <v-file-input
          accept=".xslx, .xls, .csv"
          :label="label"
          @change="onFileChange"
      ></v-file-input>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: false
    }
  },
  methods: {
    onFileChange(event) {
      const file = event.target.files[0];
      if (!file) {
        return;
      }
    }
  }
}
</script>

<style>

</style>