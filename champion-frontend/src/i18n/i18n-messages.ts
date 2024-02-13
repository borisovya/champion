import { createI18n } from 'vue-i18n'

export const i18n = createI18n({
  locale: 'ru',
  allowComposition: true,
  messages: {
    ru: {
      validations: {
        required: 'Поле обязательно для заполнения',
        minValue: 'Значение должно быть больше чем {minValue}',
        minLength: 'Длина должна быть не менее {minLength} символов',
        email: 'Введите корректный email',
        numeric: 'Поле должно содержать только цифры',
        sameAs: 'Значение должно совпадать со значением поля {field}'
      }
    },
    en: {
      validations: {
        required: 'The field is required.',
        minLength: 'The field must be at least {minLength} characters long.',
        minValue: 'The value must be greater than {minValue}.',
        email: 'Please enter a valid email.',
        numeric: 'Digits only'
      }
    }
  }
})
