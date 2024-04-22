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

export type { UserLogin, UserRegister, AuthResponse }
