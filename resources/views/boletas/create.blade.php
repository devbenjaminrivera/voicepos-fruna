<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('VoicePOS Fruna - Nuevo Pedido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Micrófono Activo</h3>
                    <p class="text-sm text-gray-500 mb-4">Presiona "Iniciar Escucha" y dicta el pedido. Di la palabra <strong>"APARTE"</strong> para finalizar y procesar.</p>
                    
                    <button id="btn-start" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                        🎤 Iniciar Escucha
                    </button>
                    <button id="btn-stop" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 hidden">
                        ⏹ Detener
                    </button>
                </div>

                <div class="mb-4">
                    <label for="transcript" class="block text-sm font-medium text-gray-700">Transcripción en tiempo real</label>
                    <textarea id="transcript" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly placeholder="Esperando dictado..."></textarea>
                </div>

                <input type="hidden" id="empresa_id" value="1">

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnStart = document.getElementById('btn-start');
            const btnStop = document.getElementById('btn-stop');
            const transcriptArea = document.getElementById('transcript');
            const empresaId = document.getElementById('empresa_id').value;

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            
            if (!SpeechRecognition) {
                Swal.fire('Error', 'Tu navegador no soporta la Web Speech API. Usa Chrome o Edge.', 'error');
                return;
            }

            const recognition = new SpeechRecognition();
            recognition.continuous = true; 
            recognition.lang = 'es-CL';
            recognition.interimResults = true;

            let finalTranscript = '';

            recognition.onstart = function() {
                btnStart.classList.add('hidden');
                btnStop.classList.remove('hidden');
                transcriptArea.value = '';
                finalTranscript = '';
                transcriptArea.classList.add('border-blue-500', 'ring-blue-500');
            };

            recognition.onresult = function(event) {
                let interimTranscript = '';
                
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    } else {
                        interimTranscript += event.results[i][0].transcript;
                    }
                }

                const textoActual = finalTranscript + interimTranscript;
                transcriptArea.value = textoActual;

                
                if (textoActual.toLowerCase().includes('aparte')) {
                    recognition.stop();
                }
            };

            recognition.onend = function() {
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
                transcriptArea.classList.remove('border-blue-500', 'ring-blue-500');

                const textoFinal = transcriptArea.value.toLowerCase();
                
                if (textoFinal.includes('aparte')) {
                    enviarPedidoBackend(textoFinal);
                }
            };

            recognition.onerror = function(event) {
                console.error("Error de reconocimiento de voz:", event.error);
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
            };

            btnStart.addEventListener('click', () => {
                recognition.start();
            });

            btnStop.addEventListener('click', () => {
                recognition.stop();
            });

            function enviarPedidoBackend(texto) {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Generando boleta en el servidor',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("boletas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        texto_dictado: texto,
                        empresa_id: empresaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Boleta Generada!',
                            text: 'El pedido se guardó correctamente.',
                            showConfirmButton: true
                        });
                        transcriptArea.value = '';
                    } else {
                        Swal.fire('Error', 'Hubo un problema al generar la boleta.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Falla de conexión con el servidor.', 'error');
                });
            }
        });
    </script>
</x-app-layout>