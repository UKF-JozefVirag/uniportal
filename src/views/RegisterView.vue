<template>
    <div class="background">
      <VerifyCodeModal ref="verifymodal" />
      <v-container class="registerCard">
        <v-row>
          <v-col 
              sm="12"
              md="12"
              lg="4"
              offset-lg="4"
              align-self="end"
              class="shadow-lg p-3 mb-5 bg-white rounded">
            <v-sheet width="300" class="mx-auto py-4">
                <h3 class="text-center">
                  Vitajte na UniPortáli
                </h3>
                <p class="text-center">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente, saepe.
                </p>
              <v-divider class="border-opacity-25"></v-divider>
              <v-form fast-fail @submit.prevent>
                <v-text-field
                    v-model="firstName"
                    label="Meno"
                ></v-text-field>
                <v-text-field
                    v-model="lastName"
                    label="Priezvisko"
                ></v-text-field>
                <v-text-field
                    v-model="email"
                    label="Email"
                ></v-text-field>
                <v-text-field
                    v-model="password"
                    label="Heslo"
                    type="password"
                ></v-text-field>
                <v-btn type="submit" block class="mt-2" color="success" @click="$refs.verifymodal.openDialog()" >Vytvoriť účet</v-btn>
              </v-form>
              <p class="mt-3 text-center">
                Už tu máš účet ?
                <span>
                  <router-link to="/login" style="text-decoration: none;" class="text-success">
                    Prihlás sa.
                  </router-link>
                </span>
              </p>
            </v-sheet>
          </v-col>
        </v-row>
      </v-container>
    </div>

  </template>
  
  
  <script setup>
  import VerifyCodeModal from "@/components/VerifyCodeModal.vue";

  </script>
  
  
  <style scoped>
  .background {
    width: 100%;
    height: 100%;
    background-image: url('../assets/loginRegisterBG.png');
    background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    z-index: 0;
    position: absolute;
  }
  .registerCard{
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 3;
  }
  </style>