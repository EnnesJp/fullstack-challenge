<template>
  <q-form @submit="submit" class="register-form">
    <q-input
      v-model="form.email"
      filled
      type="email"
      label="Email"
      lazy-rules
      :rules="[(val) => (val && val.length > 0) || 'Please type something']"
    />

    <q-input
      v-model="form.name"
      filled
      type="text"
      label="Name"
      lazy-rules
      :rules="[(val) => (val && val.length > 0) || 'Please type something']"
    />

    <q-input
      v-model="form.password"
      filled
      :type="isPwdPass ? 'password' : 'text'"
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
          :name="isPwdPass ? 'visibility_off' : 'visibility'"
          class="cursor-pointer"
          @click="isPwdPass = !isPwdPass"
        />
      </template>
    </q-input>

    <q-input
      v-model="form.confirmPassword"
      filled
      :type="isPwdConfPass ? 'password' : 'text'"
      label="Confirm Password"
      lazy-rules
      :rules="[
        (val) => (val && val.length > 0) || 'Please type something',
        (val) =>
          val.length >= 8 || 'The password must be at least 8 characters long',
        (val) =>
          val === form.password || 'The password confirmation does not match',
      ]"
    >
      <template v-slot:append>
        <q-icon
          :name="isPwdConfPass ? 'visibility_off' : 'visibility'"
          class="cursor-pointer"
          @click="isPwdConfPass = !isPwdConfPass"
        />
      </template>
    </q-input>

    <div class="flex items-center justify-between mt-4">
      <a href="/"> Back to Login </a>
      <q-btn type="submit" color="primary" :disabled="isRequesting">
        <template v-slot:default v-if="!isRequesting"> Register </template>
        <template v-slot:default v-else>
          <q-spinner-hourglass color="white" />
        </template>
      </q-btn>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import usersService from 'src/api/modules/users'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

const $q = useQuasar()
const form = ref({
  email: '',
  name: '',
  password: '',
  confirmPassword: '',
})
const isPwdPass = ref(true)
const isPwdConfPass = ref(true)
const isRequesting = ref(false)
const { createUser } = usersService()

const submit = () => {
  isRequesting.value = true
  createUser(form.value)
    .then((res) => {
      if (!res.error) {
        $q.notify({
          color: 'positive',
          position: 'bottom-right',
          message: res.content.message ?? 'User created successfully',
        })
        window.location.href = '/login'
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
        message: error.message ?? 'Something went wrong. Please try again.'
      })
    })
    .finally(() => {
      isRequesting.value = false
    })
}
</script>

<style scoped>
.register-form {
  width: 400px;
  padding: 20px;
  border-radius: 0.5rem;
  background-color: white;
}
</style>
src/api/modules/users
