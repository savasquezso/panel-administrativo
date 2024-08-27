let menu = document.querySelector('.menu')
let sidebar = document.querySelector('.sidebar')
let mainContent = document.querySelector('.main--content')
menu.onclick = function() {
    sidebar.classList.toggle('active')
    mainContent.classList.toggle('active')
}// Funciones para proveedores
function showAddProveedorForm() {
    document.getElementById('addProveedorForm').style.display = 'block';
}

function addProveedor() {
    var nombre = document.getElementById('nombreProveedor').value;
    var tipo = document.getElementById('tipoProveedor').value;
    $.post("add_proveedores.php", {nombre: nombre, tipo: tipo}, function(data) {
        alert(data);
        loadProveedores();
        document.getElementById('addProveedorForm').style.display = 'none';
    });
}

function loadProveedores() {
    $.getJSON("get_proveedores.php", function(data) {
        var container = document.getElementById('proveedoresContainer');
        container.innerHTML = ''; // Limpiar el contenedor
        data.forEach(function(proveedor) {
            var proveedorHtml = `
                <a href="#" class="doctor--card">
                    <div class="img--box--cover">
                        <div class="img--box">
                            <img src="img/${proveedor.tipo.toUpperCase()}.jpg" alt="">
                        </div>
                    </div>
                    <p class="free">${proveedor.nombre}</p>
                    <button onclick="updateProveedor(${proveedor.id})">Editar</button>
                    <button onclick="deleteProveedor(${proveedor.id})">Eliminar</button>
                </a>
            `;
            container.innerHTML += proveedorHtml;
        });
    });
}

function updateProveedor(id) {
    // Implementa la lógica para actualizar un proveedor
    // Puedes mostrar un formulario similar al de agregar, pero con los datos pre-llenados
    console.log("Actualizar proveedor con ID: " + id);
}

function deleteProveedor(id) {
    if(confirm('¿Estás seguro de que quieres eliminar este proveedor?')) {
        $.post("delete_proveedores.php", {id: id}, function(data) {
            alert(data);
            loadProveedores();
        });
    }
}

// Funciones para domicilios
function showAddDomicilioForm() {
    document.getElementById('addDomicilioForm').style.display = 'block';
}

function addDomicilio() {
    var nombre = document.getElementById('nombreDomicilio').value;
    var fecha = document.getElementById('fechaDomicilio').value;
    var genero = document.getElementById('generoDomicilio').value;
    var edad = document.getElementById('edadDomicilio').value;
    var estado = document.getElementById('estadoDomicilio').value;

    $.post("add_domicilio.php", {
        nombre: nombre,
        fecha: fecha,
        genero: genero,
        edad: edad,
        estado: estado
    }, function(data) {
        alert(data);
        loadDomicilios();
        document.getElementById('addDomicilioForm').style.display = 'none';
    });
}

function loadDomicilios() {
    $.getJSON("get_domicilios.php", function(data) {
        var tbody = document.getElementById('domiciliosTableBody');
        tbody.innerHTML = ''; // Limpiar la tabla
        data.forEach(function(domicilio) {
            var row = `
                <tr>
                    <td>${domicilio.nombre}</td>
                    <td>${domicilio.fecha}</td>
                    <td>${domicilio.genero}</td>
                    <td>${domicilio.edad}</td>
                    <td class="${domicilio.estado}">${domicilio.estado}</td>
                    <td><span><i class="ri-edit-line edit" onclick="updateDomicilio(${domicilio.id})"></i><i class="ri-delete-bin-line delete" onclick="deleteDomicilio(${domicilio.id})"></i></span></td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    });
}

function updateDomicilio(id) {
    // Implementa la lógica para actualizar un domicilio
    // Puedes mostrar un formulario similar al de agregar, pero con los datos pre-llenados
    console.log("Actualizar domicilio con ID: " + id);
}

function deleteDomicilio(id) {
    if(confirm('¿Estás seguro de que quieres eliminar este domicilio?')) {
        $.post("delete_domicilio.php", {id: id}, function(data) {
            alert(data);
            loadDomicilios();
        });
    }
}

// Código que se ejecuta cuando el documento está listo
$(document).ready(function() {
    loadProveedores();
    loadDomicilios();
});