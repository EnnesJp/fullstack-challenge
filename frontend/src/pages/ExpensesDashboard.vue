<template>
  <q-page class="flex column" padding>
    <div class="flex q-pa-md justify-between">
      <q-btn
        label="Add new Expense"
        style="
          background-color: #0093e9;
          color: #fff;
        "
        @click="fixed = true"
      />
      <span class="title">Expenses Dashboard</span>
    </div>
    <div class="q-pa-md">
      <Table
        :columns="columns"
        :rows="rows"
        :pagination="pagination"
        :loading="loading"
        :mountingPage="mountingPage"
        :request="request"
      />
    </div>

    <q-dialog v-model="fixed">
      <q-card>
        <q-card-section>
          <div class="text-h6">Add new Expense</div>
        </q-card-section>

        <q-separator />

        <q-card-section style="max-height: 50vh" class="scroll">
          <transition
            appear
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
          >
            <div>
              <div class="flex column">
                <q-input
                  v-model="form.description"
                  filled
                  class="q-mr-md"
                  style="width: 100%;"
                  type="text"
                  label="Description"
                  lazy-rules
                  :rules="[
                    (val) => (val && val.length > 0) || 'Please type something',
                    (val) => (val.length < 191) || 'The description must be less than 191 characters long'
                  ]"
                />

                <q-field
                  filled
                  class="q-mb-md q-mr-md"
                  v-model="form.amount"
                  label="Amount"
                >
                  <template v-slot:control="{ id, floatingLabel, modelValue, emitValue }">
                    <input
                      :id="id"
                      class="q-field__input text-right"
                      :value="modelValue"
                      @change="e => emitValue(e.target?.value)"
                      v-money="{
                        decimal: ',',
                        thousands: '.',
                        prefix: 'R$ ',
                        suffix: ' #',
                        precision: 2,
                        masked: false
                      }"
                      v-show="floatingLabel"
                    >
                  </template>
                </q-field>
              </div>

              <q-date
                label="Date"
                v-model="form.date"
                landscape
                mask="YYYY-MM-DD"
              />
            </div>
          </transition>
          <q-inner-loading :showing="loadingModal">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-card-section>

        <q-separator />

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="negative" v-close-popup />
          <q-btn flat label="Add" color="positive" @click="create" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import expensesService from 'src/api/modules/expenses'
import { useQuasar } from 'quasar'
import Table from 'components/Table.vue'
import { useAuthStore } from 'stores/auth'

const { getExpenses, createExpense } = expensesService()
const store = useAuthStore()

const $q = useQuasar()
const columns = ref([
  { name: 'description', required: true, label: 'Description', align: 'left', field: 'description',  sortable: true},
  { name: 'date', align: 'center', label: 'Date', field: 'date', sortable: true},
  { name: 'amount', label: 'Amount', field: 'amount'},
  { name: 'actions', required: true, label: 'Actions', align: 'center', field: 'actions' },
])
const loading = ref(true)
const loadingModal = ref(false)
const mountingPage = ref(true)
const rows = ref([])
const pagination = reactive({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})
const fixed = ref(false)
const form = ref({
  description: '',
  date: '',
  amount: '0,00',
})

function create() {
  loadingModal.value = true
  createExpense({
    description: form.value.description,
    date: form.value.date,
    amount: parseFloat(form.value.amount.replace('.', '').replace(',', '.')).toFixed(2),
    user_id: store.getUserId,
  })
    .then((res) => {
      if (!res.error) {
        $q.notify({
          color: 'positive',
          position: 'bottom-right',
          message: res.content.message ?? 'Expense created successfully',
        })
        request({ pagination: { page: 1, rowsPerPage: 10 } })
      } else {
        $q.notify({
          type: 'negative',
          position: 'bottom-right',
          message: res.error.message ?? 'Something went wrong. Please try again.'
        })
      }
    })
    .catch((error) => {
      $q.notify({
        type: 'negative',
        position: 'bottom-right',
        message: error.message ?? 'Something went wrong. Please try again.'
      })
    })
    .finally(() => {
      loadingModal.value = false
      fixed.value = false
    })

  form.value = {
    description: '',
    date: '',
    amount: '0,00',
  }
}

function request(props: {
  pagination: { page: number; rowsPerPage: number }
}) {
  const { page, rowsPerPage } = props.pagination
  loading.value = true

  getExpenses({
    page,
    page_size: rowsPerPage,
  })
    .then((response) => {
      rows.value = response.content.data
    })
    .catch((error) => {
      $q.notify({
        type: 'negative',
        position: 'bottom-right',
        message: error.message ?? 'Email or password is invalid'
      })
    })
    .finally(() => {
      loading.value = false
      mountingPage.value = false
    })
}
</script>

<style lang="scss" scoped>
.title {
  color: #80d0c7;
  text-align: right;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: 24px;
}
</style>
