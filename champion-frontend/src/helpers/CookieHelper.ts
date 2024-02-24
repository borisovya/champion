
export const getFromCookie = (key: string): any => {
  const cookies = document.cookie.split("; ");
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].split("=");
    if (cookie[0] === key) {
      return cookie[1];
    }
  }
  return null;
}

  export const setCookie = (key: string, value: any) => {
    if (typeof value !== 'string') {
      value = JSON.stringify(value);
    }

    document.cookie = `${key}=${value}`;
  }

  export const deleteFromCookie = (key: string) => {
    setCookie(key, '');
  }

