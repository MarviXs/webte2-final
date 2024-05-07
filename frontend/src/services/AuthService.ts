
import type { AuthResponse, UserLogin, UserRegister, UserChangePassword } from '@/models/Auth';
import type { User } from '@/models/User';
import { api } from '@/utils/api'

class AuthService {
  async login(user: UserLogin): Promise<AuthResponse> {
    return await api('/auth/login', {
      method: 'POST',
      body: user
    })
  }

  async register(user: UserRegister): Promise<AuthResponse> {
    return await api('/auth/register', {
      method: 'POST',
      body: user
    })
  }

  async changePassword(user: UserChangePassword): Promise<void> {
    return await api('/auth/change-password', {
      method: 'POST',
      body: user
    })
  }

  async logout(): Promise<void> {
    return await api('/auth/logout', { method: 'POST' })
  }

  async getUser(): Promise<User> {
    return await api('/auth/me', { method: 'GET' })
  }
}

export default new AuthService()