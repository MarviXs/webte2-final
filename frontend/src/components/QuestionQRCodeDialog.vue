<template>
  <q-dialog v-model="dialogOpen">
    <q-card>
      <q-card-section class="text-h6 text-center q-mb-none">QR Code</q-card-section>
      <q-card-section class="q-px-lg">
        <div class="text-center flex items-center justify-center">
          <div class="qrCode" ref="QRCodeRef"></div>
        </div>
      </q-card-section>

      <q-card-section class="q-mb-md q-px-sm">
        <div class="q-mb-lg q-px-sm">
          <q-badge color="primary">Resolution: {{ resolution }}px</q-badge>
          <q-slider
            v-model="resolution"
            :min="100"
            :max="1000"
            :step="10"
            color="primary"
            dense
          />
        </div>

        <q-select
          v-model="extension"
          :options="extensionOptions"
          label="t('questions.owner.format')"
          outlined
          mapOptions
          emitValue
          dense
        />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="t('questions.owner.cancel')" color="primary" />
        <q-btn unelevated label="t('questions.owner.download')" color="primary" :icon="mdiDownload" @click="download" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { mdiDownload } from '@quasar/extras/mdi-v7'
import QRCodeStyling, { type DrawType, type FileExtension } from 'qr-code-styling'
import { watch } from 'vue'
import { nextTick } from 'vue'

const props = defineProps({
  questionCode: {
    type: String,
    required: true
  }
})

const dialogOpen = defineModel({
  type: Boolean,
  default: false
})

const qrCodeValue = computed(() => {
  return `${import.meta.env.VITE_APP_URL}${props.questionCode}`
})

const extension = ref<FileExtension>('png')
const extensionOptions = [
  { label: "t('questions.owner.PNG')", value: 'png' },
  { label: "t('questions.owner.SVG')", value: 'svg' }
]

const resolution = ref(500)

const QRCodeRef = ref<HTMLElement>()
const QRCodeStylingInstance = ref<QRCodeStyling>()
const QRCodeStyle = computed(() => {
  return {
    data: qrCodeValue.value,
    type: 'svg' as DrawType,
    width: 250,
    height: 250,
    margin: 0
  }
})

async function renderQR() {
  QRCodeStylingInstance.value = new QRCodeStyling(QRCodeStyle.value)
  QRCodeStylingInstance.value.append(QRCodeRef.value)
}

function download() {
  const newStyling = QRCodeStyle.value

  newStyling.width = resolution.value
  newStyling.height = resolution.value

  const style = new QRCodeStyling(newStyling)

  style.download({ name: 'qrcode', extension: extension.value })
}

watch(dialogOpen, async (newVal) => {
  if (newVal) {
    await nextTick()
    renderQR()
    resolution.value = 500
  }
})
</script>
