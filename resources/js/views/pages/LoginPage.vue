<template>
  <div class="login-wrapper">

    <div class="back">
      <button class="btn btn-light btn-sm" @click="$router.push({ name: 'Home Page' })">
        <i class="bi bi-arrow-left"></i>
        Back
      </button>
    </div>

    <div class="text-center">
      <h1>Login</h1>
      <p>Enter your credentials to login and start creating reminders.</p>
    </div>

    <form class="form" @submit.prevent="onSubmitForm">
      <div class="mb-2">
        <label for="email" class="form-label">Email address</label>
        <input type="email" v-model="formState.email"
          :class="['form-control', errorState?.email?.length > 0 ? 'is-invalid' : '']" id="email"
          @change="errorState.email = ''">
        <div class="invalid-feedback" v-show="errorState?.email?.length > 0">{{ errorState?.email }}</div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" v-model="formState.password"
          :class="['form-control', errorState?.password?.length > 0 ? 'is-invalid' : '']" id="password"
          autocomplete="new-password" @change="errorState.password = ''">
        <div class="invalid-feedback" v-show="errorState?.password?.length > 0">{{ errorState?.password }}</div>
      </div>

      <div class="alert alert-danger py-2 small" v-show="errorState?.other?.length">{{ errorState?.other }}</div>

      <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg" :disabled="loading">
          <div v-if="loading" class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true">
            <span class="visually-hidden">Loading...</span>
          </div>
          Login
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, inject, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { mapErrorMessage, showToast } from '@/helpers/utils'

const $router = useRouter()
const axios = inject('axios')

const formState = ref({
  email: '',
  password: ''
})

const errorState = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const onSubmitForm = () => {
  // reset error state
  errorState.value = {
    email: '',
    password: ''
  }

  loading.value = true

  axios.post('/session', formState.value)
    .then(response => {
      console.log(response.data)

      const { access_token: accessToken, refresh_token: refreshToken, user } = response.data.data

      // save tokens and user to local storage
      localStorage.setItem('accessToken', accessToken)
      localStorage.setItem('refreshToken', refreshToken)
      localStorage.setItem('user', JSON.stringify(user))

      // set axios auth header with access token
      setAuthHeader(accessToken)

      // show toast
      showToast('success', 'Login success!')

      // bring user to home page
      $router.push({ name: 'App Home Page' })
    })
    .catch(error => {
      console.log(error?.response?.data)
      const messages = error?.response?.data?.msg
      if (typeof messages === 'object') {
        errorState.value = mapErrorMessage(error?.response?.data?.msg)
      } else {
        errorState.value = {
          email: '',
          password: '',
          other: messages
        }
      }
    }).finally(() => {
      loading.value = false
    })
}

function setAuthHeader (accessToken) {
  axios.defaults.headers.common.Authorization = `Bearer ${accessToken}`
}

onMounted(() => {
  // check if user is already logged in
  const accessToken = localStorage.getItem('accessToken')
  const user = JSON.parse(localStorage.getItem('user'))
  if (accessToken && user) {
    setAuthHeader(accessToken)
    $router.push({ name: 'App Home Page' })
  }
})

</script>

<style lang="scss" scoped>
h1 {
  font-size: 2rem;
  margin-bottom: 10px;
}

p {
  font-size: 1rem;
}

.back {
  button {
    background-color: transparent;
    border: none;

    &:hover {
      background-color: transparent;
      border: none;
    }

    &:focus {
      background-color: transparent;
      border: none;
      box-shadow: none;
    }

    &:active {
      background-color: transparent;
      border: none;
    }
  }
}

.form {
  .form-label {
    font-size: 1rem;
    font-weight: 500;
  }
}
</style>
