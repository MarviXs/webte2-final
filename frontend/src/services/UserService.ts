import type { User, UserRequest, UserUpdate, UserCreate, UserFull } from '@/models/User'
import { api } from '@/utils/api'

class UserService {
  async getUsers(): Promise<User[]> {
    return await api('/admin/users', {
      method: 'GET',
    })
  }

  async getUser(id: string): Promise<UserFull> {
    return await api(`/admin/users/${id}`, {
      method: 'GET',
    })
  }

  async createUser(question: UserCreate): Promise<User> {
    return await api('/admin/users', {
      method: 'POST',
      body: question,
    })
  }

  async updateUser(id: string, question: UserUpdate): Promise<User> {
    return await api(`/admin/users/${id}`, {
      method: 'PUT',
      body: question,
    })
  }

  async deleteUser(id: string): Promise<void> {
    await api(`/admin/users/${id}`, {
      method: 'DELETE',
    })
  }
}

export default new UserService()