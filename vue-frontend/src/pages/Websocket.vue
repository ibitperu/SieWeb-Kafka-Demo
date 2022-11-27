<template>
    <div class="card">
        <div class="card-header">
            Libreta de notas
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-end">
                    <a class="btn btn-dark btn-black" href="/">Ver ejemplo notificación push</a>
                    <button class="ms-2 btn btn-primary" @click="handlerDescargarLibreta">Descargar libreta</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Archivo</th>
                                <th>Descarga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">
                                    No tiene ninguna descarga procesada
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import axios from 'axios'
import Swal from "sweetalert2";

var toastMixin = Swal.mixin({
    toast: true,
    icon: 'success',
    title: 'General Title',
    position: 'top-right',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

const handlerDescargarLibreta = () => {
    const estado = localStorage.getItem('estado-documento');
    if (estado && (estado == 'pendiente' || estado == 'procesando')) {
        alert('Ya hay un solicitud procesandose');
        return;
    }

    localStorage.setItem('estado-documento', 'pendiente');
    const cuenta = JSON.parse(localStorage.getItem('sesion') || '{}');
    const cliente = `cliente${cuenta.ID}`;

    axios.post(`http://192.168.80.128:8082/topics/${cliente}-libretas`, {
        records: [
            {
                value: {
                    fecha: (new Date()).toISOString(),
                    alumno_id: cuenta.ID
                }
            }
        ]
    }, {
        headers:
        {
            'Content-Type': 'application/vnd.kafka.json.v2+json',
            'Accept': 'application/vnd.kafka.v2+json, application/vnd.kafka+json, application/json'
        }
    }).then((response) => {
        localStorage.setItem('estado-documento', 'procesando');
        alert('Unos minutos estaremos generado el documento. Le notificaremos en cuando esté disponible')
    }).catch((reason) => {
        localStorage.removeItem('estado-documento');
        alert(reason);
    });
}

function iniciarClienteWebSocket() {

    if ('WebSocket' in window) {
        console.log('WebSocket is supported by your Browser!');
        var ws = new WebSocket('ws://localhost:8081/', 'echo-protocol');

        ws.onopen = function () {
            console.log('Connection created');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            console.log(received_msg);
            toastMixin.fire({
                animation: false,
                title: 'Archivo disponible para descarga',
                html: `La descarga de su archivo ya está disponible`
            });
            localStorage.removeItem('estado-documento')
        };

        ws.onclose = function () {
            console.log('Connection closed');
        };
    } else {
        alert("WebSocket NOT supported by your Browser!");
    }
}
iniciarClienteWebSocket();

</script>