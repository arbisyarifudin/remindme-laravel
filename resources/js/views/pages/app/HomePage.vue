<template>
    <div class="page-wrapper">
        <div class="home-header">
            <div class="toolbar">
                <div class="toolbar__logo">
                    <button>⏲ RemindMe</button>
                </div>
                <div class="toolbar__nav">
                    <button><i class="bi bi-box-arrow-right"></i> Logout</button>
                </div>
            </div>

            <div class="greeting">
                <h1>Hello, <span>John Doe</span>!</h1>
                <p>Today you won't forget the stuff you need to do.</p>
            </div>
        </div>

        <div class="home-main">

            <div class="scrollable-card">
                <div class="scrollable-card__nav" v-if="isNotMobile">
                    <button class="prev" disabled><i class="bi bi-chevron-left"></i></button>
                    <button class="next"><i class="bi bi-chevron-right"></i></button>
                </div>
                <div class="scrollable-card__list">
                    <div class="card reminder" v-for="i in 10" :key="i">
                        <div class="card-body">
                            <div class="reminder-title">Meeting with Boss {{ i }}</div>
                            <div class="reminder-event-date"><i class="bi bi-clock"></i> 12:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="reminder-list">
                <li class="reminder-item" v-for="i in 15" :key="i">
                    <div class="reminder-item__time">
                        11:30 AM
                    </div>
                    <div class="reminder-item__body">
                        <div class="reminder-item__body__title">Meeting with Boss {{ i }}</div>
                        <div class="reminder-item__body__time-left">30 minutes left</div>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';

const screenSize = ref(window.innerWidth);
const isNotMobile = computed(() => {
    return screenSize.value > 768;
});

const scrollableCardButtonHandler = () => {
    // scrollable card handler
    const scrollableCard = document.querySelector('.scrollable-card');
    const scrollableCardList = document.querySelector('.scrollable-card__list');
    const scrollableCardListItems = document.querySelectorAll('.scrollable-card__list .card');

    const prevBtn = document.querySelector('.scrollable-card__nav .prev');
    const nextBtn = document.querySelector('.scrollable-card__nav .next');

    if (!scrollableCard || !scrollableCardList || !scrollableCardListItems || !prevBtn || !nextBtn) {
        return;
    }

    let scrollableCardListWidth = 0;
    let scrollableCardWidth = 0;
    let scrollableCardListItemsWidth = 0;

    scrollableCardListItems.forEach(item => {
        scrollableCardListItemsWidth += item.offsetWidth;
    });

    scrollableCardListWidth = scrollableCardList.offsetWidth;
    scrollableCardWidth = scrollableCard.offsetWidth;

    // button handler
    nextBtn.addEventListener('click', () => {
        scrollableCardList.scrollLeft += scrollableCardWidth;
        prevBtn.disabled = false;

        if (scrollableCardList.scrollLeft + scrollableCardListWidth >= scrollableCardListItemsWidth) {
            nextBtn.disabled = true;
        }
    });

    prevBtn.addEventListener('click', () => {
        scrollableCardList.scrollLeft -= scrollableCardWidth;
        nextBtn.disabled = false;

        if (scrollableCardList.scrollLeft <= 0) {
            prevBtn.disabled = true;
        }
    });

    // disable button if scrollableCardListItemsWidth <= scrollableCardListWidth
    if (scrollableCardListItemsWidth <= scrollableCardListWidth) {
        nextBtn.disabled = true;
    }

    // disable button if scrollableCardList.scrollLeft <= 0
    if (scrollableCardList.scrollLeft <= 0) {
        prevBtn.disabled = true;
    }

    // console.log(scrollableCardListItemsWidth);
}

// touch/drag card item handler
const scrollableCardDragHandler = () => {
    const scrollableCardList = document.querySelector('.scrollable-card__list');

    let isDown = false;
    let startX;
    let scrollLeft;

    scrollableCardList.addEventListener('mousedown', (e) => {
        isDown = true;
        scrollableCardList.classList.add('active');
        startX = e.pageX - scrollableCardList.offsetLeft;
        scrollLeft = scrollableCardList.scrollLeft;
    });

    scrollableCardList.addEventListener('mouseleave', () => {
        isDown = false;
        scrollableCardList.classList.remove('active');
    });

    scrollableCardList.addEventListener('mouseup', () => {
        isDown = false;
        scrollableCardList.classList.remove('active');
    });

    scrollableCardList.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollableCardList.offsetLeft;
        const walk = (x - startX) * 3; //scroll-fast
        scrollableCardList.scrollLeft = scrollLeft - walk;
    });
}

