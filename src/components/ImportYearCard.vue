<template>
  <v-btn
      min-width="300"
      min-height="150"
      color="white"
      variant="elevated"
  >
    {{ cardText }}
  </v-btn>
</template>

<script>
export default {
  name: "ImportYearCard",
  props: {
    cardText:{
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
.v-btn.v-btn--elevated {
  color: #43a047 !important;
  font-size: large;
}
</style>