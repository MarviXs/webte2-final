enum UserRole {
  ADMIN = 'admin',
  USER = 'user'
}

interface User {
  id: string
  email: string
  role: UserRole
}

interface UserFull extends User {
  first_name: string
  last_name: string
}
interface UserRequest {
  email: string
  password: string
  role: UserRole
}

export type { User, UserFull, UserRole, UserRequest }
