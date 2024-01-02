<template>
  <div class="login-wrapper">

    <div class="back">
      <button class="btn btn-light btn-sm" @click="$router.go(-1)">
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

      <div class="alert alert-danger py-2" v-show="errorState?.other?.length">{{ errorState?.other }}</div>

      <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg" :disabled="loading">Login</button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref, inject } from 'vue';
import { useRouter } from 'vue-router';
import { mapErrorMessage } from '@/helpers/utils';

const $router = useRouter();
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

  axios.post('/session', formState.value)
    .then(response => {
      console.log(response.data);

      const { access_token, refresh_token, user } = response.data.data
      localStorage.setItem('accessToken', access_token)
      localStorage.setItem('refreshToken', refresh_token)
      localStorage.setItem('user', user)

      setAuthHeader(access_token)

      $router.push({ name: 'App Home Page' });
    })
    .catch(error => {
      console.log(error?.response?.data)
      const messages = error?.response?.data?.msg
      if (typeof messages === 'object') {
        errorState.value = mapErrorMessage(error?.response?.data?.msg)
        return
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

function setAuthHeader(accessToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`;
}

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

