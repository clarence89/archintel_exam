<script setup>
import { object, string } from "yup";
definePageMeta({
  layout: "",
});
const config = useRuntimeConfig()
const schema = object({
  username: string().required("Required"),
  password: string().min(6, "Must be at least 6 characters").required("Required"),
});

const state = reactive({
  username: "",
  password: "",
  error: "",
  loading: false,
});

async function onSubmit(event) {
  state.loading = true;
  state.error = "";
  event.preventDefault(); // Prevent default form submission

  const formdata = new FormData();
  formdata.append("username", state.username);
  formdata.append("password", state.password);

  const requestOptions = {
    method: "POST",
    body: formdata,
  };


  fetch(config.public.apiUrl +"/api/login.php/", requestOptions)
    .then((response) => {
      if (!response.ok) {
        state.loading = false;
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((result) => {
      if (result.error) {
        state.loading = false;
        state.error = result.message;
      } else {
        useCookie("token").value = result.token;
        useCookie("user").value = result.user;
        useCookie("session_expire").value = result.session_expire;
        useCookie("token").maxAge = result.session_expire;
        useCookie("user").maxAge = result.session_expire;
        useCookie("session_expire").maxAge = result.session_expire;
        useCookie("token").readonly = true;
        useCookie("user").readonly = true;
        useCookie("session_expire").readonly = true;

        navigateTo("/dashboard");
      }
    })
    .catch((error) => (state.error = String(error)));
}
</script>
<template>
  <div class="min-h-screen flex items-center justify-center bg-primaryColor">
    <div class="max-w-md w-full p-12 bg-gray-200 rounded-lg">
      <div class="w-full flex items-center justify-center mb-10">
        <img
          src="~assets/apple-touch-icon.png"
          alt="Site Icon"
          class="header-icon mx-auto"
        />
      </div>

      <!-- <h2 class="text-3xl font-bold text-center mb-6">Login</h2> -->

      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormGroup label="Username" name="username">
          <UInput v-model="state.username" type="username" required />
        </UFormGroup>

        <UFormGroup label="Password" name="password">
          <UInput v-model="state.password" type="password" required />
        </UFormGroup>
        <UAlert
          class="bg-red-500 text-white text-center"
          v-if="state.error"
          :title="state.error"
        />
        <div class="w-full text-center">
          <UButton class="px-10" type="submit" :loading="state.loading">Login</UButton>
        </div>
      </UForm>
    </div>
  </div>
</template>
