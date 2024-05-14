<template>
    <PageLayout
    :title="t('users.create_user')"
      :previous-title="t('users.previouse_title')"
      previous-route="/users"
      class="full-width"
      max-width="900px"
    >
      <template #actions>
        <q-btn
          class="shadow"
          color="primary"
          :label="t('questions.create')"
          @click="createUser"
          :loading="creatingUser"
          type="submit"
          unelevated
          size="15px"
        />
      </template>
      <div>
        <UserForm ref="userForm" v-model:user="user" />
      </div>
    </PageLayout>
  </template>
  
  <script setup lang="ts">
  import UserForm from '@/components/UserForm.vue'
  import PageLayout from '@/layouts/PageLayout.vue'
  import { type UserCreate, UserRole } from '@/models/User'
  import UserService from '@/services/UserService'
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { toast } from 'vue3-toastify'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n()
  
  const router = useRouter()

  const user = ref<UserCreate>({
    email: '',
    password: '',
    role: UserRole.USER,
    first_name: '',
    last_name: ''
  })

  
  const userForm = ref()
  const creatingUser = ref(false)
  async function createUser() {
    if ((await userForm.value.validate()) === false) {
      return
    }
    if (!user.value) {
      return
    }
  
    creatingUser.value = true
    try {
      const createdUser = await UserService.createUser(user.value)
      console.log(createdUser)
  
      toast.success(t('toast.users.create'))
      router.push('/users')
    } catch (error) {
      toast.error(t('toast.users.create_error'))
    } finally {
        creatingUser.value = false
    }
  }
  </script>
  