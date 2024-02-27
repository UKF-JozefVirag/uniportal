<template>
  <v-row justify="center">
    <v-dialog
        v-model="dialog"
        persistent
        width="256"
    >
      <v-card>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text class="h6"><b>Si si istý ?</b></v-text>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="green"
              variant="text"
              @click="acceptSync"
          >
            <b>Potvrdiť</b>
          </v-btn>
          <v-btn
              color="green"
              variant="text"
              @click="dialog = false"
          >
            <b>Zavrieť</b>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  name: 'SynchronizeProjectModal',
  data() {
    return {
      dialog: false
    }
  },
  methods:{
    acceptSync() {
      this.$emit('acceptSync');
    },
    openDialog() {
      this.dialog = !this.dialog;
    }
  }
}
</script>