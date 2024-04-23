import { QField, type ValidationRule } from 'quasar'

type valType = string | number | null

function isFormValid(inputRefs: (QField | undefined)[]) {
  if (!inputRefs) return false
  let hasError = false
  for (const input of inputRefs) {
    if (input) {
      input.validate()
      if (input.hasError) {
        hasError = true
      }
    }
  }
  return !hasError
}

function required(): ValidationRule {
  return (val: valType) => (val?.toString() || '').length > 0 || 'This field is required'
}

function requiredSingleOption(): ValidationRule {
  return (val: Array<valType>) => val.length > 0 || 'This field is required'
}

function minLength(min: number): ValidationRule {
  return (val: valType) =>
    (val?.toString() || '').length >= min || `This field must be at least ${min} characters long`
}

function maxLength(max: number): ValidationRule {
  return (val: valType) =>
    (val?.toString() || '').length <= max || `This field must be at most ${max} characters long`
}

function isNumber(): ValidationRule {
  return (val: valType) => !val || !isNaN(Number(val)) || 'This field must be a number'
}

function isEmail(): ValidationRule {
  return (val: valType) =>
    !val ||
    /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(val.toString()) ||
    'Email must be valid'
}

export { isFormValid, required, minLength, maxLength, isNumber, isEmail, requiredSingleOption }