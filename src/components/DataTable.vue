<template>
  <v-container class="datatable">
    <v-row>
      <v-col cols="11" offset="1">
        <v-table>
          <thead>
          <tr>
            <th class="text-left"
                v-for="item in headers"
                :key="item.id">
              {{ item.name }}
            </th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="item in records"
              :key="item.id"
          >
            <td>{{ item.id }}</td>
            <td>{{ item.employee_id }}</td>
            <td>{{ item.fakulta }}</td>
            <td>{{ item.katedra }}</td>
            <td>{{ item.priezvisko }}</td>
            <td>{{ item.podiel_na_sume }}</td>
            <td>{{ item.podiel }}</td>
            <td>{{ item.rok }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.project_id }}</td>
            <td>{{ item.grant_program_id }}</td>
            <td>{{ item.acronym }}</td>
            <td>{{ item.full_expenditures }}</td>
            <td>{{ item.kategoria_v_rozpocte }}</td>
            <td>{{ item.prijem_z_rozpoctu_veda }}</td>
            <td>{{ item.prijem_z_rozpoctu_TaS }}</td>
          </tr>
          </tbody>
        </v-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {

  data() {
    return {
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
          }
        ],
      headers:[
        {
          id: 1,
          name: "ID"
        },
        {
          id: 2,
          name: "ID zamestnanca"
        },
        {
          id: 3,
          name: "Fakulta"
        },
        {
          id: 4,
          name: "Katedra"
        },
        {
          id: 5,
          name: "Priezvisko"
        },
        {
          id: 6,
          name: "Podieľ na sume"
        },
        {
          id: 7,
          name: "Podieľ"
        },
        {
          id: 8,
          name: "Rok"
        },
        {
          id: 9,
          name: "Názov práce"
        },
        {
          id: 10,
          name: "ID Projektu"
        },
        {
          id: 11,
          name: "ID grant programu"
        },
        {
          id: 12,
          name: "Akronym"
        },
        {
          id: 13,
          name: "Úplné výdavky"
        },
        {
          id: 14,
          name: "Kategória v rozpočte"
        },
        {
          id: 15,
          name: "Príjem z rozpočtu veda"
        },
        {
          id: 16,
          name: "Príjem z rozpočtu TaS"
        }
      ]

      }
    }


}
</script>

<style>
.datatable {
  margin-top: 100px;
}
</style>