<template>
  <nav class="bg-blue-900 shadow-lg" style="z-index: 500">
    <div class="container mx-auto flex justify-between items-center p-2">
      <div class="flex items-center text-white font-bold text-xl">
        <img
          class="w-32 h-auto mr-4"
          src="~assets/archintel-logo.gif"
          alt="MMWGH Logo"
          srcset=""
        />
        <!-- <NuxtLink to="/">{{ appName }}</NuxtLink> -->
      </div>
      <div class="hidden md:flex space-x-4">
        <NuxtLink
               v-if="hasPermission('administrator') || hasPermission('view_articles')"
          to="/dashboard"
          class="text-gray-300 hover:text-white"
          :class="{ 'text-white': $route.path === '/dashboard' }"
          >Dashboard</NuxtLink
        >
        <NuxtLink
               v-if="hasPermission('administrator') || hasPermission('view_articles')"
          to="/all_media"
          class="text-gray-300 hover:text-white"
          :class="{ 'text-white': $route.path === '/all_media' }"
          >All Media</NuxtLink
        >
        <div
          class="relative"
          v-if="
            hasPermission('administrator') ||
            hasPermission('view_affiliation') ||
            hasPermission('view_companies') ||
            hasPermission('view_role') ||
            hasPermission('view_users')
          "
          @click="showManagePanel = !showManagePanel"
        >
          <button class="flex items-center text-white">
            <span class="mr-2">Manage</span>
            <span>
              <svg
                v-if="!showManagePanel"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 12.707a1 1 0 011.414 0L10 9.414l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0l-4 4a1 1 0 000 1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </span>
          </button>

          <div
            v-if="showManagePanel"
            class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg"
          >
            <NuxtLink
              v-if="hasPermission('administrator') || hasPermission('view_companies')"
              to="/companies"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              >Companies</NuxtLink
            >
            <NuxtLink
              v-if="hasPermission('administrator') || hasPermission('view_affiliation')"
              to="/affiliation"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              >Affiliation</NuxtLink
            >
            <NuxtLink
              v-if="hasPermission('administrator') || hasPermission('view_role')"
              to="/roles"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              >Roles</NuxtLink
            >
            <NuxtLink
              v-if="hasPermission('administrator') || hasPermission('view_users')"
              to="/users"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              >Users</NuxtLink
            >
          </div>
        </div>
        <div class="relative" @click="showDropdown = !showDropdown">
          <button class="flex items-center text-white">
            <span class="mr-2"
              >{{ user.firstname }} {{ user.middlename }} {{ user.lastname }}
              {{ user.affiliation ? ` - (${user.affiliation})` : "" }}</span
            >
            <span>
              <svg
                v-if="!showDropdown"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 12.707a1 1 0 011.414 0L10 9.414l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0l-4 4a1 1 0 000 1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </span>
          </button>

          <div
            v-if="showDropdown"
            class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg"
          >
            <NuxtLink
              to="/profile/change_password"
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              >Change Password</NuxtLink
            >
            <NuxtLink
              class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
              @click="logout()"
              >Logout</NuxtLink
            >
          </div>
        </div>
      </div>
      <div class="md:hidden">
        <button @click="toggleMenu" class="text-white focus:outline-none">
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h7"
            ></path>
          </svg>
        </button>
      </div>
    </div>
    <div v-if="isMenuOpen" class="md:hidden">
      <NuxtLink
             v-if="hasPermission('administrator') || hasPermission('view_articles')"
        to="/dashboard"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/dashboard' }"
        >Dashboard</NuxtLink
      >
      <NuxtLink
       v-if="hasPermission('administrator') || hasPermission('view_articles')"
        to="/all_media"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/all_media' }"
        >All Media</NuxtLink
      >
      <NuxtLink
        v-if="hasPermission('administrator') || hasPermission('view_companies')"
        to="/companies"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/companies' }"
        >Companies</NuxtLink
      >
      <NuxtLink
        v-if="hasPermission('administrator') || hasPermission('view_affiliation')"
        to="/affiliation"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/affiliation' }"
        >Affiliation</NuxtLink
      >
      <NuxtLink
        v-if="hasPermission('administrator') || hasPermission('view_role')"
        to="/roles"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/roles' }"
        >Roles</NuxtLink
      >
      <NuxtLink
        v-if="hasPermission('administrator') || hasPermission('view_users')"
        to="/users"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/users' }"
        >Users</NuxtLink
      >
      <NuxtLink
        to="/services"
        class="block text-gray-300 hover:text-white p-2"
        :class="{ 'bg-gray-700': $route.path === '/services' }"
        >Services</NuxtLink
      >
      <NuxtLink @click="logout()" class="block text-gray-300 hover:text-white p-2"
        >Logout</NuxtLink
      >
    </div>
  </nav>
</template>

<script setup>
import { ref } from "vue";
const isMenuOpen = ref(false);
const showDropdown = ref(false);
const showManagePanel = ref(false);
const dropdownOpen = ref(false);
const user = useCookie("user").value;
const config = useRuntimeConfig();
const appName = config.public.appName;
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
function toggleDropdown() {
  dropdownOpen.value = !dropdownOpen.value;
}
function logout() {
  useCookie("token").value = null;
  useCookie("user").value = null;
  useCookie("session_expire").value = null;
  navigateTo("/");
}
</script>

<style scoped>
/* Optional: Add custom styles here */
</style>
