<template>
  <div class="container-dashboard q-mb-lg">
    <q-tabs
      class="text-grey"
      active-color="primary"
      inline-label
      indicator-color="primary"
      align="justify"
      v-model="tab"
    >
      <q-tab name="list" label="List" no-caps :icon="mdiFormatListBulleted" />
      <q-tab
        v-if="voteResult.question_type === QuestionType.SINGLE_CHOICE"
        name="pie_chart"
        label="Graph"
        no-caps
        :icon="mdiChartPie"
      />
      <q-tab
        v-if="voteResult.question_type === QuestionType.MULTIPLE_CHOICE"
        name="chart_bar"
        label="Graph"
        no-caps
        :icon="mdiChartBar"
      />
      <q-tab
        v-if="voteResult.question_type === QuestionType.OPEN"
        name="cloud"
        label="Word Cloud"
        no-caps
        :icon="mdiCloud"
      />
    </q-tabs>
    <q-tab-panels v-model="tab" animated class="border-top">
      <q-tab-panel name="list" class="q-pa-none">
        <q-table
          :rows="voteResult.answers"
          flat
          :columns="columns"
          row-key="answer"
          :rows-per-page-options="[5, 10, 20]"
          hide-bottom
          no-data-label="No results found"
          loading-label="Loading results..."
        >
        </q-table>
      </q-tab-panel>

      <q-tab-panel name="pie_chart">
        <div class="row justify-center">
          <apexchart
            type="pie"
            width="100%"
            class="full-width"
            height="350"
            :options="pieChartOptions"
            :series="pieSeries"
          ></apexchart>
        </div>
      </q-tab-panel>

      <q-tab-panel name="chart_bar">
        <div class="row justify-center">
          <apexchart
            type="bar"
            class="full-width"
            width="100%"
            height="400"
            :options="barChartOptions"
            :series="barSeries"
          ></apexchart>
        </div>
      </q-tab-panel>

      <q-tab-panel name="cloud">
        <div v-for="result in voteResult.answers" :key="result.answer">
          {{ result.answer }}: {{ result.count }}
        </div>
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script setup lang="ts">
import type { VoteResult } from '@/models/VoteResult'
import { mdiChartBar, mdiChartPie, mdiCloud, mdiFormatListBulleted } from '@quasar/extras/mdi-v7'
import { ref, type PropType } from 'vue'
import type { QTableProps } from 'quasar'
import { QuestionType } from '@/models/Question'
import { computed } from 'vue'

const props = defineProps({
  voteResult: {
    type: Object as PropType<VoteResult>,
    required: true
  }
})
const tab = ref<string>('list')
if(props.voteResult.question_type === QuestionType.SINGLE_CHOICE) {
  tab.value = 'pie_chart'
} else if(props.voteResult.question_type === QuestionType.MULTIPLE_CHOICE) {
  tab.value = 'chart_bar'
}


const pieSeries = computed(() => props.voteResult.answers.map((answer) => answer.count))
const pieChartOptions = ref({
  labels: props.voteResult.answers.map((answer) => answer.answer),
  legend: {
    position: 'bottom'
  }
})

const barSeries = computed(() => [
  {
    name: 'Votes',
    data: props.voteResult.answers.map((answer) => answer.count)
  }
])
const barChartOptions = ref({
  labels: props.voteResult.answers.map((answer) => answer.answer),
  yaxis: {
    title: {
      text: 'Votes',
      style: {
        fontSize: '14px',
        fontFamily: 'Inter',
        fontWeight: 'bold'
      }
    }
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '50%',
      endingShape: 'rounded'
    }
  },
  legend: {
    position: 'bottom'
  }
})

const columns: QTableProps['columns'] = [
  {
    name: 'answer',
    label: 'Answer',
    field: 'answer',
    align: 'left',
    sortable: true
  },
  {
    name: 'count',
    label: 'Count',
    field: 'count',
    align: 'left',
    sortable: true
  }
]
</script>

<style lang="scss" scoped>
.border-top {
  border-top: 1px solid #e0e0e0;
}
</style>