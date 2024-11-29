export function handleAdminAccess(user, to, next) {
    if (to.meta.requiresAuth) {
        next('/');
    } else {
        next();
    }
}
