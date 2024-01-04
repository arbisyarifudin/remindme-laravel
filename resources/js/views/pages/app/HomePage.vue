<template>
  <div class="page-wrapper">
    <div class="home-header">
      <div class="toolbar">
        <div class="toolbar__nav">
          <button @click="showLogoutDialog"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </div>
        <div class="toolbar__logo">
          <button>⏲ RemindMe</button>
        </div>
        <div class="toolbar__nav">
          <button class="btn-add" @click="showAddReminderDialog"><i class="bi bi-plus"></i> New</button>
        </div>
      </div>

      <div class="greeting">

        <h1>Hello, <span>{{ user?.name }}</span>!</h1>
        <p>Today you won't forget the stuff you need to do.</p>

        <div class="scrollable-card__title" v-if="reminderUpcomings?.length > 0">
          <h2>📌 Upcoming Reminders</h2>
        </div>
      </div>
    </div>

    <div class="home-main">

      <div class="scrollable-card" :class="[!reminderUpcomings.length ? 'empty' : '']">
        <div class="scrollable-card__nav" v-show="isNotMobile && reminderUpcomings.length">
          <button class="prev" disabled><i class="bi bi-chevron-left"></i></button>
          <button class="next"><i class="bi bi-chevron-right"></i></button>
        </div>
        <div class="scrollable-card__list">
          <div class="card reminder" v-for="reminderToday in reminderUpcomings" :key="reminderToday.id"
            @click="$router.push({ name: 'App Reminder Detail Page', params: { id: reminderToday.id } })">
            <div class="card-body">
              <div class="reminder-title">{{ truncateString(reminderToday.title, 5) }}</div>
              <div class="reminder-event-date"><i class="bi bi-clock"></i> {{ getTime(reminderToday.event_at)
              }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="">
        <div class="reminder-list__toolbar">
          <div class="reminder-list__title">
            <h3>{{ dateSelected === 'today' ? '✨' : '📅'}} {{ getDateLabel(dateSelected) }}'s events</h3>
          </div>
          <select v-model="dateSelected" class="form-control">
            <option value="today">Today</option>
            <option value="tomorrow">Tomorrow</option>
            <option :value="date" v-for="date in datesFromNowOfMonth">{{ getDate(date, 'DD/MM/YYYY') }}</option>
          </select>
        </div>
        <ul class="reminder-list" v-if="!loading[dateSelected] && reminderDateSelected.length">
          <li class="reminder-item" :class="[isDatePassed(reminder.event_at) ? 'passed' : '']"
            v-for="reminder in reminderDateSelected" :key="reminder.id" @click="openDetailPage(reminder)">
            <div class="reminder-item__time">
              <!-- 11:30 AM -->
              {{ getTime(reminder.event_at) }}
              <div class="reminder-item__time__date">
                {{ getDate(reminder.event_at) }}
              </div>
            </div>
            <div class="reminder-item__body">
              <div class="reminder-item__body__title">{{ truncateString(reminder.title, 5) }}</div>
              <div class="reminder-item__body__desc">
                <div v-html="truncateString(reminder.description, 15)"></div>
              </div>
              <div class="reminder-item__body__time-left">
                <!-- 30 minutes left -->
                {{ getTimeLeft(reminder.event_at) }}
              </div>
            </div>
          </li>
        </ul>
        <div class="loading" v-if="loading[dateSelected]">
          <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div class="empty" v-if="!loading[dateSelected] && !reminderDateSelected.length">
          <div class="alert alert-light small" role="alert">
            No event found.
          </div>
        </div>
      </div>

    </div>

    <!-- dialog logout bootstrap -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="logoutModalLabel">Logout</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure to logout?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-sm btn-primary" @click="logout" :disabled="logoutLoading">Logout</button>
          </div>
        </div>
      </div>
    </div>

    <!-- dialog add reminder -->
    <FormReminder :show="addReminderDialogShow" @close="addReminderDialogShow = false" @success="onSuccessAddReminderSubmission" />

  </div>
</template>

<script setup>
import { computed, inject, onBeforeUnmount, onMounted, ref, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import moment from 'moment'
import { showToast } from '@/helpers/utils';

import FormReminder from '@/views/components/FormReminder.vue';

const axios = inject('axios')

/* REMINDER */
const reminders = ref([])
// const reminderUpcomings = computed(() => reminders.value?.upcoming?.slice(0, 3) || [])
const reminderUpcomings = computed(() => {
  // get upcoming reminder, but not yet passed
  const upcomingReminders = reminders.value?.upcoming?.filter(reminder => {
    return moment(reminder.event_at * 1000).isAfter(moment())
  }) || []

  // get upcoming reminder, but not yet passed, and limit to 3
  return upcomingReminders.slice(0, 3)
})

const dateSelected = ref('today')
const reminderDateSelected = computed(() => reminders.value[dateSelected.value] || [])

const datesFromNowOfMonth = computed(() => {
  const dates = []
  for (let i = 0; i < 15; i++) {
    dates.push(moment().add(i + 2, 'days').format('YYYY-MM-DD'))
  }
  return dates
})

const loading = ref({
  today: true,
  tomorrow: true,
  upcoming: true
})
const getReminders = (params) => {
  const { event_date, limit } = params || { event_date: 'today' }

  loading.value[event_date] = true
  axios.get('/reminders', {
    params: {
      // event_date: 'today' // today, tomorrow, selected_date
      event_date: event_date === 'upcoming' ? 'today' : event_date,
      limit,
      sort: {
        dir: 'asc',
        name: 'event_at'
      }
    }
  })
    .then(res => {
      console.log(res.data.data)
      // reminders.value = res.data.data?.reminders
      reminders.value[event_date] = res.data.data?.reminders
    })
    .catch(err => {
      console.log(err)
    })
    .finally(() => {
      loading.value[event_date] = false

      scrollableCardButtonHandler()
      scrollableCardDragHandler()
    })
}

onMounted(() => {
  getReminders({ event_date: 'upcoming' })
  getReminders({ event_date: dateSelected.value })
})

watch(dateSelected, (newVal, oldVal) => {
  console.log('dateSelected', newVal)
  getReminders({ event_date: newVal })
})

const $router = useRouter()
const openDetailPage = (reminder) => {
  //   if (isDatePassed(reminder.event_at)) return
  $router.push({ name: 'App Reminder Detail Page', params: { id: reminder.id } })
}

watch($router.currentRoute, (currRoute) => {
//   console.log('route change', currRoute)
  if (currRoute.name === 'App Home Page') {
    const queryParams = currRoute.query
    if (queryParams?.needToRefresh === 'true') {
        getReminders({ event_date: 'upcoming' })
        getReminders({ event_date: dateSelected.value })
    }
  }
})

/* METHOD HELPER */

const truncateString = (string, limit) => {
  // if (string.length > limit) {
  //   return string.slice(0, limit) + '...'
  // }

  const stringWords = string.split(' ')
  if (stringWords.length > limit) {
    // slice by word
    return string.split(' ').slice(0, limit).join(' ') + '...'
  }
  return string
}

const getDate = (timeOrDate = 0, format = 'DD MMM YYYY') => {
  if (!timeOrDate) return moment().format(format)
  //   return moment(time * 1000).format('Do MMM, YYYY')
  //   return moment(time * 1000).format('MMM DD, YYYY')
  //   return moment(time * 1000).format('DD MMM YYYY')
  //   return moment(time * 1000).format('DD/MM/YYYY')

  // chekc is date or time
  if (typeof timeOrDate === 'string' && timeOrDate.includes('-')) {
    return moment(timeOrDate).format(format)
  }

  return moment(timeOrDate * 1000).format(format)
}

const getTime = (time = 0) => {
  if (!time) return '???'
  //   const date = new Date(time)
  //   const hours = date.getHours()
  //   const minutes = date.getMinutes()
  //   const ampm = hours >= 12 ? 'PM' : 'AM'
  //   const hours12 = hours % 12 || 12
  //   const minutes12 = minutes < 10 ? '0' + minutes : minutes
  //   return `${hours12}:${minutes12} ${ampm}`

  // with momenjs
  return moment(time * 1000).format('hh:mm A')
  //   return moment(time * 1000).format('HH:MM')
}

const getTimeLeft = (time = 0) => {
  if (!time) return '???'

  // time left from now
  const timeLeft = moment(time * 1000).fromNow()
  return timeLeft
}

const getDateLabel = (date = 'today') => {
  if (date === 'today') return 'Today'
  if (date === 'tomorrow') return 'Tomorrow'
  if (date === 'upcoming') return 'Upcoming'
  return moment(date).format('dddd, DD MMMM YYYY')
}

const isDatePassed = (time = 0) => {
  if (!time) return false
  return moment(time * 1000).isBefore(moment())
}


/* USER */
const user = computed(() => {
  const user = localStorage.getItem('user')
  if (!user) return null
  return JSON.parse(user)
})


/* LOGOUT */
const bootstrap = inject('bootstrap')
let logoutModal = null

// Show the logout dialog
const showLogoutDialog = () => {
  logoutModal.show()
}

const logoutLoading = ref(false)
const logout = () => {
  logoutLoading.value = true
  axios.delete('session')
    .then(res => {
      // console.log('res', res.data)
      showToast('success', 'Logout success!')
      logoutModal.hide()
      $router.replace({ name: 'Login Page' })
    })
    .catch(err => {
      console.log('logout error:', err)
    })
    .finally(() => {
      logoutLoading.value = false
    })
}

onMounted(() => {
  nextTick(() => {
    logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'))
    document.getElementById('logoutModal').addEventListener('hidden.bs.modal', event => {
      //   console.log('event', event)
    })
  })
})

/* ADD REMINDER */
const addReminderDialogShow = ref(false)
const showAddReminderDialog = () => {
  addReminderDialogShow.value = true
}

const onSuccessAddReminderSubmission = () => {
  addReminderDialogShow.value = false
  getReminders({ event_date: 'upcoming' })
  getReminders({ event_date: dateSelected.value })
}

/* SCROLLABLE CARD */

const screenSize = ref(window.innerWidth);
const isNotMobile = computed(() => {
  return screenSize.value >= 768;
});

watch(isNotMobile, (newVal, oldVal) => {
  console.log('isNotMobile', newVal)
  if (newVal) {
    scrollableCardButtonHandler()
    // scrollableCardDragHandler()
  }
})

// button next/prev handler
const scrollableCardButtonHandler = () => {
  if (!isNotMobile.value) return

  const scrollableCard = document.querySelector('.scrollable-card');
  const scrollableCardList = document.querySelector('.scrollable-card__list');
  const scrollableCardListItems = document.querySelectorAll('.scrollable-card__list .card');

  const prevBtn = document.querySelector('.scrollable-card__nav .prev');
  const nextBtn = document.querySelector('.scrollable-card__nav .next');

  if (!scrollableCard || !scrollableCardList || !scrollableCardListItems || !prevBtn || !nextBtn) {
    return;
  }

  const scrollableCardWidth = scrollableCard.offsetWidth;

  const handleScrollableCard = () => {
    if (scrollableCardListItems.length < 2) {
      prevBtn.disabled = true;
      nextBtn.disabled = true;
    } else if (scrollableCardList.scrollLeft <= 0) {
      prevBtn.disabled = true;
      nextBtn.disabled = false;
    } else if (
      scrollableCardList.scrollLeft + scrollableCardList.offsetWidth >=
      scrollableCardList.scrollWidth
    ) {
      prevBtn.disabled = false;
      nextBtn.disabled = true;
    } else {
      prevBtn.disabled = false;
      nextBtn.disabled = false;
    }
  };

  handleScrollableCard();

  prevBtn.addEventListener('click', () => {
    scrollableCardList.scrollLeft -= scrollableCardWidth;
    handleScrollableCard();
  });

  nextBtn.addEventListener('click', () => {
    scrollableCardList.scrollLeft += scrollableCardWidth;
    handleScrollableCard();
  });


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

let resizeHandler = () => {
  screenSize.value = window.innerWidth;
}

onMounted(() => {
  resizeHandler()
  scrollableCardButtonHandler()
  scrollableCardDragHandler()

  window.addEventListener('resize', resizeHandler);
})

onBeforeUnmount(() => {
  if (resizeHandler) {
    window.removeEventListener('resize', resizeHandler);
  }
})


</script>

<style lang="scss" scoped>
.page-wrapper {
  width: 700px;
  max-width: 100%;
  margin: 0 auto;
  //   height: 100vh;
  background-color: #fff;
  border-radius: 5px;
  overflow: hidden;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

  min-height: 100vh;
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

.home {
  &-header {
    background: linear-gradient(-110deg, #EDE4B5 25%, #F1EAC2 25%, #F1EAC2 50%, #F5EFD2 50%, #F5EFD2 75%, #F6F2DB 75%, #F6F2DB 100%);
    position: relative;

    .toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 60px;
      border-bottom: 1px solid #ccc;
      margin-bottom: 20px;
      padding: 0 30px;

      &__logo {
        position: absolute;
        flex: none;
        left: 50%;
        transform: translateX(-50%);

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
          &:not(.btn) {
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

            &.btn-add {
              background-color: #fff;
              border-radius: 5px;
              padding: 5px 10px;
              color: #555;
              transition: 0.3s all;
              font-size: 0.875rem;

              &:hover {
                background-color: #eee;
              }
            }
          }
        }
      }
    }

    .greeting {
      padding: 25px 30px 50px;

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
    position: relative;
    padding: 20px;
  }
}

.scrollable-card {
  position: relative;
  margin-left: 10px;
  margin-right: -20px;
  margin-top: -80px;
  margin-bottom: 20px;

  &.empty {
    margin-top: -100px;
    height: auto;
  }

  &__title {
    margin-top: 30px;
    margin-bottom: 20px;

    h2 {
      font-size: 0.85rem;
      font-weight: 500;
    }
  }

  &__list {
    overflow-x: auto;
    // white-space: nowrap;
    padding: 10px 0 10px;
    border-radius: 5px;
    position: relative;

    display: flex;
    // flex-wrap: nowrap;
    // align-items: start;

    // hide scrollbar
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */

    &::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari, Opera*/
    }

    .card {
      width: 300px;
      // min-height: 80px;
      min-height: 100px;
      background-color: #fff;
      border-radius: 10px;
      display: inline-block;
      margin-right: 10px;

      flex-shrink: 0;

      // make linear-gradien (1: #02314A, 2: #144158, 3: #254F64), TIDAK patah-patah
      background: linear-gradient(60deg, #02314A 25%, #144158 50%, #254F64 75%, #365E70 100%);

      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

      &-body {
        overflow: hidden;
      }

      &.reminder {
        color: #fff;
        cursor: pointer;
      }

      .reminder {
        &-title {
          font-size: 1rem;
          font-weight: 600;
          margin-bottom: 5px;

          white-space: initial;
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
  //   height: calc(100vh - 340px);
  //   height: calc(100vh - 450px);
  overflow-y: auto;

  &__toolbar {
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    margin-left: 10px;
    margin-bottom: 10px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    select {
      padding: 5px 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      background-color: #fff;
      font-size: 0.875rem;
      font-weight: 500;
      color: #555;
      cursor: pointer;

      flex: 1;
      max-width: 110px;
    }
  }

  &__title {

    h3 {
      font-size: 0.85rem;
      font-weight: 500;
    }
  }

  .reminder {
    &-item {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      border-radius: 5px;
      background-color: #fff;
      margin-bottom: 20px;
      cursor: pointer;

      &__time {
        font-size: 1.25rem;
        font-weight: 600;
        margin-right: 20px;
        text-align: right;

        &__date {
          font-size: 0.875rem;
          font-weight: 500;
          color: #ccc;
        }
      }

      &__body {
        background-color: #F4F2E5;
        flex: 1;
        padding: 10px 20px;
        border-radius: 5px;
        position: relative;
        margin-left: 15px;
        transition: 0.3s all;

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
          font-size: 1.2rem;
          font-weight: 600;
          margin-bottom: 5px;
        }

        &__desc {
          font-size: 0.85rem;
          color: #365E70;
        }

        &__time-left {
          font-size: 0.875rem;
          font-weight: 500;
          color: #bbb;
        }
      }

      &:hover {
        .reminder-item__body {
          background-color: #F1EAC2;

          &__time-left {
            // color: #365E70;
          }
        }
      }

      &.passed {
        .reminder-item__time {
          color: #ccc;
        }

        .reminder-item__body {
          &__title {
            text-decoration: line-through;
            color: #ccc;
          }

          &__desc {
            text-decoration: line-through;
            color: #ccc;
          }

          &__time-left {
            text-decoration: line-through;
            color: #ccc;
          }
        }
      }
    }
  }
}
</style>

