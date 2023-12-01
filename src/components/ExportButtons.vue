<template>
        <v-col cols="auto">
            <v-btn
            prepend-icon="mdi mdi-file-document-outline"
            color="red"
            >
            <template v-slot:prepend>
                <v-icon color="white"></v-icon>
            </template>
                PDF
            </v-btn>
        </v-col>
        <v-col cols="auto">
            <v-btn
            prepend-icon="mdi mdi-file-excel-outline"
            color="success"
            >
            <template v-slot:prepend>
                <v-icon color="white"></v-icon>
            </template>
                Excel
            </v-btn>
        </v-col>
        <v-col cols="auto">
            <v-btn
            prepend-icon="mdi mdi-file-delimited-outline"
            color="success"
            >
            <template v-slot:prepend>
                <v-icon color="white"></v-icon>
            </template>
                Csv
            </v-btn>
        </v-col>
</template>

<script>

</script>

<style>

</style>