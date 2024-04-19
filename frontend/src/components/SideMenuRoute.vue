<template>
  <router-link :to="to" :class="{ active: subIsActive() }">
    <SideMenuButton class="side-menu-btn" :label="label" :icon="icon" />
  </router-link>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import SideMenuButton from './SideMenuButton.vue'

const route = useRoute()
const props = defineProps({
  label: String,
  icon: String,
  to: {
    type: String,
    default: '/'
  }
})

const subIsActive = () => {
  if (props.to === '/' && route.path !== '/') {
    return false
  }

  const paths = Array.isArray(props.to) ? props.to : [props.to]
  return paths.some((path) => route.path.indexOf(path) === 0)
}
</script>

<style lang="scss" scoped>
.active {
  .side-menu-btn {
    background-color: #edf1fc;
    color: $primary;
  }
}
</style>
