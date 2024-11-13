<div class="card">
                    <h6>Total proveedores</h6>
                    <h3><?php echo htmlspecialchars($totalProveedores); ?></h3>
                    <div class="small warning">Sin cambio</div>
                </div>



                
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>

            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Proveedor</th>
                            <th>Categoría</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido["id_pedido"]); ?></td>
                                <td><?php echo htmlspecialchars($pedido["proveedor"]); ?></td>
                                <td><?php echo htmlspecialchars($pedido["categoria"]); ?></td>
                                <td>$<?php echo number_format(htmlspecialchars($pedido["costo"]), 2); ?></td>
                                <td>
                                    <!-- Botón Editar -->
                                    <button class="btn btn-warning btn-sm" onclick="editPedido(<?php echo htmlspecialchars($pedido["id_pedido"]); ?>)">Editar</button>
                                    <!-- Botón Eliminar -->
                                    <a href="?delete_pedido=<?php echo htmlspecialchars($pedido["id_pedido"]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este pedido?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botón Agregar Pedido -->
            <button class="btn btn-primary" onclick="toggleAddModal()">Agregar Pedido</button>

            <!-- Modal Agregar Pedido -->
            <div class="modal" id="addPedidoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Pedido</h5>
                            <button type="button" class="btn-close-text" onclick="toggleAddModal()">Cerrar</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="id_proveedor" class="form-label">Proveedor</label>
                                    <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                                        <option value="">Selecciona un proveedor</option>
                                        <?php foreach ($proveedores as $proveedor): ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['id']); ?>">
                                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <select class="form-control" id="categoria" name="categoria" required>
                                        <option value="">Selecciona una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria); ?>">
                                                <?php echo htmlspecialchars($categoria); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="costo" class="form-label">Costo</label>
                                    <input type="number" class="form-control" id="costo" name="costo" required>
                                </div>
                                <button type="submit" name="add_pedido" class="btn btn-primary">Agregar Pedido</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar Pedido -->
            <div class="modal" id="editPedidoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Pedido</h5>
                            <button type="button" class="btn-close-text" onclick="toggleEditModal()">Cerrar</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" id="edit_id_pedido" name="id_pedido">
                                <div class="mb-3">
                                    <label for="edit_id_proveedor" class="form-label">Proveedor</label>
                                    <select class="form-control" id="edit_id_proveedor" name="id_proveedor" required>
                                        <option value="">Selecciona un proveedor</option>
                                        <?php foreach ($proveedores as $proveedor): ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['id']); ?>">
                                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_categoria" class="form-label">Categoría</label>
                                    <select class="form-control" id="edit_categoria" name="categoria" required>
                                        <option value="">Selecciona una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria); ?>">
                                                <?php echo htmlspecialchars($categoria); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_costo" class="form-label">Costo</label>
                                    <input type="number" class="form-control" id="edit_costo" name="costo" required>
                                </div>
                                <button type="submit" name="update_pedido" class="btn btn-primary">Actualizar Pedido</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="chart-container">
                    <canvas id="myChart"></canvas>
                    </div>
                    <div class="chart-container">
                    
                    </div>