onMounted(() => {
    screenSize.value = window.innerWidth;
    if (isNotMobile.value) {
        scrollableCardButtonHandler()
        scrollableCardDragHandler()
    }

    window.addEventListener('resize', () => {
        screenSize.value = window.innerWidth;

        if (isNotMobile.value) {
            scrollableCardButtonHandler()
            scrollableCardDragHandler()
        }
    });

})


</script>

<style lang="scss" scoped>
.page-wrapper {
    width: 700px;
    max-width: 100%;
    margin: 0 auto;
    height: 100vh;
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.home {
    &-header {
        background: linear-gradient(-110deg, #EDE4B5 25%, #F1EAC2 25%, #F1EAC2 50%, #F5EFD2 50%, #F5EFD2 75%, #F6F2DB 75%, #F6F2DB 100%);

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 0 30px;

            &__logo {
                button {
                    background-color: transparent;
                    border: none;
                    font-size: 1.5rem;
                    font-weight: 500;
                    cursor: pointer;

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

            &__nav {
                button {
                    background-color: transparent;
                    border: none;
                    font-size: 1rem;
                    font-weight: 500;
                    cursor: pointer;

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
        }

        .greeting {
            padding: 25px 30px 60px;

            h1 {
                font-size: 2.5rem;
                margin-bottom: 10px;
                font-weight: normal;

                span {
                    font-weight: 600;
                }
            }

            p {
                font-size: 1.25rem;
            }
        }
    }

    &-main {
        padding: 20px;
    }
}

.scrollable-card {
    position: relative;

    &__list {
        overflow-x: auto;
        white-space: nowrap;
        padding: 10px 0 10px;
        border-radius: 5px;
        margin-left: 10px;
        margin-right: -20px;
        margin-top: -70px;
        position: relative;

        .card {
            width: 300px;
            min-height: 80px;
            background-color: #fff;
            border-radius: 10px;
            display: inline-block;
            margin-right: 10px;

            // make linear-gradien (1: #02314A, 2: #144158, 3: #254F64), TIDAK patah-patah
            background: linear-gradient(60deg, #02314A 25%, #144158 50%, #254F64 75%, #365E70 100%);

            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

            &.reminder {
                color: #fff;

                &-title {
                    font-size: 1rem;
                    font-weight: 500;
                    margin-bottom: 5px;
                }

                &-event-date {
                    font-size: 0.875rem;
                    font-weight: 500;
                    color: #ccc;

                    i {
                        font-size: 0.875rem;
                        margin-right: 5px;
                    }
                }
            }
        }
    }

    &__nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        z-index: 1;
        width: 100%;

        button {
            background-color: transparent;
            border: none;
            font-size: 1.5rem;
            font-weight: 500;
            cursor: pointer;
            color: #fff;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.1);

            &:disabled {
                opacity: 0;
                // cursor: not-allowed;
            }

            &.prev {
                position: absolute;
                left: 20px;
                top: 50%;
                transform: translateY(-50%);
            }

            &.next {
                position: absolute;
                right: 30px;
                top: 50%;
                transform: translateY(-50%);
            }
        }
    }
}

.reminder-list {
    padding: 0;
    list-style: none;
    height: calc(100vh - 340px);
    overflow-y: auto;

    .reminder {
        &-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #fff;
            margin-bottom: 20px;

            &__time {
                font-size: 1.15rem;
                font-weight: 500;
                margin-right: 20px;
            }

            &__body {
                background-color: #F4F2E5;
                flex: 1;
                padding: 10px 20px;
                border-radius: 5px;
                position: relative;
                margin-left: 15px;

                &:before {
                    content: "";
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    left: -20px;
                    height: 50%;
                    width: 3px;
                    border-radius: 5px;
                    background-color: #F4F2E5;
                    // background-color: #D93E3E;
                }

                &__title {
                    font-size: 1.25rem;
                    font-weight: 500;
                    margin-bottom: 5px;
                }

                &__time-left {
                    font-size: 0.875rem;
                    font-weight: 500;
                    color: #bbb;
                }
            }
        }
    }
}
</style>

