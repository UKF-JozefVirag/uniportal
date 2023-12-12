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
                <v-text class="h5">Sem vložte verifikačný kód, ktorý Vám bol zaslaný do mailu. </v-text>
              </v-col>
              <v-col cols="12">
                <v-text-field
                    label="Verifikačný kód"
                    required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="blue-darken-1"
              variant="text"
              @click="dialog = false"
          >
            Zavrieť
          </v-btn>
          <v-btn
              color="blue-darken-1"
              variant="text"
              @click="dialog = false"
          >
            Odoslať
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
  }
}
</script>

<style>

</style>