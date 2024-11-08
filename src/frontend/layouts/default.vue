<!-- layouts/default.vue -->
<template>
  <div class="flex flex-col min-h-screen">
    <Navbar />
    <div class="container mx-auto flex m-10 p-4 flex-grow">
      <div class="px-4 w-full">
        <slot />
      </div>
    </div>
    <Footer />
  </div>
</template>
<script setup lang="ts">
  onMounted(() => {
    if (process.client) {
      const colorMode = localStorage.getItem("nuxt-color-mode");

      if (colorMode === "dark") {
        localStorage.setItem("nuxt-color-mode", "light");
        document.body.setAttribute("data-theme", "light");
      }
    }
  });

  const links = [
    {
      label: "Profile",
      avatar: {
        src: "https://avatars.githubusercontent.com/u/739984?v=4",
      },
      badge: 100,
    },
    {
      label: "Installation",
      icon: "i-heroicons-home",
      to: "/getting-started/installation",
    },
    {
      label: "Vertical Navigation",
      icon: "i-heroicons-chart-bar",
      to: "/components/vertical-navigation",
    },
    {
      label: "Command Palette",
      icon: "i-heroicons-command-line",
      to: "/components/command-palette",
    },
  ];
</script>
