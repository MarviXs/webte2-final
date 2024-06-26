<template>
  <PageLayout :title="t('users.create_users')">
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        :icon="mdiPlus"
        :label="t('users.create_user')"
        to="/users/create"
        unelevated
        no-caps
        size="15px"
      />
    </template>
    <template #default>
      <div class="shadow">
        <q-table
          :rows="users"
          :columns="columns"
          row-key="id"
          flat
          :loading="loadingUsers"
          :rows-per-page-options="[10, 20, 30]"
          :no-data-label="t('vote.data.user_data_label')"
          :loading-label="t('vote.data.user_loading_label')"
          :rows-per-page-label="t('vote.data.user_rows_label')"
        >
          <template #body-cell-actions="propsActions">
            <q-td auto-width :props="propsActions">
              <!-- Edit button -->
              <q-btn
                :icon="mdiPencil"
                color="gray-btn"
                flat
                round
                :to="`/users/${propsActions.row.id}/edit`"
              >
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]">
                  {{ t('users.edit_user') }}
                </q-tooltip>
              </q-btn>

              <!--Dropdown -->
              <q-btn :icon="mdiDotsVertical" color="gray-btn" flat round>
                <q-menu anchor="bottom right" self="top right">
                  <q-list class="text-grey-10">
                    <q-item v-close-popup clickable @click="deleteUser(propsActions.row.id)">
                      <div class="row items-center q-gutter-sm">
                        <q-icon color="grey-8" size="24px" :name="mdiTrashCan" />
                        <div>{{ t('questions.delete') }}</div>
                      </div>
                    </q-item>
                  </q-list>
                </q-menu>
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
import { mdiDotsVertical, mdiPencil, mdiTrashCan, mdiPlus } from '@quasar/extras/mdi-v7'
import type { User } from '@/models/User'
import UserService from '@/services/UserService'
import { useAuthStore } from '@/stores/auth-store'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'

const { t } = useI18n()
const users = ref<User[]>([])
let loadingUsers = ref(false)

const router = useRouter()

const authStore = useAuthStore()

function verifyRole() {
  if (authStore.role !== 'admin') {
    toast.error(t('toast.unauthorized'))
    router.push('/')
  }
}
verifyRole()

async function getAllUsers() {
  try {
    loadingUsers.value = true
    users.value = await UserService.getUsers()
  } catch (error) {
    console.error(error)
    toast.error(t('toast.users.get_error'))
  } finally {
    loadingUsers.value = false
  }
}
getAllUsers()

async function deleteUser(id: string) {
  try {
    await UserService.deleteUser(id)
    toast.success(t('toast.users.delete'))
    getAllUsers()
  } catch (error) {
    console.error(error)
    toast.error(t('toast.users.delete_error'))
  }
}

const columns: QTableProps['columns'] = [
  {
    name: 'id',
    label: 'Id',
    field: 'id',
    align: 'left',
    sortable: true
  },
  {
    name: 'email',
    label: 'Email',
    field: 'email',
    align: 'left',
    sortable: true
  },
  {
    name: 'first_name',
    label: t('users.first_name'),
    field: 'first_name',
    align: 'left',
    sortable: true
  },
  {
    name: 'last_name',
    label: t('users.last_name'),
    field: 'last_name',
    align: 'left',
    sortable: true
  },
  {
    name: 'role',
    label: t('users.roles.role'),
    field: 'role',
    align: 'left',
    format(val, row) {
      return row.role === 'admin' ? t('users.roles.admin') : t('users.roles.user')
    },
    sortable: true
  },
  {
    name: 'actions',
    label: t('columns.actions'),
    align: 'center',
    field: ''
  }
]
</script>

<style scoped></style>
