<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container class="auth">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<style scoped>
.logo {
  width: 120px;
  height: 120px;
}
.auth {
  background-image: linear-gradient(160deg, #0093e9 0%, #80d0c7 100%);
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
</style>
