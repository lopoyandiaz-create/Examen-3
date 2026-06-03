<template>
  <div class="container">
    <h1>Productos</h1>
    
    <div class="card">
      <h2>{{ editando ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
      <select v-model="form.categoria_id">
        <option value="">-- Selecciona categoría --</option>
        <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
          {{ cat.nombre }}
        </option>
      </select>
      <input v-model="form.nombre" placeholder="Nombre del producto" />
      <input v-model="form.talla" placeholder="Talla (S, M, L, XL...)" />
      <input v-model="form.precio" placeholder="Precio" type="number" step="0.01" />
      <div class="campo-imagen">
        <label>Imagen del producto:</label>
        <input type="file" @change="seleccionarImagen" accept="image/*" />
        <img v-if="preview" :src="preview" class="preview" />
      </div>
      <div class="botones">
        <button class="btn-guardar" @click="guardar">
          {{ editando ? 'Actualizar' : 'Guardar' }}
        </button>
        <button class="btn-cancelar" v-if="editando" @click="cancelar">
          Cancelar
        </button>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Talla</th>
          <th>Precio</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="prod in productos" :key="prod.id">
          <td>{{ prod.id }}</td>
          <td>
            <img v-if="prod.imagen"
              :src="`/storage/${prod.imagen}`"
              class="img-tabla" />
            <span v-else>Sin imagen</span>
          </td>
          <td>{{ prod.nombre }}</td>
          <td>{{ prod.categoria?.nombre }}</td>
          <td>{{ prod.talla }}</td>
          <td>S/. {{ prod.precio }}</td>
          <td>
            <button class="btn-editar" @click="editar(prod)">Editar</button>
            <button class="btn-eliminar" @click="eliminar(prod.id)">Eliminar</button>
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
      productos: [],
      categorias: [],
      form: {
        categoria_id: '',
        nombre: '',
        talla: '',
        precio: '',
      },
      imagen: null,
      preview: null,
      editando: null,
    }
  },
  mounted() {
    this.cargarProductos()
    this.cargarCategorias()
  },
  methods: {
    async cargarProductos() {
      const res = await fetch('/api/productos')
      this.productos = await res.json()
    },
    async cargarCategorias() {
      const res = await fetch('/api/categorias')
      this.categorias = await res.json()
    },
    seleccionarImagen(e) {
      this.imagen = e.target.files[0]
      this.preview = URL.createObjectURL(this.imagen)
    },
    async guardar() {
      if (!this.form.categoria_id || !this.form.nombre) {
        return alert('Categoría y nombre son obligatorios')
      }
      const data = new FormData()
      data.append('categoria_id', this.form.categoria_id)
      data.append('nombre', this.form.nombre)
      data.append('talla', this.form.talla)
      data.append('precio', this.form.precio)
      if (this.imagen) data.append('imagen', this.imagen)

      if (this.editando) {
        data.append('_method', 'PUT')
        await fetch(`/api/productos/${this.editando}`, {
          method: 'POST',
          body: data,
        })
      } else {
        await fetch('/api/productos', {
          method: 'POST',
          body: data,
        })
      }
      this.cancelar()
      this.cargarProductos()
    },
    editar(prod) {
      this.editando = prod.id
      this.form = {
        categoria_id: prod.categoria_id,
        nombre: prod.nombre,
        talla: prod.talla,
        precio: prod.precio,
      }
      this.preview = prod.imagen ? `/storage/${prod.imagen}` : null
    },
    async eliminar(id) {
      if (!confirm('¿Eliminar este producto?')) return
      await fetch(`/api/productos/${id}`, { method: 'DELETE' })
      this.cargarProductos()
    },
    cancelar() {
      this.editando = null
      this.form = { categoria_id: '', nombre: '', talla: '', precio: '' }
      this.imagen = null
      this.preview = null
    },
  },
}
</script>

<style scoped>
.container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
h1 { color: #2c3e50; margin-bottom: 20px; }
.card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
h2 { margin-bottom: 15px; color: #555; }
input, select { display: block; width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
.campo-imagen label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; }
.preview { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; margin-top: 10px; border: 2px solid #ddd; }
.botones { display: flex; gap: 10px; margin-top: 10px; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
th { background: #2c3e50; color: white; padding: 12px; text-align: left; }
td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
tr:hover { background: #f9f9f9; }
.img-tabla { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
.btn-guardar { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.btn-cancelar { background: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
.btn-editar { background: #3498db; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 5px; }
.btn-eliminar { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
</style>