<template>
  <q-layout view="lHh LpR lfr">
    <q-header class="bg-white text-secondary shadow">
      <q-toolbar>
        <q-btn flat dense round :icon="mdiMenu" :aria-label="t('main.menu')" @click="toggleLeftDrawer" />
      </q-toolbar>
    </q-header>
    <q-drawer v-model="leftDrawerOpen" show-if-above class="shadow bg-white">
      <div class="column q-px-lg full-height">
        <div class="drawer-title">Final assignment</div>
        <div class="links">
          <side-menu-route to="/" :exact="true" :label="t('main.vote')" :icon="mdiTextBoxEditOutline" />
          <side-menu-route to="/guide" :exact="true" :label="t('main.guide')" :icon="mdiBookOutline" />
          <div v-if="authStore.isAuthenticated">
            <side-menu-route to="/questions" :label="t('main.questions')" :icon="mdiChatQuestionOutline" />
          </div>
        </div>
        <q-separator class="q-my-md"></q-separator>
        <div v-if="!authStore.isAuthenticated">
          <side-menu-route to="/login" :label="t('main.register.login')" :icon="mdiAccount" />
          <side-menu-route to="/register" :label="t('main.register.register')" :icon="mdiAccountPlus" />
        </div>
        <div v-else>
          <side-menu-route to="/account" :label="t('main.register.account')" :icon="mdiAccount" />
          <side-menu-button :label="t('main.register.logout')" :icon="mdiLogout" @click="authStore.logout" />
        </div>
        <q-space></q-space>
        <LanguageSelect class="q-mx-md q-mb-md"></LanguageSelect>
      </div>
    </q-drawer>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {
  mdiAccount,
  mdiAccountPlus,
  mdiBookOutline,
  mdiChatQuestionOutline,
  mdiLogout,
  mdiMenu,
  mdiTextBoxEditOutline
} from '@quasar/extras/mdi-v7'
import SideMenuRoute from '@/components/SideMenuRoute.vue'
import SideMenuButton from '@/components/SideMenuButton.vue'
import { useAuthStore } from '@/stores/auth-store'
import LanguageSelect from '@/components/LanguageSelect.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const leftDrawerOpen = ref(false)

const authStore = useAuthStore()

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
