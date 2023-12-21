<template>
  <q-form @submit="submit" class="login-form">
    <q-input
      v-model="form.email"
      filled
      type="email"
      label="Email"
      lazy-rules
      :rules="[(val) => (val && val.length > 0) || 'Please type something']"
    />

    <q-input
      v-model="form.password"
      filled
      :type="isPwd ? 'password' : 'text'"
      label="Password"
      lazy-rules
      :rules="[
        (val) => (val && val.length > 0) || 'Please type something',
        (val) =>
          val.length >= 8 || 'The password must be at least 8 characters long',
      ]"
    >
      <template v-slot:append>
        <q-icon
          :name="isPwd ? 'visibility_off' : 'visibility'"
          class="cursor-pointer"
          @click="isPwd = !isPwd"
        />
      </template>
    </q-input>

    <div class="flex items-center justify-between mt-4">
      <a href="/register"> Ainda não possui uma conta? </a>
      <q-btn type="submit" color="primary" :disabled="isRequesting">
        <template v-slot:default v-if="!isRequesting"> Login </template>
        <template v-slot:default v-else>
          <q-spinner-hourglass color="white" />
        </template>
      </q-btn>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import authService from 'src/api/modules/auth'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const form = ref({
  email: '',
  password: '',
})
const isPwd = ref(true)
const isRequesting = ref(false)
const { login } = authService()

const submit = () => {
  isRequesting.value = true
  login(form.value)
    .then((res) => {
      if (!res.error) {
        $q.notify({
          type: 'positive',
          position: 'bottom-right',
          message: res.content.message ?? 'Login successful'
        })
        window.location.href = '/'
      } else {
        $q.notify({
          type: 'negative',
          position: 'bottom-right',
          message: res.error.message ?? 'Something went wrong. Please try again.'
        })
      }
    })
    .catch((error) => {
      $q.notify({
        type: 'negative',
        position: 'bottom-right',
        message: error.message ?? 'Email or password is invalid'
      })
    })
    .finally(() => {
      isRequesting.value = false
    })
}
</script>

<style scoped>
.login-form {
  width: 400px;
  padding: 20px;
  border-radius: 0.5rem;
  background-color: white;
}
</style>
