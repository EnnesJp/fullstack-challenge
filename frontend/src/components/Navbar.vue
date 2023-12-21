<template>
  <div>
    <q-toolbar class="navbar text-white">
      <ApplicationLogo class="logo" />

      <q-toolbar-title> ExpenseHub </q-toolbar-title>

      <span class="q-mr-md">Oi, {{ store.getUserName }}!</span>
      <q-btn flat size="md" style="color: #fff" label="Logout" @click="logoutUser" />
    </q-toolbar>
  </div>
</template>

<script setup lang="ts">
import ApplicationLogo from 'components/Base/ApplicationLogo.vue'
import authService from 'src/api/modules/auth'
import { useAuthStore } from 'stores/auth'

const store = useAuthStore()
const { logout } = authService()

function logoutUser() {
  logout()
  window.location.href = '/login'
}
</script>

<style scoped lang="scss">
.navbar {
  background: linear-gradient(160deg, #0093e9 0%, #80d0c7 100%);
  .logo {
    width: 40px;
    height: 40px;
  }

  .btn-logout {
    min-width: 100px;
    background-color: #0094e973;
  }
}
</style>
