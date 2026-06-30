<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Terminal VoicePOS') }}
            </h2>
            <a href="{{ route('boletas.index') }}" class="text-gray-500 hover:text-fruna-red font-medium flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Boletas
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Columna Izquierda: Micrófono -->
                <div class="lg:col-span-7 bg-white/70 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                    <div class="p-8 border-b border-gray-50 flex-none bg-white/50">
                        <h3 class="text-xl font-bold text-gray-800">Reconocimiento de Voz</h3>
                        <p class="text-sm text-gray-500 mt-1">Presiona iniciar, dicta el pedido y finaliza con la palabra <strong class="text-fruna-red bg-red-50 px-2 py-0.5 rounded">"APARTE"</strong>.</p>
                    </div>

                    <div class="flex-1 flex flex-col items-center justify-center p-8 relative">
                        <!-- Animación del Micrófono -->
                        <div class="relative w-48 h-48 flex items-center justify-center mb-8">
                            <!-- Ondas de pulso -->
                            <div id="mic-pulse-1" class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-0 transition-opacity duration-300"></div>
                            <div id="mic-pulse-2" class="absolute inset-4 bg-red-200 rounded-full animate-pulse opacity-0 transition-opacity duration-300"></div>
                            
                            <!-- Botón central de Inicio -->
                            <button id="btn-start" class="relative z-10 bg-gradient-to-br from-fruna-red to-fruna-darkred hover:shadow-[0_0_20px_rgba(225,6,0,0.4)] hover:scale-105 text-white rounded-full w-24 h-24 flex items-center justify-center transition-all duration-300 shadow-lg border-4 border-white">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                            </button>
                            
                            <!-- Botón de Detener (Oculto inicialmente) -->
                            <button id="btn-stop" class="relative z-10 bg-gray-800 hover:bg-gray-900 text-white rounded-full w-24 h-24 flex items-center justify-center transition-all duration-300 shadow-lg border-4 border-gray-700 hidden">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Entrada de Texto / Transcripción (Diseño Limpio) -->
                        <div class="w-full bg-white rounded-2xl p-2 shadow-sm border border-gray-200 min-h-[160px] relative flex flex-col mt-2">
                            <div class="flex justify-between items-center px-4 py-2 border-b border-gray-50">
                                <span class="text-sm font-bold text-gray-700" id="status-text">Dicta o escribe el pedido</span>
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded hidden sm:inline">Usa la palabra "aparte" o el botón para finalizar</span>
                            </div>
                            <textarea id="transcript" class="w-full flex-1 p-4 bg-transparent border-none focus:ring-0 resize-none text-gray-800 text-lg leading-relaxed placeholder-gray-300" placeholder="Ej: Quiero una coca cola y unas papas fritas..."></textarea>
                            <div class="absolute bottom-3 right-3">
                                <button id="btn-manual-submit" class="bg-fruna-yellow text-gray-900 hover:bg-yellow-400 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all hover:shadow-md flex items-center gap-2 border border-yellow-400">
                                    Procesar Pedido
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Ticket Virtual -->
                <div class="lg:col-span-5 bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col h-[600px] relative">
                    <!-- Decoración superior ticket -->
                    <div class="bg-gray-900 text-white p-6 text-center border-b-4 border-fruna-yellow relative z-10">
                        <h3 class="text-2xl font-bold uppercase tracking-widest">TICKET VIRTUAL</h3>
                        <p class="text-xs text-gray-400 mt-1 uppercase">Sucursal Fruna Principal</p>
                    </div>
                    
                    <div class="flex-1 p-6 bg-[url('https://www.transparenttextures.com/patterns/cream-paper.png')] bg-yellow-50/50 overflow-y-auto font-mono text-sm relative" id="ticket-items">
                        <div class="text-center text-gray-400 mt-20 italic opacity-60" id="empty-ticket-msg">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            El ticket está vacío.<br>Simulando productos en tiempo real...
                        </div>
                        <table class="w-full hidden" id="ticket-table">
                            <thead>
                                <tr class="border-b-2 border-dashed border-gray-300 text-gray-500 text-xs">
                                    <th class="text-left pb-2">CANT</th>
                                    <th class="text-left pb-2">PRODUCTO</th>
                                    <th class="text-right pb-2">VALOR</th>
                                </tr>
                            </thead>
                            <tbody id="ticket-body" class="text-gray-800">
                                <!-- Filas agregadas por JS -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="bg-white p-6 border-t-2 border-dashed border-gray-300 z-10">
                        <div class="flex justify-between items-center text-2xl font-bold text-gray-900">
                            <span>TOTAL</span>
                            <span id="ticket-total" class="text-fruna-red">$0</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <input type="hidden" id="empresa_id" value="{{ $empresa->id ?? 1 }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Cambiamos DOMContentLoaded por turbolinks:load para compatibilidad con la SPA
        document.addEventListener('turbolinks:load', function () {
            // Evitar reinicialización múltiple si no estamos en la página correcta
            const btnStart = document.getElementById('btn-start');
            if (!btnStart) return;

            const btnStop = document.getElementById('btn-stop');
            const transcriptArea = document.getElementById('transcript');
            const statusText = document.getElementById('status-text');
            const pulse1 = document.getElementById('mic-pulse-1');
            const pulse2 = document.getElementById('mic-pulse-2');
            const empresaId = document.getElementById('empresa_id').value;

            // Variables para ticket virtual
            const ticketTable = document.getElementById('ticket-table');
            const ticketBody = document.getElementById('ticket-body');
            const emptyMsg = document.getElementById('empty-ticket-msg');
            const ticketTotalEl = document.getElementById('ticket-total');
            
            // CATÁLOGO OFFLINE: Se inyecta desde la base de datos al renderizar. Latencia = 0ms.
            const CATALOGO = @json($productos);

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            
            if (!SpeechRecognition) {
                Swal.fire({
                    icon: 'error',
                    title: 'Navegador no soportado',
                    text: 'Tu navegador no soporta la Web Speech API. Usa Chrome o Edge.',
                    confirmButtonColor: '#E10600'
                });
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
                
                pulse1.classList.remove('opacity-0');
                pulse2.classList.remove('opacity-0');
                statusText.innerText = 'Escuchando activamente... (Voz)';
                statusText.classList.add('text-fruna-red');
                
                transcriptArea.value = '';
                finalTranscript = '';
                
                ticketBody.innerHTML = '';
                ticketTotalEl.innerText = '$0';
                ticketTable.classList.add('hidden');
                emptyMsg.classList.remove('hidden');
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

                // Llamada Instantánea SIN FETCH NI DEBOUNCE (0ms)
                simularTicketVirtualOffline(textoActual.toLowerCase());
                
                if (textoActual.toLowerCase().includes('aparte')) {
                    recognition.stop();
                }
            };

            // Implementación de Similitud Levenshtein en JS
            function similitud(s1, s2) {
                if(s1.length === 0) return s2.length === 0 ? 100 : 0;
                if(s2.length === 0) return 0;
                let matrix = [];
                for(let i = 0; i <= s2.length; i++){ matrix[i] = [i]; }
                for(let j = 0; j <= s1.length; j++){ matrix[0][j] = j; }
                for(let i = 1; i <= s2.length; i++){
                    for(let j = 1; j <= s1.length; j++){
                        if(s2.charAt(i-1) == s1.charAt(j-1)){
                            matrix[i][j] = matrix[i-1][j-1];
                        } else {
                            matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, Math.min(matrix[i][j-1] + 1, matrix[i-1][j] + 1));
                        }
                    }
                }
                let maxLen = Math.max(s1.length, s2.length);
                return (1 - matrix[s2.length][s1.length] / maxLen) * 100;
            }

            function simularTicketVirtualOffline(texto) {
                if (texto.trim() === '') {
                    renderTicketUI({ productos: [], total: 0 });
                    return;
                }

                const mapaNumeros = {
                    'un': '1', 'una': '1', 'uno': '1', 'dos': '2', 'tres': '3', 
                    'cuatro': '4', 'cinco': '5', 'seis': '6', 'siete': '7', 
                    'ocho': '8', 'nueve': '9', 'diez': '10'
                };

                let textoProcesado = texto.toLowerCase();
                // Limpiar puntuación (puntos, comas, etc) que arruinan la Regex
                textoProcesado = textoProcesado.replace(/[^a-zñáéíóú0-9\s]/ig, ' ');

                for (const [palabra, num] of Object.entries(mapaNumeros)) {
                    let regex = new RegExp('\\b' + palabra + '\\b', 'g');
                    textoProcesado = textoProcesado.replace(regex, num);
                }

                let regex = /(\d+)\s*([a-zñáéíóú\s]+?)(?=\s*\d|$)/ig;
                let match;
                let itemsDictados = [];
                while ((match = regex.exec(textoProcesado)) !== null) {
                    itemsDictados.push({
                        cantidad: parseInt(match[1]),
                        palabra: match[2].trim()
                    });
                }

                let detectados = [];
                let total = 0;

                itemsDictados.forEach(item => {
                    if (!item.palabra) return;
                    
                    let mejorCoincidencia = null;
                    let mayorSimilitud = 0;

                    CATALOGO.forEach(producto => {
                        let palabraLimpia = item.palabra.toLowerCase().replace(/[^a-záéíóúñ]/g, '');
                        let nombreBDLimpia = producto.nombre.toLowerCase().replace(/[^a-záéíóúñ]/g, '');
                        
                        let sim = 0;
                        if (palabraLimpia.includes(nombreBDLimpia)) {
                            sim = 100; // Match perfecto si está contenido (ej. "papafritayaparte" contiene "papafrita")
                        } else {
                            sim = similitud(palabraLimpia, nombreBDLimpia);
                        }
                        
                        if (sim > mayorSimilitud) {
                            mayorSimilitud = sim;
                            mejorCoincidencia = producto;
                        }
                    });

                    // Umbral de 65% de similitud para tolerar errores pero evitar falsos positivos (ej: doritos vs gomitas)
                    if (mayorSimilitud >= 65 && mejorCoincidencia) {
                        let subtotal = item.cantidad * mejorCoincidencia.precio_unitario;
                        detectados.push({
                            cantidad: item.cantidad,
                            nombre: mejorCoincidencia.nombre,
                            subtotal: subtotal
                        });
                        total += subtotal;
                    }
                });

                renderTicketUI({ productos: detectados, total: total });
            }

            function renderTicketUI(data) {
                ticketBody.innerHTML = '';
                let productos = data.productos;
                
                if (productos.length > 0) {
                    emptyMsg.classList.add('hidden');
                    ticketTable.classList.remove('hidden');
                    ticketTotalEl.innerText = '$' + data.total.toLocaleString('es-CL');
                    
                    productos.forEach(prod => {
                        const tr = document.createElement('tr');
                        tr.className = "border-b border-dashed border-gray-200 animate-[pulse_0.1s_ease-in-out]";
                        tr.innerHTML = `
                            <td class="py-3 font-semibold">${prod.cantidad}</td>
                            <td class="py-3 capitalize">${prod.nombre}</td>
                            <td class="py-3 text-right">$${prod.subtotal}</td>
                        `;
                        ticketBody.appendChild(tr);
                    });
                } else {
                    emptyMsg.classList.remove('hidden');
                    ticketTable.classList.add('hidden');
                    ticketTotalEl.innerText = '$0';
                }
            }

            recognition.onend = function() {
                resetUI();
                const textoFinal = transcriptArea.value.toLowerCase();
                
                if (textoFinal.includes('aparte')) {
                    enviarPedidoBackend(textoFinal);
                } else if (textoFinal.trim() !== '') {
                    statusText.innerText = 'Dictado detenido.';
                    statusText.classList.remove('text-fruna-red');
                }
            };

            recognition.onerror = function(event) {
                console.error("Error de reconocimiento de voz:", event.error);
                resetUI();
                statusText.innerText = 'Error de micrófono.';
                statusText.classList.remove('text-fruna-red');
                statusText.classList.add('text-red-500');
            };

            function resetUI() {
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
                pulse1.classList.add('opacity-0');
                pulse2.classList.add('opacity-0');
            }

            // Evitar duplicados de event listeners en Turbolinks agregando clases o flags
            if(!btnStart.dataset.listenerAttached) {
                btnStart.addEventListener('click', () => { recognition.start(); });
                btnStop.addEventListener('click', () => { recognition.stop(); });
                
                transcriptArea.addEventListener('keyup', function(e) {
                    const textoActual = this.value.toLowerCase();
                    simularTicketVirtualOffline(textoActual);

                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const textoLimpio = textoActual.replace(/\n/g, '');
                        enviarPedidoBackend(textoLimpio);
                    }
                });

                document.getElementById('btn-manual-submit').addEventListener('click', () => {
                    const textoActual = transcriptArea.value.toLowerCase();
                    enviarPedidoBackend(textoActual);
                });
                
                btnStart.dataset.listenerAttached = "true";
            }

            function enviarPedidoBackend(texto) {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Guardando boleta real...',
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
                            title: '¡Boleta Guardada!',
                            text: 'El pedido se ha procesado exitosamente.',
                            confirmButtonColor: '#E10600'
                        });
                        statusText.innerText = '¡Boleta guardada con éxito!';
                        statusText.classList.remove('text-fruna-red');
                        statusText.classList.add('text-green-600');
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