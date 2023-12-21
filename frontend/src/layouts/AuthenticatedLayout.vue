<template>
  <q-layout view="lHh Lpr lFf">
    <Navbar />

    <q-page-container class="app">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import Navbar from 'src/components/Navbar.vue'
</script>

<style lang="scss" scoped>
.app {
  background-color: #F2F4F6;
}
</style>
