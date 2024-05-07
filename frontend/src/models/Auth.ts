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

interface AuthResponse {
  token: string
}

interface UserChangePassword {
  current_password: string
  new_password: string
}

export type { UserLogin, UserRegister, AuthResponse, UserChangePassword }
