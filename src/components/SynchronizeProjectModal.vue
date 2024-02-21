<template>
  <div class="text-center">
    <v-btn size="x-large"
    >
      Submit

      <v-dialog
          v-model="dialog"
          activator="parent"
          width="auto"
      >
        <v-card>
          <v-card-text>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" block @click="dialog = false">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-btn>
  </div>
</template>

<script>
export default {
  data () {
    return {
      dialog: false,
    }
  },
}
</script>

<style scoped>
.v-btn {
  background: #43a047;
  color: white;
}
</style>