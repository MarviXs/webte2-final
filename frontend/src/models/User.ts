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
interface UserUpdate {
  email: string
  password: string
  role: UserRole
  first_name: string
  last_name: string
}
interface UserCreate {
  email: string
  password: string
  role: UserRole
  first_name: string
  last_name: string
}
export type { User, UserFull, UserRequest, UserUpdate, UserCreate }
export { UserRole }
