<template>
    <div class="text-center">
        <h1>⏲ RemindMe</h1>
        <p>RemindMe is a simple reminder app that allows you to create reminders and send them to your email.</p>

        <router-link class="btn btn-primary btn-lg" :to="{ name: 'Login Page' }">Get Started</router-link>
    </div>
</template>

<style lang="scss" scoped>
h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

p {
    font-size: 1.5rem;
}
</style>

