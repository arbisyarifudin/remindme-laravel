<template>
    <div class="modal fade" id="formReminderDialogModal" tabindex="-1" aria-labelledby="formReminderDialogModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary py-2">
                    <h1 class="modal-title fs-6" id="formReminderDialogModalLabel">Add Reminder</h1>
                    <button type="button" class="btn-closex btn btn-sm text-white p-0 btn-lg fs-4" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x"></i></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="onSubmit">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" :class="['form-control', errorState?.title?.length > 0 ? 'is-invalid' : '']"
                                id="title" placeholder="Title" v-model="formState.title" @change="errorState.title = ''">
                            <div class="invalid-feedback" v-show="errorState?.title?.length > 0">{{ errorState?.title }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea :class="['form-control', errorState?.description?.length > 0 ? 'is-invalid' : '']"
                                id="description" rows="3" placeholder="Description" v-model="formState.description"
                                @change="errorState.description = ''"></textarea>
                            <div class="invalid-feedback" v-show="errorState?.description?.length > 0">{{
                                errorState?.description }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="event_date" class="form-label">Event Date</label>
                                <input type="date"
                                    :class="['form-control', errorState?.event_date?.length > 0 ? 'is-invalid' : '']"
                                    id="event_date" placeholder="Event Date" :min="getDate(0, 'YYYY-MM-DD')"
                                    v-model="formState.event_date" @change="errorState.event_date = ''">
                                <div class="invalid-feedback" v-show="errorState?.event_date?.length > 0">{{
                                    errorState?.event_date }}
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="event_time" class="form-label">Event Time</label>
                                <input type="time"
                                    :class="['form-control', errorState?.event_time?.length > 0 ? 'is-invalid' : '']"
                                    id="event_time" placeholder="Event Time" :min="minTime" v-model="formState.event_time"
                                    @change="errorState.event_time = ''">
                                <div class="invalid-feedback" v-show="errorState?.event_time?.length > 0">{{
                                    errorState?.event_time }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="event_at" class="form-label">Remind Me</label>
                            <select :class="['form-select', errorState?.remind_at?.length > 0 ? 'is-invalid' : '']"
                                v-model="formState.remind_at" @change="errorState.remind_at = ''">
                                <!-- <option selected>None</option> -->
                                <option :value="opt.value" v-for="opt in remindMeOptions" :key="opt.value">{{ opt.label }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-show="errorState?.remind_at?.length > 0">{{
                                errorState?.remind_at }}</div>
                        </div>
                    </form>
                    <div class="alert alert-danger py-2 small" v-show="errorState?.other?.length">{{ errorState?.other }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" v-if="!submitLoading"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary" :disabled="submitLoading" @click="onSubmit">
                        <span v-show="submitLoading" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                        <span v-show="!submitLoading">Submit</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from 'moment'
import { ref, onMounted, nextTick, computed, watch, inject } from 'vue'
import { defineProps, defineEmits } from 'vue'
import { showToast, mapErrorMessage } from '@/helpers/utils'

const axios = inject('axios')
const bootstrap = inject('bootstrap')

const $props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const $emit = defineEmits(['close'])

watch(() => $props.show, val => {
    if (val) {
        showFormReminderDialog()
    }
})

let formReminderDialogModal = null
const showFormReminderDialog = () => {
    formReminderDialogModal.show()
}

onMounted(() => {
    nextTick(() => {
        formReminderDialogModal = new bootstrap.Modal(document.getElementById('formReminderDialogModal'))
        document.getElementById('formReminderDialogModal').addEventListener('hidden.bs.modal', event => {
            //   console.log('event', event)
            $emit('close')
        })
    })
})

const remindMeOptions = [
    { value: 1, label: 'At time of event' },
    { value: 2, label: '5 minutes before' },
    { value: 3, label: '10 minutes before' },
    { value: 4, label: '15 minutes before' },
    { value: 5, label: '30 minutes before' },
    { value: 6, label: '1 hour before' },
    { value: 7, label: '2 hours before' },
    { value: 8, label: '1 day before' },
    { value: 9, label: '2 days before' }
]

const minTime = computed(() => {
    const now = moment()

    // if today, min time is now
    const formDate = moment(formState.value.event_date)
    if (now.isSame(formDate, 'day')) {
        return now.format('HH:mm')
    }

    return '00:00'
})

const formState = ref({
    title: '',
    description: '',
    event_date: '',
    event_time: '',
    remind_at: 4
})

const errorState = ref({
    title: '',
    description: '',
    event_date: '',
    event_time: '',
    remind_at: '',
    other: ''
})

const getDate = (timeOrDate = 0, format = 'DD MMM YYYY') => {
    if (!timeOrDate) return moment().format(format)

    // chekc is date or time
    if (typeof timeOrDate === 'string' && timeOrDate.includes('-')) {
        return moment(timeOrDate).format(format)
    }

    return moment(timeOrDate * 1000).format(format)
}

const getRemindAtTime = () => {
    // get remind_at label
    const remindAtLabel = remindMeOptions.find(opt => opt.value === formState.value.remind_at)?.label

    // get remind at as unix datetime from (event_date + event_time) - remindAtLabel
    if (remindAtLabel === 'At time of event') {
        return moment(formState.value.event_date + ' ' + formState.value.event_time).unix()
    }

    else {
        // if label contains minutes
        if (remindAtLabel.includes('minutes')) {
            const minutes = remindAtLabel.split(' ')[0]
            return moment(formState.value.event_date + ' ' + formState.value.event_time).subtract(minutes, 'minutes').unix()
        } else if (remindAtLabel.includes('hour')) {
            const hours = remindAtLabel.split(' ')[0]
            return moment(formState.value.event_date + ' ' + formState.value.event_time).subtract(hours, 'hours').unix()
        } else if (remindAtLabel.includes('day')) {
            const days = remindAtLabel.split(' ')[0]
            return moment(formState.value.event_date + ' ' + formState.value.event_time).subtract(days, 'days').unix()
        }
    }
}

const submitLoading = ref(false)
const onSubmit = () => {

    // reset error state
    errorState.value = {
        title: '',
        description: '',
        event_date: '',
        event_time: '',
        remind_at: '',
        other: ''
    }

    // validate title
    if (!formState.value.title) {
        errorState.value.title = 'Title is required'
        return
    }

    // validate description
    if (!formState.value.description) {
        errorState.value.description = 'Description is required'
        return
    }

    // validate event_date
    if (!formState.value.event_date) {
        errorState.value.event_date = 'Event date is required'
        return
    }

    // validate event_time minTime
    const formDate = moment(formState.value.event_date)
    const formTime = moment(formState.value.event_time, 'HH:mm')
    const now = moment()
    if (now.isSame(formDate, 'day') && formTime.isBefore(now)) {
        errorState.value.event_time = 'If you choose today, event time must be greater than now'
        return
    }


    const submitData = {
        title: formState.value.title,
        description: formState.value.description,
        event_at: moment(formState.value.event_date + ' ' + formState.value.event_time).unix(),
        remind_at: getRemindAtTime()
    }

    //   console.log('submitData', submitData)

    submitLoading.value = true
    axios.post('/reminders', submitData)
        .then(res => {
            console.log(res.data.data)

            showToast('success', 'New reminder added!')
            formReminderDialogModal.hide()

            // reset form state
            formState.value = {
                title: '',
                description: '',
                event_date: '',
                event_time: '',
                remind_at: 4
            }

            $emit('success')
        })
        .catch(err => {
            console.log(err)
            const messages = err?.response?.data?.msg
            if (typeof messages === 'object') {
                errorState.value = mapErrorMessage(err?.response?.data?.msg)
                return
            } else {
                errorState.value = {
                    title: '',
                    description: '',
                    event_date: '',
                    event_time: '',
                    remind_at: '',
                    other: messages
                }
            }
        })
        .finally(() => {
            submitLoading.value = false
        })
}

</script>
