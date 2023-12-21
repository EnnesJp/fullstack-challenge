<template>
  <q-table
    ref="tableRef"
    :loading="mountingPage"
    :rows="rows"
    :columns="columns"
    row-key="id"
    :hide-no-data="mountingPage"
    no-data-label="I didn't find anything for you"
    @request="request"
  >
    <template v-slot:body="props">
      <q-tr :props="props" v-if="!loading">
        <q-td key="description" :props="props">
          {{ props.row.description }}
        </q-td>
        <q-td class="number" key="date" :props="props">
          {{ props.row.date }}
        </q-td>
        <q-td class="number" key="amount" :props="props">
          {{ CurrencyHelper.formatCurrency(props.row.amount) }}
        </q-td>
        <q-td style="width: 85px;" key="actions" :props="props">
          <q-btn
            flat
            round
            size="md"
            class="q-mr-sm"
            @click="openEditDialog(props.row)"
          >
            <img src="src/assets/edit.svg" alt="Edit">
          </q-btn>
          <q-btn
            flat
            round
            size="md"
            class="q-mr-sm"
            @click="openDeleteDialog(props.row)"
          >
            <img src="src/assets/delete.svg" alt="Delete">
          </q-btn>
        </q-td>
      </q-tr>
      <RowsTableLoader v-else />
    </template>
    <template v-slot:loading>
      <SkeletonTableLoader />
    </template>
  </q-table>

  <q-dialog v-model="confirm" persistent>
    <q-card>
      <q-card-section class="row items-center">
        <span class="q-ml-sm">Do you really want to delete this expense?</span>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Cancel" color="negative" v-close-popup />
        <q-btn fill label="Delete" color="negative" @click="deleteExpense" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>

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
                v-model="actualRow.description"
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
                v-model="actualRow.amount"
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
              v-model="actualRow.date"
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
        <q-btn flat label="Edit" color="primary" @click="editExpense" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import SkeletonTableLoader from 'components/Base/SkeletonTableLoader.vue'
import RowsTableLoader from 'components/Base/RowsTableLoader.vue'
import CurrencyHelper from 'src/helpers/CurrencyHelper'
import expensesService from 'src/api/modules/expenses'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'stores/auth'

interface Props {
  columns: Array<object>
  rows: Array<object>
  pagination: {
    page: number
    rowsPerPage: number
    rowsNumber: number
  }
  loading: boolean
  mountingPage: boolean
  request: (props: {
    pagination: { page: number; rowsPerPage: number }
  }) => void
}

defineProps<Props>()
const $q = useQuasar()
const store = useAuthStore()

const { removeExpense, updateExpense } = expensesService()
const fixed = ref(false)
const tableRef = ref()
const confirm = ref(false)
const loadingModal = ref(false)
const actualRow = ref({
  id: 0,
  description: '',
  date: '',
  amount: '',
})

function openEditDialog(row: object) {
  const objectDate = row['date'].split('/');
  const date = `${objectDate[2]}-${objectDate[1]}-${objectDate[0]}`

  actualRow.value = {
    id: row['id'],
    description: row['description'],
    date: date,
    amount: row['amount'].replace('.', ','),
  }

  fixed.value = true
}

function editExpense() {
  loadingModal.value = true
  const { id, description, date, amount } = actualRow.value
  const payload = {
    description,
    date,
    amount: parseFloat(amount.replace('.', '').replace(',', '.')).toFixed(2),
    user_id: store.getUserId,
  }

  updateExpense(id, payload)
    .then((res) => {
      if (!res.error) {
        $q.notify({
          color: 'positive',
          position: 'bottom-right',
          message: res.content.message ?? 'Expense updated successfully',
        })
        tableRef.value.requestServerInteraction()
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
}

function openDeleteDialog(row: object) {
  confirm.value = true
  actualRow.value = row
}

function deleteExpense() {
  removeExpense(actualRow.value.id)
    .then((res) => {
      if (!res.error) {
        $q.notify({
          color: 'positive',
          position: 'bottom-right',
          message: res.content.message ?? 'Expense deleted successfully',
        })
        tableRef.value.requestServerInteraction()
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
      confirm.value = false
    })
}


onMounted(() => {
  tableRef.value.requestServerInteraction()
})
</script>
