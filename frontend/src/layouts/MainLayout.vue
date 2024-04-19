<template>
  <q-layout view="lHh LpR lfr">
    <q-header class="bg-white text-secondary shadow">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          :icon="mdiMenu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
      </q-toolbar>
    </q-header>
    <q-drawer v-model="leftDrawerOpen" show-if-above class="shadow bg-white">
      <div class="column q-px-lg full-height">
        <div class="drawer-title">Final assignment</div>
        <div class="links">
          <side-menu-route to="/" :exact="true" label="Home" :icon="mdiHome" />
        </div>
      </div>
    </q-drawer>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { mdiHome, mdiMenu } from '@quasar/extras/mdi-v7'
import SideMenuRoute from '@/components/SideMenuRoute.vue'

const leftDrawerOpen = ref(false)

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>

<style lang="scss" scoped>
.drawer-title {
  font-size: 1.6rem;
  font-weight: 500;
  margin: 1.5rem 0;
  width: 100%;
  text-align: center;
  color: $secondary;
}

.top-title {
  font-size: 1.2rem;
  font-weight: 500;
  color: $secondary;
}

.top-buttons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.25rem;
}

</style>
