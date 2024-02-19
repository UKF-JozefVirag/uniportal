<template>

  <v-container class="datatable">

    <v-row>

      <v-col
          lg="4"
          md="4"
          sm="6"
          offset-lg="2"
          offset-md="2"
          offset-sm="2">

        <v-text-field
            v-model="search"
            label="Search"
            prepend-inner-icon="mdi-magnify"
            single-line
            variant="outlined"
            hide-details
        ></v-text-field>

      </v-col>

    </v-row>

    <v-row>

      <v-col
          lg="10"
          md="10"
          sm="10"
          offset-lg="2"
          offset-md="2"
          offset-sm="2">

        <v-data-table
            :items="records"
            :search="search"
            items-per-page="3"
            :loading="loading"
            v-model="selected"
            :items-per-page-options="[
                {value: 3, title: '3'},
                {value: 5, title: '5'},
                {value: 10, title: '10'},
                {value: 25, title: '25'},
                {value: -1, title: '$vuetify.dataFooter.itemsPerPageAll'},
            ]"
            :show-select="showSelect"
            select-strategy="single"
            return-object
        ></v-data-table>

        <pre>
          {{selected}}
        </pre>
      </v-col>

    </v-row>

  </v-container>

</template>

<script>


export default {
  components: {
  },
  data() {
    return {
      search: '',
      loading: false,
      selected: []
    }
  },
  props: {
    records: {
      type: Array,
      required: true
    },
    showSelect: {
      type: Boolean,
      required: true
    },

  }


}
</script>

<style>

</style>