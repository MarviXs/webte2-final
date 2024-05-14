<template>
    <PageLayout
      :title="t('users.edit_user')"
      previous-title="Users"
      previous-route="/users"
      class="full-width"
      max-width="900px"
    >
      <template #actions>
        <q-btn
          class="shadow"
          color="primary"
          :label="t('questions.update')"
          @click="updateUser"
          :loading="updatingUser"
          type="submit"
          unelevated
          size="15px"
        />
      </template>
      <div v-if="user">
        <UserUpdateForm ref="userForm" v-model:user="user" />
      </div>
    </PageLayout>
  </template>
  
  <script setup lang="ts">
  import UserUpdateForm from '@/components/UserUpdateForm.vue'
  import PageLayout from '@/layouts/PageLayout.vue'
  import type{ User, UserUpdate } from '@/models/User'
  import UserService from '@/services/UserService'
  import { ref } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { toast } from 'vue3-toastify'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n()
  const router = useRouter()
  const route = useRoute()
  const userId = ref('')
  const user = ref<UserUpdate>()
  const currUser = ref<User>()
  async function getUser() {
    userId.value = route.params.id.toString()
    try {
        const userData = await UserService.getUser(userId.value)
        currUser.value = userData
        user.value = {
            email: userData.email,
            password: '',
            role: userData.role,
            first_name: userData.first_name,
            last_name: userData.last_name,
        }
    } catch (error) {
        toast.error('Failed to fetch user')
        router.push('/users')
    }
  }
  getUser()
  
  const userForm = ref()
  const updatingUser = ref(false)
  async function updateUser() {
    if ((await userForm.value.validate()) === false) {
      return
    }
    if (!user.value) {
      return
    }
  
    updatingUser.value = true
    try {
      const editedUser = await UserService.updateUser(userId.value, user.value)
      console.log(editedUser)
  
      toast.success('User updated successfully')
      router.push('/users')
    } catch (error) {
      toast.error('Failed to update user')
    } finally {
        updatingUser.value = false
    }
  }
  </script>
  