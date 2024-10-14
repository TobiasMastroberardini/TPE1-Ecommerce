<section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">

<form action="addProduct" method="POST" enctype="multipart/form-data">
    <div class="mb-4">
        <label for="nombre" class="block text-gray-700">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" class="mt-2 block w-full px-4 py-2 border rounded" 
               value="<?php echo isset($product->nombre) ? htmlspecialchars($product->nombre) : ''; ?>" required>
    </div>

    <div class="mb-4">
        <label for="descripcion" class="block text-gray-700">Descripción:</label>
        <textarea id="descripcion" name="descripcion" class="mt-2 block w-full px-4 py-2 border rounded" required>
            <?php echo isset($product->descripcion) ? htmlspecialchars($product->descripcion) : ''; ?>
        </textarea>
    </div>

    <div class="mb-4">
        <label for="precio" class="block text-gray-700">Precio:</label>
        <input type="number" id="precio" name="precio" class="mt-2 block w-full px-4 py-2 border rounded" 
               required step="0.01" min="0" value="<?php echo isset($product->precio) ? htmlspecialchars($product->precio) : ''; ?>">
    </div>

    <div class="mb-4">
        <label for="imagen" class="block text-gray-700">Imagen:</label>
        <input type="file" id="imagen" name="imagen" class="mt-2 block w-full" required accept="image/*">
    </div>

    <div class="mb-4">
        <label for="stock" class="block text-gray-700">Stock:</label>
        <input type="number" id="stock" name="stock" class="mt-2 block w-full px-4 py-2 border rounded" 
               required min="0" value="<?php echo isset($product->stock) ? htmlspecialchars($product->stock) : ''; ?>">
    </div>

    <div class="mb-4">
        <label for="categoria" class="block text-gray-700">Categoría:</label>
        <select id="categoria" name="categoria" class="mt-2 block w-full px-4 py-2 border rounded" required>
            <option value="" disabled <?php echo !isset($product) ? 'selected' : ''; ?>>Selecciona una categoría</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category->id_categoria); ?>"
                        <?php echo isset($product->categoria_id) && $product->categoria_id == $category->id_categoria ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category->nombre); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>No hay categorías disponibles</option>
            <?php endif; ?>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Crear Producto</button>
</form>

</section>