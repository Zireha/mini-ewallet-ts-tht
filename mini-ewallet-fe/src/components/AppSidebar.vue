<script setup></script>

<template>
  <aside
    class="top-0 left-0 z-40 w-72 h-full transition-transform -translate-x-full sm:translate-x-0"
  >
    <div
      class="flex flex-col h-full bg-sky-900 justify-center items-center py-12 shadow-2xl"
    >
      <!-- Logo -->
      <a href="#" class="">
        <div class="flex gap-4 items-center">
          <i class="fa-solid fa-money-bill fa-2x text-white" aria-hidden="true"></i>
          <p class="text-white font-mono text-3xl">E Wallet</p>
        </div>
      </a>

      <!-- Menu Bar -->
      <nav class="flex-1 px-4 pt-16 space-y-5 justify-start">
        <RouterLink to="/"
        class="flex gap-2 items-center w-full px-4 py-2 rounded-full lg:text-base text-white hover:text-blue-950 hover:bg-sky-400">
          <i class="fa-solid fa-home"></i>
          <p>Home</p>
        </RouterLink>
      
        <RouterLink to="/transactions" class="flex gap-2 items-center w-full px-4 py-2 rounded-full lg:text-base text-white hover:text-blue-950 hover:bg-sky-400">
          <i class="fa-solid fa-history"></i>
          <p>Transaction History</p>
        </RouterLink>
      </nav>

      
      <a href="#"> </a>
    </div>
  </aside>
</template>
