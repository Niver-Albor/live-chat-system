let formulario = document.getElementById('form-mensaje'),
    botonEnviar = document.getElementById('boton-enviar'),
    bandeja = document.getElementById('bandeja'),
    recibir = document.getElementById('reciever_usuario_ID'),
    sender = document.getElementById('sender_usuario_ID'),
    mensaje1 = document.getElementById('mensaje'),
    datosOyenteImagen = document.getElementById('datos_oyente_imagen'),
    datosOyenteInfo = document.getElementById('datos_oyente_info');

cargarMensajes();
actualizarInfo();

function enviarMensaje(e)
{
    e.preventDefault();
    
    var enviar = document.getElementById('sender_usuario_ID');
    
    let sender = enviar.value, reciever = recibir.value, mensaje = mensaje1.value;

    let paramentros = '?sender_usuario_ID=' + sender + '&reciever_usuario_ID=' + reciever + '&mensaje=' + mensaje;

    let peticion = new XMLHttpRequest();
    peticion.open('GET','php/guardar_mensaje.php' + paramentros);

    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    peticion.onload = function()
    {
        let datos = JSON.parse(peticion.responseText);
        if(datos.error)
        {
            console.log(datos.mensaje);
            window.location.href = "usuarios.php";
        }
    }

    // alert(paramentros);
    peticion.send();
}

// Actualizar info usuario.
function actualizarInfo()
{
    let  oyente_ID = recibir.value;
    let peticion = new XMLHttpRequest();
    peticion.open('GET','php/actualizar_info.php?usuario_ID=' + oyente_ID);

    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    peticion.onload = function()
    {
        let datos = JSON.parse(peticion.responseText);
        
        if(datos.error)
        {
            console.log(datos.mensaje);
            window.location.href = "usuarios.php";
        }
        else
        {
            while (datosOyenteImagen.firstChild)
            {
                datosOyenteImagen.removeChild(datosOyenteImagen.firstChild);
            }
            while (datosOyenteInfo.firstChild)
            {
                datosOyenteInfo.removeChild(datosOyenteInfo.firstChild);
            }

            // Creo el elemento que voy a agregar despues.
            let imagen = document.createElement('div');
            // El pone su clase para los estilos CSS.
            imagen.classList.add('imagen');
            // Le mete la imagen.
            imagen.innerHTML =("<img src='php/cargar_foto.php?usuario_ID=" + oyente_ID + "' alt=''>" );
            // Le metemos la imagen.
            datosOyenteImagen.appendChild(imagen);

            // Creo el div de la info.
            let info = document.createElement('div');
            // Le pongo su calse para el CSS.
            info.classList.add('info-usuario');
            // le metemos la nueva info.
            info.innerHTML =(
                '<p class="nombre-usuario"><b>' + datos.nombre_usuario + '</b></p>'
            );
            if(datos.enlinea == 0)
                info.innerHTML+=('<p>Desconectado</p>');
            else if(datos.enlinea == 1)
            info.innerHTML+=('<p>Conectado</p>');
            // Le metemos el otro elemento, el de la info.
            datosOyenteInfo.appendChild(info);

            // // Le mete el boton de volver.
            // let botonVolver = document.createElement('div');
            // // Le pone sus clases para el CSS.
            // botonVolver.classList.add('volver');
            // botonVolver.innerHTML = ('<button id="boton-volver">Volver</button>');
            // // Se lo mete.
            // datosOyente.appendChild(botonVolver);
        }
    }

    peticion.send();
}

function cargarMensajes()
{
    var enviar = document.getElementById('sender_usuario_ID');
    let usuario = enviar.value, usuario2 = recibir.value;
    var scrollPos = bandeja.scrollTop;
    var oldHeight = bandeja.scrollHeight;

    let peticion = new XMLHttpRequest();
    peticion.open('GET','php/cargar_mensajes.php?usuario_ID=' + usuario + '&usuario2_ID=' + usuario2);

    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    peticion.onload = function()
    {
        let datos = JSON.parse(peticion.responseText);
        
        if(datos.error)
        {
            console.log(datos.mensaje);
            window.location.href = "usuarios.php";
        }
        else
        {
            while (bandeja.firstChild)
            {
                bandeja.removeChild(bandeja.firstChild);
            }
            
            datos.forEach(mensaje => 
                {
                // Agrega el boton para eliminar mensajes.
                let botonEliminar = document.createElement('button');
                botonEliminar.value = mensaje.mensaje_ID;
                botonEliminar.innerHTML += ('Eliminar');
                botonEliminar.classList.add('boton-eliminar');

                // Aqui se crea el div.
                let elemento = document.createElement('div');

                elemento.classList.add('mensaje');
                if(mensaje.tipo == 2)
                    elemento.classList.add('recibido');
                else if(mensaje.tipo == 1)
                {
                    elemento.classList.add('enviado');
                    // Mete el boton en el div.
                    elemento.appendChild(botonEliminar);
                }
                elemento.innerHTML += ('<p>' + mensaje.mensajeDB + '</p>');
                // console.dir(mensaje)
                bandeja.appendChild(elemento);
                });

            // Mariqueras del scroll.
            var heightDifference = bandeja.scrollHeight - oldHeight;
            bandeja.scrollTop = scrollPos + heightDifference;
        }
    }

    peticion.send();
}

// Borra el mensaje de la base de datos.
function borrarMensaje(mensajeID)
{
    // alert(mensajeID);
    var info = {
        mensajeID: mensajeID,
        senderID: sender.value
    };
    let peticion = new XMLHttpRequest();
    peticion.open('POST','php/eliminar_mensaje.php');

    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let params = Object.keys(info).map(key => key + '=' + encodeURIComponent(info[key])).join('&');

    peticion.onload = function() {
        if (peticion.status >= 200 && peticion.status < 400) {
            // La petición fue exitosa
            console.log(peticion.responseText);
        } else {
            // Hubo un error en la petición
            console.log("Error: " + peticion.status);
        }
    };

    peticion.send(params);
}

const actualizar = setInterval(() => {
    // console.log('Beep!')
    cargarMensajes();
    // ActualizarBotones();
    actualizarInfo();
}, 2000);

formulario.addEventListener('submit', function(e){
    enviarMensaje(e);
    document.getElementById('mensaje').value = "";
});

var nodo = document.body;

// Crea una nueva instancia de MutationObserver
var observador = new MutationObserver(function() {
    var botonesEliminar = Array.from(document.getElementsByClassName('boton-eliminar'));
    botonesEliminar.forEach(function(boton) {
        boton.addEventListener('click', function(){
            borrarMensaje(boton.value);
        });
    });
});

// Configura el observador para que observe los cambios en los hijos y los atributos del nodo
observador.observe(nodo, { attributes: true, childList: true, subtree: true });