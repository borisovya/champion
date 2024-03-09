/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  extends: [
    'plugin:vue/vue3-essential',
    'eslint:recommended',
    '@vue/typescript/recommended',
  ],

  ignorePatterns: ['dist', '.eslintrc.cjs'],
  parser: "vue-eslint-parser",
  parserOptions: {
    "parser": "@typescript-eslint/parser",
  },

  plugins: [
    "vue",
    "@typescript-eslint"
  ],
}
