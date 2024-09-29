<div class="card" style="width: 18rem;">
    <img src="<?php echo htmlspecialchars($product->imagen); ?>" alt="<?php echo htmlspecialchars($product->nombre); ?>" class="card-img-top">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($product->nombre);?></h5>
        <p class="card-text"><?php echo htmlspecialchars($product->descripcion); ?></p>
        <p class="text-gray-700">Precio: $<?php echo htmlspecialchars(number_format($product->precio, 2)); ?></p>
        <a href="#" class="btn btn-primary">Agregar</a>
    </div>
</div>