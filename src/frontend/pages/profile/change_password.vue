<template>
  <div class="flex items-center justify-center">
    <div class="max-w-md w-full p-12 bg-gray-200 rounded-lg">
      <h2 class="text-3xl font-bold text-center mb-6">Change Password</h2>
      <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
        <UFormGroup label="Current Password" name="current_password">
          <UInput v-model="state.current_password" type="password" required />
        </UFormGroup>

        <UFormGroup label="New Password" name="new_password">
          <UInput v-model="state.new_password" type="password" required />
        </UFormGroup>

        <UFormGroup label="Confirm New Password" name="confirm_password">
          <UInput v-model="state.confirm_password" type="password" required />
        </UFormGroup>

        <UAlert
          class="bg-red-500 text-white text-center"
          v-if="state.error"
          :title="state.error"
          style="z-index: -999"
        />
        <div class="w-full text-center">
          <UButton class="px-10" type="submit" :loading="state.loading"
            >Change Password</UButton
          >
        </div>
      </UForm>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { object, string, ref } from "yup";

const schema = object().shape({
  current_password: string().required("Required"),
  new_password: string().min(8, "Must be at least 8 characters").required("Required"),
  confirm_password: string()
    .oneOf([ref("new_password"), null], "Passwords must match")
    .required("Required"),
});

const state = reactive({
  current_password: "",
  new_password: "",
  confirm_password: "",
  error: "",
  loading: false,
});

async function onSubmit(values) {
  state.loading = true;
  state.error = "";
  let form = new FormData();
  form.append("current_password", state.current_password);
  form.append("new_password", state.new_password);
  form.append("confirm_password", state.confirm_password);

  try {
    // Call your API to change password
    const response = await useApiFetch("/api/profile/change_password.php/", {
      method: "POST",
      body: form,
    });
    if (!response.ok) {
      throw new Error(await response.text());
    }
  } catch (error) {
    state.error = error.message;
  } finally {
    state.loading = false;
    navigateTo("/dashboard")
  }
}
</script>
