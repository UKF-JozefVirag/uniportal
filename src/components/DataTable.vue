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

      <v-col >

        <v-data-table
            :items="records"
            :search="search"
            items-per-page="5"
        ></v-data-table>

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
      headers:[
        "ID",
        "ID zamestnanca",
        "Fakulta",
        "Katedra",
        "Priezvisko",
        "Podieľ na sume",
        "Podieľ",
        "Rok",
        "Názov práce",
        "ID Projektu",
        "ID grant programu",
        "Akronym",
        "Úplné výdavky",
        "Kategória v rozpočte",
        "Príjem z rozpočtu veda",
        "Príjem z rozpočtu TaS"
      ]
,

      records:
        [
          {
            id: 1,
            employee_id: 1441,
            fakulta: "Fakulta medicíny",
            katedra: "FM - Katedra pediatrie",
            priezvisko: "Kováčová",
            podiel_na_sume: 712.60,
            podiel: 11,
            rok: 2023,
            title: "Inovácie v pediatrickom liečení",
            project_id: 827,
            grant_program_id: 3,
            acronym: "INOPED",
            full_expenditures: 5200,
            kategoria_v_rozpocte: "T2",
            prijem_z_rozpoctu_veda: 102.30,
            prijem_z_rozpoctu_TaS: 8.00
          },
          {
            id: 2,
            employee_id: 1442,
            fakulta: "Fakulta výtvarných umení",
            katedra: "FVU - Katedra maľby",
            priezvisko: "Šimko",
            podiel_na_sume: 632.80,
            podiel: 10,
            rok: 2022,
            title: "Moderné prístupy v maľbe a vizuálnom umení",
            project_id: 828,
            grant_program_id: 2,
            acronym: "MODVIZ",
            full_expenditures: 4600,
            kategoria_v_rozpocte: "T1",
            prijem_z_rozpoctu_veda: 95.60,
            prijem_z_rozpoctu_TaS: 7.30
          },
          {
            id: 3,
            employee_id: 1443,
            fakulta: "Fakulta teologická",
            katedra: "FT - Katedra biblistiky",
            priezvisko: "Kráľ",
            podiel_na_sume: 521.90,
            podiel: 8,
            rok: 2021,
            title: "Historický vývoj biblických interpretácií",
            project_id: 829,
            grant_program_id: 4,
            acronym: "HIVYBI",
            full_expenditures: 3900,
            kategoria_v_rozpocte: "T3",
            prijem_z_rozpoctu_veda: 80.00,
            prijem_z_rozpoctu_TaS: 6.20
          },
          {
            id: 4,
            employee_id: 1441,
            fakulta: "Fakulta medicíny",
            katedra: "FM - Katedra pediatrie",
            priezvisko: "Kováčová",
            podiel_na_sume: 712.60,
            podiel: 11,
            rok: 2023,
            title: "Inovácie v pediatrickom liečení",
            project_id: 827,
            grant_program_id: 3,
            acronym: "INOPED",
            full_expenditures: 5200,
            kategoria_v_rozpocte: "T2",
            prijem_z_rozpoctu_veda: 102.30,
            prijem_z_rozpoctu_TaS: 8.00
          },
          {
            id: 5,
            employee_id: 1442,
            fakulta: "Fakulta výtvarných umení",
            katedra: "FVU - Katedra maľby",
            priezvisko: "Šimko",
            podiel_na_sume: 632.80,
            podiel: 10,
            rok: 2022,
            title: "Moderné prístupy v maľbe a vizuálnom umení",
            project_id: 828,
            grant_program_id: 2,
            acronym: "MODVIZ",
            full_expenditures: 4600,
            kategoria_v_rozpocte: "T1",
            prijem_z_rozpoctu_veda: 95.60,
            prijem_z_rozpoctu_TaS: 7.30
          },
          {
            id: 6,
            employee_id: 1443,
            fakulta: "Fakulta teologická",
            katedra: "FT - Katedra biblistiky",
            priezvisko: "Kráľ",
            podiel_na_sume: 521.90,
            podiel: 8,
            rok: 2021,
            title: "Historický vývoj biblických interpretácií",
            project_id: 829,
            grant_program_id: 4,
            acronym: "HIVYBI",
            full_expenditures: 3900,
            kategoria_v_rozpocte: "T3",
            prijem_z_rozpoctu_veda: 80.00,
            prijem_z_rozpoctu_TaS: 6.20
          },
          {id: 7,
            employee_id: 1441,
            fakulta: "Fakulta medicíny",
            katedra: "FM - Katedra pediatrie",
            priezvisko: "Kováčová",
            podiel_na_sume: 712.60,
            podiel: 11,
            rok: 2023,
            title: "Inovácie v pediatrickom liečení",
            project_id: 827,
            grant_program_id: 3,
            acronym: "INOPED",
            full_expenditures: 5200,
            kategoria_v_rozpocte: "T2",
            prijem_z_rozpoctu_veda: 102.30,
            prijem_z_rozpoctu_TaS: 8.00
          },
          {
            id: 8,
            employee_id: 1442,
            fakulta: "Fakulta výtvarných umení",
            katedra: "FVU - Katedra maľby",
            priezvisko: "Šimko",
            podiel_na_sume: 632.80,
            podiel: 10,
            rok: 2022,
            title: "Moderné prístupy v maľbe a vizuálnom umení",
            project_id: 828,
            grant_program_id: 2,
            acronym: "MODVIZ",
            full_expenditures: 4600,
            kategoria_v_rozpocte: "T1",
            prijem_z_rozpoctu_veda: 95.60,
            prijem_z_rozpoctu_TaS: 7.30
          },
          {
            id: 9,
            employee_id: 1443,
            fakulta: "Fakulta teologická",
            katedra: "FT - Katedra biblistiky",
            priezvisko: "Kráľ",
            podiel_na_sume: 521.90,
            podiel: 8,
            rok: 2021,
            title: "Historický vývoj biblických interpretácií",
            project_id: 829,
            grant_program_id: 4,
            acronym: "HIVYBI",
            full_expenditures: 3900,
            kategoria_v_rozpocte: "T3",
            prijem_z_rozpoctu_veda: 80.00,
            prijem_z_rozpoctu_TaS: 6.20
          },
          {
            id: 10,
            employee_id: 1441,
            fakulta: "Fakulta medicíny",
            katedra: "FM - Katedra pediatrie",
            priezvisko: "Kováčová",
            podiel_na_sume: 712.60,
            podiel: 11,
            rok: 2023,
            title: "Inovácie v pediatrickom liečení",
            project_id: 827,
            grant_program_id: 3,
            acronym: "INOPED",
            full_expenditures: 5200,
            kategoria_v_rozpocte: "T2",
            prijem_z_rozpoctu_veda: 102.30,
            prijem_z_rozpoctu_TaS: 8.00
          },
          {
            id: 11,
            employee_id: 1442,
            fakulta: "Fakulta výtvarných umení",
            katedra: "FVU - Katedra maľby",
            priezvisko: "Šimko",
            podiel_na_sume: 632.80,
            podiel: 10,
            rok: 2022,
            title: "Moderné prístupy v maľbe a vizuálnom umení",
            project_id: 828,
            grant_program_id: 2,
            acronym: "MODVIZ",
            full_expenditures: 4600,
            kategoria_v_rozpocte: "T1",
            prijem_z_rozpoctu_veda: 95.60,
            prijem_z_rozpoctu_TaS: 7.30
          },
          {
            id: 12,
            employee_id: 1443,
            fakulta: "Fakulta teologická",
            katedra: "FT - Katedra biblistiky",
            priezvisko: "Kráľ",
            podiel_na_sume: 521.90,
            podiel: 8,
            rok: 2021,
            title: "Historický vývoj biblických interpretácií",
            project_id: 829,
            grant_program_id: 4,
            acronym: "HIVYBI",
            full_expenditures: 3900,
            kategoria_v_rozpocte: "T3",
            prijem_z_rozpoctu_veda: 80.00,
            prijem_z_rozpoctu_TaS: 6.20
          }
        ],
      }
    }


}
</script>

<style>
.datatable {
  margin-top: 100px;
}
</style>