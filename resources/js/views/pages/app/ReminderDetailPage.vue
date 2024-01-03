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
                        <li><a class="dropdown-item" href="#" @click.prevent="showDeleteDialog"><i
                                    class="bi bi-trash me-1"></i> Delete</a></li>
                    </ul>
                </div>
            </div>

            <div class="info" v-if="!loading">
                <h1>{{ detailState.title }}</h1>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="info__time" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        :data-bs-title="getDate(detailState.event_at)">
                        <div class="clock"><i class="bi bi-clock"></i> {{ getTime(detailState.event_at) }}</div>
                        <!-- <div class="day">Today</div> -->
                        <div class="day">{{ getDateLabel(detailState.event_at) }}</div>
                    </div>
                    <div class="info__notif" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-title="Reminder time">
                        <i class="bi bi-bell"></i>
                        <!-- <span class="ms-2">5 minutes before</span> -->
                        <span class="ms-2">{{ getReminderTimeLabel(detailState.remind_at) }}</span>
                    </div>
                </div>
            </div>

            <div class="loading" v-else>
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
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
                <div class="card-body" v-if="!loading">
                    <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis praesentium quas quos ab ex
                        doloribus blanditiis at impedit eos deserunt, dolorum id consequuntur odio eum aliquam corrupti!
                        Fugiat, minima quos!</p>
                    <ul>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                        <li>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</li>
                    </ul> -->
                    <span v-html="detailState.description"></span>
                </div>
            </div>

        </div>

        <!-- dialog delete -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Reminder</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure to delete this reminder?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-primary" @click="deleteReminder"
                            :disabled="deleteLoading">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, onMounted, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import moment from 'moment'
import { showToast } from '../../../helpers/utils';

const axios = inject('axios')
const bootstrap = inject('bootstrap')

const detailState = ref({
    id: 1,
    title: 'Meeting with client',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis praesentium quas quos ab ex doloribus blanditiis at impedit eos deserunt, dolorum id consequuntur odio eum aliquam corrupti! Fugiat, minima quos!',
    event_at: '2021-09-01 10:00:00',
    remind_at: '2021-09-01 09:55:00',
})

const loading = ref(false)

const $router = useRouter()
const fetchDetail = async () => {

    // fetch detail from api
    const id = $router.currentRoute.value.params.id

    loading.value = true
    await axios.get('/reminders/' + id)
        .then(res => {
            console.log('res', res.data)
            detailState.value = res.data.data
        })
        .catch(err => {
            console.log(err)

            // if status 404, redirect to home page
            // else, show error message
            if (err?.response?.status === 404) {
                showToast('danger', 'Reminder not found!')
                $router.push({ name: 'App Home Page' })
            } else {
                showToast('danger', 'Something went wrong!')
            }
        })
        .finally(() => {
            loading.value = false
        })
}

onMounted(() => {
    nextTick(async () => {
        await fetchDetail()

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    })
})

watch(() => $router.currentRoute.value.params.id, (newId) => {
    // detailState.value = {}
    if (newId && parseInt(newId) !== detailState.value.id) {
        fetchDetail()
    }
})

/* HELPER METHOD */

const getTime = (unixtTime = 0) => {
    if (!unixtTime) return ''
    return moment(unixtTime * 1000).format('hh:mm A')
}

const getDate = (unixtTime = 0) => {
    if (!unixtTime) return ''
    return moment(unixtTime * 1000).format('dddd, DD MMMM YYYY')
}

const getDateLabel = (unixTimeOrDate = 0, format = 'DD MMMM YYYY') => {
    if (!unixTimeOrDate) return moment().format(format)

    let date

    // check is date or time
    if (typeof unixTimeOrDate === 'string' && unixTimeOrDate.includes('-')) {
        date = moment(unixTimeOrDate).format(format)
    }

    date = moment(unixTimeOrDate * 1000).format(format)

    // check is today
    if (moment().format(format) === date) {
        return 'Today'
    }

    return date
}

const getReminderTimeLabel = (unixtTime) => {
    if (!unixtTime) return ''

    // event_at - remind_at = reminder time
    const eventAt = moment(detailState.value.event_at * 1000)
    const remindAt = moment(detailState.value.remind_at * 1000)

    const diff = eventAt.diff(remindAt, 'minutes')

    if (diff === 0) {
        return 'At time of event'
    }

    if (diff === 1) {
        return '1 minute before'
    }

    if (diff > 1 && diff < 60) {
        return `${diff} minutes before`
    }

    if (diff === 60) {
        return '1 hour before'
    }

    if (diff > 60 && diff < 1440) {
        return `${Math.floor(diff / 60)} hours before`
    }

    if (diff === 1440) {
        return '1 day before'
    }

    if (diff > 1440) {
        return `${Math.floor(diff / 1440)} days before`
    }

    return ''
}

/* DELETE */
let deleteModal = null

// Show the delete dialog
const showDeleteDialog = () => {
    deleteModal.show()
}

const deleteLoading = ref(false)
const deleteReminder = () => {
    deleteLoading.value = true
    axios.delete('/reminders/' + detailState.value.id)
        .then(res => {
            // console.log('res', res.data)
            showToast('success', 'Reminder deleted successfully!')
            deleteModal.hide()

            // redirect to home page
            $router.push({ name: 'App Home Page', query: { needToRefresh: true } })
        })
        .catch(err => {
            console.log('delete error:', err)
        })
        .finally(() => {
            deleteLoading.value = false
        })
}

onMounted(() => {
    nextTick(() => {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'))
        document.getElementById('deleteModal').addEventListener('hidden.bs.modal', event => {
            //   console.log('event', event)
        })
    })
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

.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px;
}

.empty {
    //   display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
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

            &__nav {}

            &__title {
                font-size: 1.25rem;
                font-weight: 600;
            }
        }

        .info {
            padding: 25px 30px 60px;
            color: #EAE2B7;

            h1 {
                font-size: 2rem;
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

