<template>
    <div class="layout">
        <!-- <router-view></router-view> -->
        <router-view v-slot="{ Component }">
            <keep-alive :exclude="[]">
                <component :is="Component" />
            </keep-alive>
        </router-view>
    </div>
</template>
