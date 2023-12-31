<template>
    <div class="layout">
        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.layout {
    width: 700px;
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    .content {
        width: 100%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 20px;
        background-color: rgba(255, 255, 255, 0.8);
    }
}
</style>

