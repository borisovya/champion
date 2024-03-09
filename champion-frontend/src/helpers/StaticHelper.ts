export const asset = (path: string): string => {
  return import.meta.env.VITE_EXTERNAL_URL + path
}
