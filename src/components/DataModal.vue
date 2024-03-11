<template>
  <v-row justify="center">
    <v-dialog
        v-model="dialog"
        persistent
        width="512"
    >
      <v-card>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text class="h4"><b>Všetky informácie: </b></v-text>
              </v-col>
              <v-col cols="12">
                <v-list v-for="(value, key) in projectInfo" :key="key">
                  <b>{{key}}</b> : <v-list-item> {{value}} </v-list-item>
                </v-list>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
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
  data() {
    return {
      dialog: false
    };
  },
  methods: {
    openDialog() {
      this.dialog = !this.dialog;
    }
  },
  props:{
    projectInfo: Object
  }
}
</script>

<style>

</style>