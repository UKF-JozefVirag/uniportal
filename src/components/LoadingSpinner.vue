<template>
  <div class="spinner-container">
    <svg width="48" height="48" stroke="#000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <g class="spinner">
        <circle cx="12" cy="12" r="9.5" fill="none" stroke-width="3"></circle>
      </g>
    </svg>
  </div>
</template>

<script setup>
</script>

<style scoped>
.spinner-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  transform-origin: center;
  animation: spinner 2s linear infinite;
}

.spinner circle {
  stroke-linecap: round;
  animation: spinner-pulse 1.5s ease-in-out infinite;
}

@keyframes spinner {
  100% {
    transform: rotate(360deg);
  }
}

@keyframes spinner-pulse {
  0% {
    stroke-dasharray: 0 150;
    stroke-dashoffset: 0;
  }
  47.5% {
    stroke-dasharray: 42 150;
    stroke-dashoffset: -16;
  }
  95%, 100% {
    stroke-dasharray: 42 150;
    stroke-dashoffset: -59;
  }
}
</style>
