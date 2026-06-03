<template>
  <div class="container">
    <h1>Categorías</h1>

    <!-- Formulario -->
    <div class="card">
      <h2>{{ editando ? 'Editar Categoría' : 'Nueva Categoría' }}</h2>
      <input v-model="form.nombre" placeholder="Nombre" />
      <input v-model="form.descripcion" placeholder="Descripción" />
      <div class="botones">
        <button class="btn-guardar" @click="guardar">
          {{ editando ? 'Actualizar' : 'Guardar' }}
        </button>
        <button class="btn-cancelar" v-if="editando" @click="cancelar">
          Cancelar
        </button>
      </div>
    </div>

    <!-- Tabla -->
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cat in categorias" :key="cat.id">
          <td>{{ cat.id }}</td>
          <td>{{ cat.nombre }}</td>
          <td>{{ cat.descripcion }}</td>
          <td>
            <button class="btn-editar" @click="editar(cat)">Editar</button>
            <button class="btn-eliminar" @click="eliminar(cat.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      categorias: [],
      form: { nombre: '', descripcion: '' },
      editando: null,
    }
  },
  mounted() {
    this.cargar()
  },
  methods: {
    async cargar() {
      const res = await fetch('/api/categorias')
      this.categorias = await res.json()
    },
    async guardar() {
      if (!this.form.nombre) return alert('El nombre es obligatorio')
      if (this.editando) {
        await fetch(`/api/categorias/${this.editando}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        })
      } else {
        await fetch('/api/categorias', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        })
      }
      this.cancelar()
      this.cargar()
    },
    editar(cat) {
      this.editando = cat.id
      this.form = { nombre: cat.nombre, descripcion: cat.descripcion }
    },
    async eliminar(id) {
      if (!confirm('¿Eliminar esta categoría?')) return
      await fetch(`/api/categorias/${id}`, { method: 'DELETE' })
      this.cargar()
    },
    cancelar() {
      this.editando = null
      this.form = { nombre: '', descripcion: '' }
    },
  },
}
</script>

<style scoped>
.container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
h1 { color: #2c3e50; margin-bottom: 20px; }
.card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
h2 { margin-bottom: 15px; color: #555; }
input { display: block; width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
.botones { display: flex; gap: 10px; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
th { background: #2c3e50; color: white; padding: 12px; text-align: left; }
td { padding: 12px; border-bottom: 1px solid #eee; }
tr:hover { background: #f9f9f9; }
.btn-guardar { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.btn-cancelar { background: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.btn-editar { background: #3498db; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 5px; }
.btn-eliminar { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
</style>