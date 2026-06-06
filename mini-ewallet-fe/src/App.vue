<script setup>
import AppBar from './components/AppBar.vue'
import AppSidebar from './components/AppSidebar.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-50">
      <!-- TODO: temporary, don't forget to change back -->
    <template v-if="$route.path !== '/login'">
      <AppSidebar />

      <!-- main area -->

      <div class="flex flex-1 flex-col overflow-hidden">
        <AppBar />

        <!-- page -->
        <main class="flex-1 overflow-y-auto p-6">
          <RouterView />
        </main>
      </div>
    </template>
    
    <template v-else>
      <RouterView />
    </template>
  </div>
</template>

<style scoped></style>
