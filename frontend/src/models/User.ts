enum UserRole {
  ADMIN = 'admin',
  USER = 'user'
}

interface User {
  id: string
  email: string
  role: UserRole
}

export type { User, UserRole }
