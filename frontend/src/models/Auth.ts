interface UserLogin {
  email: string
  password: string
}

interface UserRegister {
  email: string
  first_name: string
  last_name: string
  password: string
}

interface UserCreate {
  email: string
  first_name: string
  last_name: string
  password: string
  role: string
}
interface UserUpdate {
  email: string
  password: string
  role: string
  first_name: string
  last_name: string
}

interface AuthResponse {
  token: string
}

interface UserChangePassword {
  current_password: string
  new_password: string
}

export type { UserLogin, UserRegister, AuthResponse, UserChangePassword, UserCreate, UserUpdate}
