<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
        </h2>
    </x-slot>

    <div id="app">
        <x-container class="py-8">

            {{-- Crear Cliente --}}
            <x-form-section>
                @slot('title')
                    Crear un nuevo cliente
                @endslot

                @slot('description')
                    Ingrese los datos solicitados para poder crear un nuevo cliente
                @endslot

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">

                        <div v-if="createForm.errors.length > 0"
                            class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Whoops!</strong>
                            <span>¡Algo salió mal!</span>
                            <hr>
                            <ul class="list-disc ml-5">
                                <li v-for="error in createForm.errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <x-label>
                            Nombre
                        </x-label>
                        <x-input v-model="createForm.name" type="text" class="w-full mt-1" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-label>
                            URL de redirección
                        </x-label>
                        <x-input v-model="createForm.redirect" type="text" class="w-full mt-1" />
                    </div>
                </div>


                @slot('actions')
                    <x-button v-on:click="store">
                        Crear
                    </x-button>
                @endslot
            </x-form-section>

            {{-- Mostrar Clientes --}}
            <x-form-section v-if="clients.length > 0">
                @slot('title')
                    Listado de clientes
                @endslot

                @slot('description')
                    Aquí podrás encontrar todos los clientes que has agregado
                @endslot

                <div>
                    <table class="text-gray-600">

                        <thead class="border-b border-gray-300">
                            <tr>
                                <th class="py-2 w-full">Nombre</th>
                                <th class="py-2">Accción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-300">
                            <tr v-for="client in clients">
                                <td class="py-2">
                                    @{{ client . name }}
                                </td>

                                <td class="flex divide-x divide-gray-300 py-2">
                                    <a class="pr-2 hover:text-green-600 font-semibold cursor-pointer"
                                        v-on:click="show(client)">
                                        Ver
                                    </a>

                                    <a class="pr-2 pl-2 hover:text-blue-600 font-semibold cursor-pointer"
                                        v-on:click="edit(client)">
                                        Editar
                                    </a>

                                    <a class="pl-2 hover:text-red-600 font-semibold cursor-pointer"
                                        v-on:click="destroy(client)">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </x-form-section>


        </x-container>

        {{-- MODAL EDITAR --}}
        <x-dialog-modal modal="editForm.open">
            @slot('title')
                Editar cliente
            @endslot
            @slot('content')
                <div class="space-y-6">

                    <div v-if="editForm.errors.length > 0"
                        class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Whoops!</strong>
                        <span>¡Algo salió mal!</span>
                        <hr>
                        <ul class="list-disc ml-5">
                            <li v-for="error in editForm.errors">@{{ error }}</li>
                        </ul>
                    </div>

                    <div class="">
                        <x-label>
                            Nombre
                        </x-label>
                        <x-input v-model="editForm.name" type="text" class="w-full mt-1" />
                    </div>

                    <div class="">
                        <x-label>
                            URL de redirección
                        </x-label>
                        <x-input v-model="editForm.redirect" type="text" class="w-full mt-1" />
                    </div>
                </div>
            @endslot

            @slot('footer')
                <button type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-40"
                    v-on:click="update()" v-bind:disabled="editForm.disabled">
                    Actualizar
                </button>
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    v-on:click="editForm.open = false">
                    Cancelar
                </button>
            @endslot
        </x-dialog-modal>

        {{-- MODAL MOSTRAR --}}
        <x-dialog-modal modal="showClient.open">
            @slot('title')
                Mostrar credenciales
            @endslot
            @slot('content')
                <div class="space-y-3">

                    <p>
                        <span class="font-semibold">CLIENTE: </span>
                        <br>
                        <span>@{{ showClient . name }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">CLIENT_ID: </span>
                        <br>
                        <span>@{{ showClient . id }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">CLIENTE_SECRET: </span>
                        <br>
                        <span v-text="showClient.secret"></span>
                    </p>

                </div>
            @endslot

            @slot('footer')
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    v-on:click="showClient.open = false">
                    Cancelar
                </button>
            @endslot
        </x-dialog-modal>
    </div>



    @push('js')

        <script>
            new Vue({
                el: "#app",
                data: {
                    clients: [],
                    showClient: {
                        open: false,
                        name: null,
                        id: null,
                        secret: null,
                    },
                    createForm: {
                        errors: [],
                        disabled: false,
                        name: null,
                        redirect: null
                    },
                    editForm: {
                        open: false,
                        errors: [],
                        disabled: false,
                        name: null,
                        redirect: null,
                        id: null
                    }
                },
                mounted() {
                    this.getClients();
                },
                methods: {
                    show(client) {
                        console.log(client);
                        this.showClient.open = true;
                        this.showClient.name = client.name;
                        this.showClient.id = client.id;
                        this.showClient.secret = client.secret;
                    },
                    edit(client) {
                        this.editForm.open = true;
                        this.editForm.name = client.name;
                        this.editForm.redirect = client.redirect;
                        this.editForm.id = client.id;
                    },
                    update() {
                        this.editForm.disable = true;
                        console.log(this.editForm);
                        axios.put('/oauth/clients/' + this.editForm.id, this.editForm)
                            .then(response => {
                                this.editForm.disable = false;
                                this.editForm.open = false;
                                this.editForm.name = null;
                                this.editForm.redirect = null;
                                this.editForm.errors = [];
                                this.getClients();
                                Swal.fire(
                                    'Correcto!',
                                    'Se actualizó el cliente con éxito',
                                    'success'
                                );
                            })
                            .catch(error => {
                                errors = Object.values(error.response.data.errors).flat();
                                this.editForm.errors = errors;
                                this.editForm.disable = false;
                            });
                    },
                    getClients() {
                        axios.get('/oauth/clients', {
                                responseType: 'json'
                            })
                            .then(response => {
                                this.clients = response.data;
                            });
                    },
                    store() {
                        axios.post('/oauth/clients', this.createForm)
                            .then(response => {
                                this.createForm.name = null;
                                this.createForm.redirect = null;
                                this.createForm.errors = [];
                                this.getClients();
                                this.show(response.data);
                            })
                            .catch(error => {
                                errors = Object.values(error.response.data.errors).flat();
                                this.createForm.errors = errors;
                            });
                    },
                    destroy(client) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                axios.delete('/oauth/clients/' + client.id)
                                    .then(response => {
                                        this.getClients();
                                        Swal.fire(
                                            'Deleted!',
                                            'Your file has been deleted.',
                                            'success'
                                        )
                                    });


                            }
                        })
                    }
                }
            });
        </script>

    @endpush

</x-app-layout>
