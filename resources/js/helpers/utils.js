import { inject } from "vue"

const getErrorMessage = (errors) => {
  if (errors?.length > 0) {
    return errors.join(', ') // join all errors
  }
  return ''
}

const mapErrorMessage = (errors = []) => {
  const resultErrors = {}
  for (const key in errors) {
    if (Object.hasOwnProperty.call(errors, key)) {
      const error = errors[key]
      if (error instanceof Array) {
        resultErrors[key] = getErrorMessage(error)
        continue
      }
    }
  }
  // console.log('resultErrors', resultErrors)
  return resultErrors
}

export {
  getErrorMessage,
  mapErrorMessage
}
