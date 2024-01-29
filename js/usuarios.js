let botonesUsuarios = Array.from(document.getElementsByClassName('usuario-boton')),
    form = document.getElementById('form-chat'),
    input = document.getElementById('oyente'),
    botonCerrar = document.getElementById('boton-cerrar'),
    botonEliminarCuenta = document.getElementById('boton-eliminar-cuenta');

cargarUsuarios();
// Boton de cerrar sesion.
botonCerrar.addEventListener('click',function(){
    // alert("Tu ere' un lambe bicho");
    window.location.href = "php/logout.php";
});

botonEliminarCuenta.addEventListener('click', function() {
    var respuesta = confirm("¿Estás seguro que desea eliminar la cuenta de forma permanente?");

        if (respuesta == true)
        {
            window.location.href = 'php/eliminar_cuenta.php';
        }
});

function cargarUsuarios () 
{
    var catalogoUsuarios = document.getElementById('catalogo_usuarios');

    let peticion = new XMLHttpRequest();
    peticion.open('GET','php/lista_usuarios.php');
    // Sin esta paja lanza error.
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     peticion.onload = function()
     {
        let datos = JSON.parse(peticion.responseText);

        if(datos.error)
        {
            console.log(datos.mensaje);
        }
        else
        {
            while (catalogoUsuarios.firstChild)
            {
                catalogoUsuarios.removeChild(catalogoUsuarios.firstChild);
            }

            datos.forEach(usuario =>
                {
                    let elemento = document.createElement('button');
                    elemento.classList.add('usuario-boton');
                    elemento.value = usuario.usuarioID;

                    elemento.innerHTML += ("<div class='imagen'><img src='php/cargar_foto.php?usuario_ID=" + usuario.usuarioID + " alt='Me ganaste, Leroy.'></div>");

                    elemento.innerHTML += ("<div class='contenido'><p>" + usuario.nombreUsuario + "</p>");

                    if(usuario.estado == '1')
                        elemento.innerHTML += ("<p class='on'>Conectado</p>");
                    else
                        elemento.innerHTML += ("<p>Desconectado</p>");
                    if(usuario.pendiente)
                        elemento.innerHTML += ("<p>Mensaje sin leer</p></div>");
                    
                    catalogoUsuarios.appendChild(elemento);
                });
        }
     };

     peticion.send();
}

// Actualiza la pagina cada dos segundos.
const actualizar = setInterval(() => {
    cargarUsuarios();
}, 2000);

var nodo = document.body;

// Crea una nueva instancia de MutationObserver
var observador = new MutationObserver(function() {
    var botonesUsuarios = Array.from(document.getElementsByClassName('usuario-boton'));
    botonesUsuarios.forEach(function(botonUsuario) {
        botonUsuario.addEventListener('click', function(){
            input.value = botonUsuario.value;
            form.submit();
        });
    });
});

// Configura el observador para que observe los cambios en los hijos y los atributos del nodo
observador.observe(nodo, { attributes: true, childList: true, subtree: true });