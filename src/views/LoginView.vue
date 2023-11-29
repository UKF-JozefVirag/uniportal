<template>
  <div class="container">
    <div class="row">
      <div class="col-6">
        <v-sheet width="300" class="mx-auto">
          <v-form fast-fail @submit.prevent>
            <v-text-field
                v-model="firstName"
                label="First name"
                :rules="firstNameRules"
            ></v-text-field>

            <v-text-field
                v-model="lastName"
                label="Last name"
                :rules="lastNameRules"
            ></v-text-field>

            <v-btn type="submit" block class="mt-2">Submit</v-btn>
          </v-form>
        </v-sheet>
      </div>
    </div>
  </div>
</template>


<script setup>

</script>


<style scoped>

</style>