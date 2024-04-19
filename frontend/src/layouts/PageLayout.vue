<template>
  <q-page class="main-padding">
    <div class="q-mb-lg">
      <div class="q-mb-md row items-center">
        <router-link
          v-if="previousRoute && previousTitle"
          class="main-text text-accent z-fab"
          :to="previousRoute"
        >
          <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -->
          {{ previousTitle }} >&nbsp;
        </router-link>
        <p v-if="title" class="main-text z-index">{{ props.title }}</p>
        <slot name="description" />
        <q-space></q-space>
        <div class="actions">
          <slot name="actions" />
        </div>
      </div>
      <slot />
    </div>
  </q-page>
</template>

<script setup lang="ts">
const props = defineProps({
  title: {
    type: String,
    required: false
  },
  previousTitle: {
    type: String,
    default: ''
  },
  previousRoute: {
    type: String,
    default: ''
  }
})
</script>

<style lang="scss" scoped>
.z-index {
  z-index: 1001;
}

.actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.main-padding {
  padding: 1rem 1rem;
}

@media screen and (min-width: 575px) {
  .main-padding {
    padding: 1rem 1rem;
  }
}

@media screen and (min-width: 600px) {
  .main-padding {
    padding: 1.5rem 2rem;
  }
}

@media screen and (min-width: 1024px) {
  .main-padding {
    padding: 2rem 4rem;
  }
}

@media screen and (min-width: 1440px) {
  .main-padding {
    padding: 4rem 6rem;
  }
}
</style>
