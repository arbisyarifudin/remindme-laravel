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
                <input type="email" v-model="email" class="form-control" id="email" aria-describedby="emailHelp">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" v-model="password" class="form-control" id="password" autocomplete="new-password">
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
            </div>

        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');

const $router = useRouter();
const onSubmitForm = () => {
    $router.push({ name: 'App Home Page' });
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

