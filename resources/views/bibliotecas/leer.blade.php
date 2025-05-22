@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('biblioteca.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold">{{ $comic->titulo }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Detalles del cómic -->
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/3 p-6">
                @if($comic->imagen)
                    <img src="{{ asset($comic->imagen) }}" alt="{{ $comic->titulo }}" class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500 text-lg">Sin imagen disponible</span>
                    </div>
                @endif
            </div>
            <div class="w-full md:w-2/3 p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $comic->titulo }}</h2>
                
                @if($comic->autor)
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Autor:</span> {{ $comic->autor->nombre }}</p>
                @endif
                
                <p class="text-gray-600 mb-2"><span class="font-semibold">Género:</span> {{ $comic->genero }}</p>
                <p class="text-gray-600 mb-2"><span class="font-semibold">Fecha de publicación:</span> {{ $comic->fecha_publicacion }}</p>
                
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Sinopsis</h3>
                    <p class="text-gray-700">{{ $comic->descripcion }}</p>
                </div>
                
                <!-- Progreso de lectura -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Tu progreso</h3>
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                        <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $entrada->progreso_lectura }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $entrada->progreso_lectura }}% completado</p>
                </div>
            </div>
        </div>
        
        <!-- Visor PDF con PDF.js -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold mb-4">Lector de cómics</h3>
            
            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex space-x-2">
                        <button id="prev" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button id="next" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div>
                        <span>Página: <span id="page_num"></span> / <span id="page_count"></span></span>
                    </div>

                    <div>
                        <button id="zoom_in" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-md mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button id="zoom_out" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-inner rounded-lg p-2 flex items-center justify-center">
                    <canvas id="pdf-canvas" class="max-w-full"></canvas>
                </div>
            </div>
            
            <div class="text-center">
                <button id="actualizar-progreso" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Guardar progreso de lectura
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Importar PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    // Configurar worker de PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

    document.addEventListener('DOMContentLoaded', function() {
        // Variables para el visor PDF
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        const scale = 1.5;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');

        // Ruta al archivo PDF del cómic
        const pdfUrl = '{{ asset("storage/".$comic->archivo_comic) }}';

        /**
         * Renderizar la página especificada del PDF
         */
        function renderPage(num) {
            pageRendering = true;
            
            // Obtener la página
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Renderizar PDF page en el canvas
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                const renderTask = page.render(renderContext);

                // Esperar a que la página termine de renderizarse
                renderTask.promise.then(function() {
                    pageRendering = false;
                    
                    // Actualizar números de página
                    document.getElementById('page_num').textContent = num;
                    
                    // Calcular progreso de lectura aproximado
                    const progreso = Math.round((num / pdfDoc.numPages) * 100);
                    
                    if (pageNumPending !== null) {
                        // Si hay una página pendiente, renderizarla
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
        }

        /**
         * Si otra página está en proceso de renderizado, poner en cola la nueva página;
         * de lo contrario, renderizar la página inmediatamente.
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        /**
         * Mostrar página anterior
         */
        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }
        document.getElementById('prev').addEventListener('click', onPrevPage);

        /**
         * Mostrar página siguiente
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }
        document.getElementById('next').addEventListener('click', onNextPage);

        /**
         * Zoom in
         */
        function zoomIn() {
            scale *= 1.2;
            queueRenderPage(pageNum);
        }
        document.getElementById('zoom_in').addEventListener('click', zoomIn);

        /**
         * Zoom out
         */
        function zoomOut() {
            scale /= 1.2;
            queueRenderPage(pageNum);
        }
        document.getElementById('zoom_out').addEventListener('click', zoomOut);

        // Cargar PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page_count').textContent = pdfDoc.numPages;
            
            // Iniciar con la primera página
            renderPage(pageNum);
        }).catch(function(error) {
            // Manejar error de carga
            console.error('Error al cargar el PDF:', error);
            const canvas = document.getElementById('pdf-canvas');
            const ctx = canvas.getContext('2d');
            canvas.height = 400;
            canvas.width = 600;
            
            ctx.fillStyle = '#f8f9fa';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.font = '24px Arial';
            ctx.fillStyle = 'red';
            ctx.textAlign = 'center';
            ctx.fillText('Error al cargar el PDF', canvas.width / 2, canvas.height / 2);
            
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.fillText('Por favor, asegúrate de que el archivo exista', canvas.width / 2, (canvas.height / 2) + 30);
        });

        // Botón para actualizar progreso
        const btnActualizar = document.getElementById('actualizar-progreso');
        
        btnActualizar.addEventListener('click', function() {
            // Calcular progreso basado en la página actual
            const progreso = Math.round((pageNum / pdfDoc.numPages) * 100);
            
            // Guardar progreso
            fetch('{{ route("biblioteca.actualizar", $comic->id_comic) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    progreso_lectura: progreso,
                    ultimo_marcador: pageNum
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Progreso actualizado!');
                }
            })
            .catch(error => {
                console.error('Error al actualizar el progreso:', error);
            });
        });
    });
</script>
@endsection 