export enum Roles {
  SUPER_ADMIN = 'ROLE_SUPER_ADMIN',
  ADMIN = 'ROLE_ADMIN',
  USER = 'ROLE_USER'
}

export const getRoleName = (role: Roles) => {
  switch (role) {
    case Roles.SUPER_ADMIN:
      return 'Главный администратор'
    case Roles.ADMIN:
      return 'Администратор'
    default:
      return 'Партнер'
  }
}

// export const roleOptions = ref([
//   { name: 'Главный администратор', code: Roles.SUPER_ADMIN },
//   { name: 'Администратор', code: Roles.ADMIN },
//   { name: 'Партнер', code: Roles.USER },
// ]);
