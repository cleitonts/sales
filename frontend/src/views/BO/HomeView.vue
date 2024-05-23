<template>
  <v-card class="overflow-visible">
    <v-card-title class="mt-4 mb-5 d-flex">
      <v-col cols="6" class="pa-0">
        <div
          class="icon-wrapper text-center rounded-lg mt-n5 position-absolute d-flex align-center justify-center bg-secondary-gradient"
        >
          <v-icon icon="fa fa-person" color="white" />
        </div>
        <h3 class="pl-16 ml-3">Hi this is home!</h3>
      </v-col>

      <slot name="after" />
    </v-card-title>

    <v-card-text> Hi this is home!</v-card-text>
  </v-card>
</template>
<script setup></script>
