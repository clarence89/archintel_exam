export default defineNuxtRouteMiddleware((to, from) => {
    const token = useCookie('token').value || null;
    const user = useCookie('user').value || null;
    const sessionExpire = useCookie('session_expire').value || null;
    const currentTime = Math.floor(Date.now() / 1000);
    if (!token || !user || !sessionExpire || currentTime > sessionExpire) {
        if (to.path !== '/') {
            return navigateTo('/');
        }
    } else {
        if (to.path === '/') {
            return navigateTo('/dashboard');
        }
    }
})
