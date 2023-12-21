<template>
  <q-page class="row items-center justify-center column" style="gap: 20px">
    <ApplicationLogo class="logo" />
    <LoginForm />
  </q-page>
</template>

<script setup lang="ts">
import LoginForm from 'components/Auth/LoginForm.vue'
import ApplicationLogo from 'components/Base/ApplicationLogo.vue'
</script>

<style scoped>
.logo {
  width: 100px;
  height: 100px;
}
</style>
