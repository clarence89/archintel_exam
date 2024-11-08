export const useApiFetch = async (endpoint: string, options = {}): Promise<Response> => {
  const token_cookie = useCookie("token").value || null;
  const user = useCookie("user").value || null;
  const sessionExpire = useCookie("session_expire").value || null;
  const currentTime = Math.floor(Date.now() / 1000);

  if (!token_cookie || !user || !sessionExpire || Number(currentTime) > Number(sessionExpire)) {
    navigateTo("/");
    return {} as Promise<Response>;
  }

  const myHeaders = new Headers();
  const token = useCookie("token").value || "";

  if (token) {
    myHeaders.append("Authorization", `Bearer ${token}`);
  }

  const config = useRuntimeConfig();
  const url = `${config.public.apiUrl}${endpoint}`;
  return await fetch(url, { headers: myHeaders, ...options });
};
