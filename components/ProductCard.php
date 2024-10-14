

<div class="group relative">
        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
          <img src="https://tailwindui.com/plus/img/ecommerce-images/product-page-01-related-product-01.jpg" alt="Front of men&#039;s Basic Tee in black." class="h-full w-full object-cover object-center lg:h-full lg:w-full">
        </div>
        <div class="mt-4 flex justify-between">
          <div>
            <h3 class="text-sm text-gray-700">
                <a href="quickView/<?php echo htmlspecialchars($product->id_producto); ?>">                <span aria-hidden="true" class="absolute inset-0"></span>
                <p class="text-l font-bold text-gray-800"><?php echo htmlspecialchars($product->nombre); ?></p>
              </a>
            </h3>
            <p class="mt-1 text-sm text-gray-500">Black</p>
          </div>
          <p class="text-sm font-medium text-gray-900">$<?php echo htmlspecialchars(number_format($product->precio, 2)); ?></p>
        </div>
        <?php if(AuthHelper::isAdmin() || AuthHelper::getLoggedInUserId() == $product->id_vendedor): ?>
            <a href="deleteProduct/<?php echo $product->id_producto; ?>" class="btn btn-danger">Eliminar</a>
        <?php endif; ?>
</div>