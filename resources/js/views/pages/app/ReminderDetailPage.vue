<template>
    <div class="page-wrapper">
        <div class="detail-header">
            <div class="toolbar">
                <div class="toolbar__back">
                    <button class="btn text-white" @click="$router.go(-1)"><i class="bi bi-arrow-left"></i></button>
                </div>
                <div class="toolbar__title">Reminder Details</div>
                <div class="toolbar__nav">
                    <button class="btn text-white" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-1"></i> Edit</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-1"></i> Delete</a></li>
                    </ul>
                </div>
            </div>

            <div class="info">
                <h1>Meeting with Boss {{ $route.params.id }}</h1>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="info__time">
                        <div class="clock"><i class="bi bi-clock"></i> 12:30PM</div>
                        <div class="day">Today</div>
                    </div>
                    <div class="info__notif">
                        <i class="bi bi-bell"></i>
                        <span class="ms-2">5 minutes before</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-main">

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="icon"><i class="bi bi-card-text"></i></div>
                        Notes
                    </div>
                </div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis praesentium quas quos ab ex
                        doloribus blanditiis at impedit eos deserunt, dolorum id consequuntur odio eum aliquam corrupti!
                        Fugiat, minima quos!</p>
                    <ul>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>


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

.detail {
    &-header {
        background: linear-gradient(60deg, #07354E 20%, #276386 100%);

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 0 30px;
            color: #fff;

            button.btn {
                font-size: 1.5rem;
                background-color: transparent;
                border: none;
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

            &__nav {

            }

            &__title {
                font-size: 1.25rem;
                font-weight: 600;
            }
        }

        .info {
            padding: 25px 30px 60px;
            color: #EAE2B7;

            h1 {
                font-size: 2.5rem;
                margin-bottom: 10px;
                font-weight: 600;

                span {
                    font-weight: 600;
                }
            }

            p {
                font-size: 1.25rem;
            }

            &__time {
                display: flex;

                .clock {
                    margin-right: 15px;

                    i {
                        margin-right: 5px;
                    }
                }

                .day {
                    margin-left: 15px;
                    position: relative;

                    &::before {
                        content: "";
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        left: -15px;
                        height: 3px;
                        width: 3px;
                        border-radius: 50%;
                        background-color: #aaa;
                    }
                }

            }
        }
    }

    &-main {
        padding: 20px;
        background-color: #F4F5F5;
        height: calc(100vh - 200px);

        .card-header {
            background-color: #fff;
            padding-top: 15px;
            padding-bottom: 10px;
        }

        .card-title {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;

            .icon {
                background-color: #07354E;
                border-radius: 50%;
                color: #fff;
                width: 37px;
                height: 37px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 10px;
                font-size: 1.25rem;
            }
        }

        .card-body {
            font-size: 1.2rem;
            line-height: 1.5;
        }
    }
}
</style>

