<template>
  <v-container class="loginCard">
    
    <v-row>
      <v-col 
          sm="0"
          md="0"
          lg="9">
        <!--<img src="./assets/logo.png" alt="asd" srcset="">-->
      </v-col>
      <v-col 
          sm="12"
          md="12"
          lg="3" 
          align-self="end">
        <v-sheet width="300" class="mx-auto">
          <h3 class="text-center">
              Vitajte na UniPortáli
            </h3>
            <p class="text-center">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente, saepe.
            </p>
          <v-divider class="border-opacity-25"></v-divider>
          <v-form fast-fail @submit.prevent>
            <v-text-field
                v-model="email"
                label="Email"
                :rules="emailRules"
            ></v-text-field>

            <v-text-field
                v-model="password"
                label="Heslo"
                :rules="passwordRules"
                type="password"
            ></v-text-field>

            <v-btn type="submit" block class="mt-2" color="success">Prihlásiť sa</v-btn>
          </v-form>
        </v-sheet>
      </v-col>
    </v-row>
  </v-container>
</template>


<script setup>

</script>


<style scoped>
.loginCard{
  height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>