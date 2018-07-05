module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:vue/recommended'
  ],
  rules: {
    // override/add rules settings here, such as:
    // 'vue/no-unused-vars': 'error'
  },
  env: {
    amd: true,
    jest: true
  },
  parserOptions: {
    ecmaVersion: 8
  }
}
