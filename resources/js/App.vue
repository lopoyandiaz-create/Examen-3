<template>
  <div>
    <nav>
      <router-link to="/categorias">Categorías</router-link>
      <router-link to="/productos">Productos</router-link>
    </nav>
    <router-view />
  </div>
</template>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: #f5f5f5; }
nav {
  background: #2c3e50;
  padding: 15px 30px;
  display: flex;
  gap: 20px;
}
nav a {
  color: white;
  text-decoration: none;
  font-size: 16px;
  padding: 8px 16px;
  border-radius: 5px;
}
nav a:hover, nav a.router-link-active {
  background: #e74c3c;
}
</style>