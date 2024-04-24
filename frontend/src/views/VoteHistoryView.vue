<template>
  <PageLayout
    title="Vote Archive"
    previous-title="Results"
    :previous-route="`/questions/${route.params.code}/results`"
  >
    <template #default>
      <div class="shadow">
        <q-table
          :rows="closures"
          :columns="columns"
          row-key="id"
          flat
          :loading="loadingClosures"
          :rows-per-page-options="[10, 20, 30]"
          no-data-label="No vote closures found"
          loading-label="Loading vote archive..."
          rows-per-page-label="Vote closures per page"
        >
          <template #body-cell-actions="propsActions">
            <q-td auto-width :props="propsActions">
              <q-btn
                :icon="mdiChartBar"
                color="gray-btn"
                flat
                round
                :to="`/${route.params.code.toString()}/archive/${propsActions.row.id}`"
              >
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Results </q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </div>
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'
import type { QTableProps } from 'quasar'
import type { VoteClosure } from '@/models/VoteClosure'
import VoteService from '@/services/VoteService'
import { useRoute } from 'vue-router'
import { mdiChartBar } from '@quasar/extras/mdi-v7'

const route = useRoute()
const closures = ref<VoteClosure[]>([])

const loadingClosures = ref(false)

async function getClosures() {
  const questionCode = route.params.code as string
  try {
    loadingClosures.value = true
    closures.value = await VoteService.getQuestionClosures(questionCode)
  } catch (error) {
    console.error(error)
    toast.error('Failed to load question closures')
  } finally {
    loadingClosures.value = false
  }
}
getClosures()

const columns: QTableProps['columns'] = [
  {
    name: 'closed_at',
    label: 'Closed at',
    field: 'created_at',
    align: 'left',
    sortable: true,
    format(val) {
      return new Date(val * 1000).toLocaleString()
    }
  },
  {
    name: 'note',
    label: 'Note',
    field: 'note',
    align: 'left',
    sortable: true
  },
  {
    name: 'actions',
    label: 'Actions',
    align: 'center',
    field: ''
  }
]
</script>

<style scoped></style>
