<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div id="app">

        <x-container class="py-8">

            {{-- CREATE ACCESS PERSONAL TOKEN --}}
            <x-form-section class="mb-12">

                @slot('title')
                    Access Token
                @endslot

                @slot('description')
                    Aquí podrás generar un access token
                @endslot

                <div class="grid grid-cols-6 gap-6">

                    <div class="col-span-6 sm:col-span-4">

                        {{-- ERRORS --}}

                        <div v-if="form.errors.length > 0"
                            class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Whoops!</strong>
                            <span>¡Algo salió mal!</span>
                            <hr>
                            <ul class="list-disc ml-5">
                                <li v-for="error in form.errors">@{{ error }}</li>
                            </ul>
                        </div>

                        {{-- INPUT NAME --}}

                        <div>
                            <x-label>Nombre</x-label>
                            <x-input v-model="form.name" type="text" class="w-full mt-1" />
                        </div>

                        <div v-if="scopes.length > 0">
                            <x-label>Scopes</x-label>

                            <div v-for="scope in scopes">
                                <x-label>
                                    <input type="checkbox" name="scopes" :value="scope.id" v-model="form.scopes"></input>
                                    @{{ scope.description }}
                                </x-label>
                            </div>
                        </div>

                    </div>

                </div>

                @slot('actions')
                    <x-button v-on:click="store()" v-bind:disabled="form.disabled">Crear</x-button>
                @endslot

            </x-form-section>

            {{-- SHOW ACCESS PERSONAL TOKENS --}}
            <x-form-section v-if="tokens.length > 0">
                @slot('title')
                    Listado de tokens
                @endslot

                @slot('description')
                    Aquí podrás encontrar todos los tokens personales que has agregado
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
                            <tr v-for="token in tokens">
                                <td class="py-2">
                                    @{{ token . name }}
                                </td>

                                <td class="flex divide-x divide-gray-300 py-2">
                                    <a class="pr-2 hover:text-green-600 font-semibold cursor-pointer"
                                        v-on:click="show(token)">
                                        Ver
                                    </a>

                                    <a class="pl-2 hover:text-red-600 font-semibold cursor-pointer"
                                        v-on:click="revoke(token)">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </x-form-section>
        </x-container>

        {{-- MODAL SHOW TOKEN --}}

        <x-dialog-modal modal="showToken.open">
            @slot('title')
                Mostrar access token
            @endslot
            @slot('content')
                <div class="space-y-3 overflow-auto">

                    <p>
                        <span class="font-semibold">Access Token: </span>
                        <br>
                        <span>@{{ showToken . id }}</span>
                    </p>

                </div>
            @endslot

            @slot('footer')
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-gray-50 hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    v-on:click="showToken.open = false">
                    Cancelar
                </button>
            @endslot
        </x-dialog-modal>

    </div>

    @push('js')

        <script>
            new Vue({
                el: '#app',
                data: {
                    tokens: [],
                    scopes: [],
                    form: {
                        name: '',
                        errors: [],
                        disabled: false,
                        scopes: [],
                    },
                    showToken: {
                        open: false,
                        id: ''
                    }
                },
                mounted() {
                    this.getTokens();
                    this.getScopes();
                },
                methods: {
                    getScopes() {
                        axios.get('/oauth/scopes')
                            .then(response => {
                                this.scopes = response.data;
                            });
                    },
                    show(token) {
                        this.showToken.open = true;
                        this.showToken.id = token.id;
                    },
                    getTokens() {
                        axios.get('/oauth/personal-access-tokens')
                            .then(response => {
                                this.tokens = response.data;
                            });
                    },
                    store() {
                        this.form.disabled = true;
                        console.log(this.form);

                        axios.post('/oauth/personal-access-tokens', this.form)
                            .then(response => {
                                this.form.name = '';
                                this.form.errors = [];
                                this.form.scopes = [];
                                this.form.disabled = false;
                                this.getTokens();
                            })
                            .catch(error => {
                                this.form.errors = Object.values(error.response.data.errors).flat();
                                this.form.disabled = false;
                            });
                    },
                    revoke(token) {
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

                                axios.delete('/oauth/personal-access-tokens/' + token.id)
                                    .then(response => {
                                        this.getTokens();
                                        Swal.fire(
                                            'Deleted!',
                                            'Your file has been deleted.',
                                            'success'
                                        )
                                    });


                            }
                        });
                    }
                }
            });
        </script>

    @endpush

</x-app-layout>
