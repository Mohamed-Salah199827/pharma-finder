<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Pharma Finder</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><RouterLink class="nav-link" to="/">Home</RouterLink></li>
          <li class="nav-item">
            <RouterLink class="nav-link" to="/products">Products</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" to="/variants">Variants</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" to="/pharmacies">Pharmacies</RouterLink>
          </li>
          <li class="nav-item"><RouterLink class="nav-link" to="/search">Search</RouterLink></li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container py-4">
    <RouterView />
  </main>
</template>
