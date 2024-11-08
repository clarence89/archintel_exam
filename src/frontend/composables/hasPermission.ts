export const hasPermission = (search_permission: string): boolean => {
  const user = useCookie("user").value as { permissions: { permission_value: string; permission_name: string }[] } | null;

  const permissions = user?.permissions ?? [];

  return permissions.some(({ permission_value }) => permission_value === search_permission);
};
