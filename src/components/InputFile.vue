<template>
  <v-row>
    <v-col>
      <v-file-input
          accept=".xslx"
          :label="label"
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
  }
}
</script>

<style>

</style>