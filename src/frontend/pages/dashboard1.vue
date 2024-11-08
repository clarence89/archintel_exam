<template>
  <div>
    <h1>Dashboard</h1>
    {{ useCookie("username").value }}
    <UInput v-model="state.username" />
  </div>
</template>

<script setup lang="ts">

const state = reactive({
  username: '',
});
watch(
  () => state.username,
  (newValue) => {
    useCookie("username").value = newValue;
  })
useHead({
  titleTemplate: (titleChunk) => {
    const title_desc = "Dashboard";
    return titleChunk ? `${titleChunk} - ${title_desc}` : title_desc;
  },
});
</script>
<style></style>
