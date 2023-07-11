<div class="">

    <div class="mb-6">

        <h1 class="text-3xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-thin mb-6  bg-white">Consultas</h1>

        <div class="flex justify-between">

            <div class="flex">

                <input type="number" placeholder="Número de control" min="1" class="bg-white rounded-l text-sm w-full focus:ring-0" wire:model.defer="search">

                <button
                    wire:click="consultar"
                    wire:loading.attr="disabled"
                    wire:target="consultar"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-none ">

                    <img wire:loading wire:target="consultar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

        </div>

    </div>

    @if($certificacion)

        <div class="relative overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <table class="rounded-lg w-full">

                <thead class="border-b border-gray-300 bg-gray-50">

                    <tr class="text-xs font-medium text-gray-500 uppercase text-left traling-wider">

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Número de Control

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Estado

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Tipo de servicio

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Solicitante

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Tomo / Bis

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Registro / Bis

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Distrito

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Sección

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Asignado a

                        </th>

                        <th class="px-3 py-3 hidden lg:table-cell">

                            Fecha de entrega

                        </th>

                        @if(auth()->user()->hasRole('Administrador'))

                            <th class="px-3 py-3 hidden lg:table-cell">

                                Acciones

                            </th>

                        @endif

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200 flex-1 sm:flex-none ">


                    <tr class="text-sm font-medium text-gray-500 bg-white flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            {{ $certificacion->tramite }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center  lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $certificacion->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($certificacion->estado) }}</span>

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $certificacion->tipo_servicio }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $certificacion->solicitante }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto capitalize p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo / Bis</span>

                            {{ $certificacion->tomo  ?? 'N/A' }} {{ $certificacion->tomo_bis ? '/ Bis' : ''}}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto capitalize p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro / Bis</span>

                            {{ $certificacion->registro  ?? 'N/A' }} {{ $certificacion->registro_bis ? '/ Bis' : ''}}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                            {{ $certificacion->distrito }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                            {{ $certificacion->seccion }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Asignado a</span>

                            {{ $certificacion->asignadoA->name }}

                        </td>

                        <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                            {{ $certificacion->fecha_entrega }}

                        </td>

                        @if(auth()->user()->hasRole('Administrador'))

                            <td class="px-3 py-3 w-full lg:w-auto p-3 text-gray-800 text-center lg:text-left lg:border-0 border border-b block lg:table-cell relative lg:static">

                                <button
                                    wire:click="$set('modal', '!modal')"
                                    wire:loading.attr="disabled"
                                    class="md:w-full bg-blue-400 hover:shadow-lg text-white text-xs md:text-sm px-3 py-1 items-center rounded-full mr-2 hover:bg-blue-700 flex justify-center focus:outline-none"
                                >

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>

                                    <p>Corregir</p>

                                </button>

                                @if($certificacion->estado == 'nuevo')

                                    <button
                                        wire:click="$set('modalRechazar', '!modalRechazar')"
                                        wire:loading.attr="disabled"
                                        class="md:w-full mt-1 bg-red-400 hover:shadow-lg text-white text-xs md:text-sm px-3 py-1 items-center rounded-full mr-2 hover:bg-red-700 flex justify-center focus:outline-none"
                                    >

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        <p>Recahzar</p>

                                    </button>

                                @endif

                            </td>

                        @endif

                    </tr>

                </tbody>

                <tfoot class="border-gray-300 bg-gray-50">

                </tfoot>

            </table>

            <div class="h-full w-full rounded-lg bg-gray-200 bg-opacity-75 absolute top-0 left-0" wire:loading.delay.longer>

                <img class="mx-auto h-16" src="{{ asset('storage/img/loading.svg') }}" alt="">

            </div>

        </div>

    @else

        <div class="border-b border-gray-300 bg-white text-gray-500 text-center p-5 rounded-full text-lg">

            No hay resultados.

        </div>

    @endif

    <x-dialog-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">
            Corregir certificación
        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto mr-1 ">

                    <div>

                        <Label>Número de paginas</Label>

                    </div>

                    <div>

                        <input type="number" class="bg-white rounded text-sm w-full" wire:model.defer="paginas">

                    </div>

                    <div>

                        @error('paginas') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modal')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
            >
                Corregir
            </x-danger-button>

        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal wire:model="modalRechazar" maxWidth="sm">

        <x-slot name="title">

            Rechazar

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between md:space-x-3 mb-5">

                <div class="flex-auto ">

                    <div>

                        <Label>Observaciones</Label>
                    </div>

                    <div>

                        <textarea rows="5" class="bg-white rounded text-sm w-full" wire:model.defer="observaciones"></textarea>

                    </div>

                    <div>

                        @error('observaciones') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                    </div>

                </div>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="float-righ">

                <button
                    wire:click="rechazar"
                    wire:loading.attr="disabled"
                    wire:target="rechazar"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded-full text-sm mb-2 hover:bg-blue-700 flaot-left mr-1 focus:outline-none">

                    <img wire:loading wire:target="rechazar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    Rechazar
                </button>

                <button
                    wire:click="$set('modalRechazar',false)"
                    wire:loading.attr="disabled"
                    wire:target="$set('modalRechazar',false)"
                    type="button"
                    class="bg-red-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded-full text-sm mb-2 hover:bg-red-700 flaot-left focus:outline-none">
                    Cerrar
                </button>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
