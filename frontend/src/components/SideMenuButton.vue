<template>
  <q-btn
    no-caps
    flat
    :label="label"
    align="left"
    class="menu-btn q-my-xs"
    :icon="icon"
    size="15px"
    padding="10px"
  />
</template>

<script setup lang="ts">
defineProps({
  label: String,
  icon: String
})
</script>

<style lang="scss" scoped>
.menu-btn {
  width: 100%;
  border-radius: 12px;
  font-weight: 500;
  color: #8296bd;
  i {
    color: #9eb3d3;
  }
}
</style>
