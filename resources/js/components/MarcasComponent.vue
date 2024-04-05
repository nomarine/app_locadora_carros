<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início card pesquisa -->
                <card-component style="margin-bottom: 16px" titulo="Pesquisa por marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputId" 
                                    label="ID da Marca" 
                                    ajuda-id="ajudaId" 
                                    ajuda-texto="Opcional. Informe o ID da marca. Apenas números."
                                >
                                    <input type="number" class="form-control" id="inputId" aria-describedby="ajudaId">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputNome" 
                                    label="Nome da Marca" 
                                    ajuda-id="ajudaNome" 
                                    ajuda-texto="Opcional. Informe o nome da marca."
                                >
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="ajudaNome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <!-- final card pesquisa -->

                <!-- início card resultado -->
                <card-component titulo="Marcas encontradas">
                    <template v-slot:conteudo>                        
                        <table-component :dados="marcas" :campos="camposTabela"></table-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#createMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- final card resultado -->
            </div>
        </div>
        <modal-component id="createMarca" titulo="Adicionar marca">
            
            <template v-slot:alertas v-if="transacaoStatus">
                <alert-component :tipo="alertTipo" :titulo="transacaoStatus" :detalhes="transacaoDetalhes">
                </alert-component>
            </template>

            <template v-slot:form>
                <div class="form-group">
                    <input-container-component 
                        id="inputNovoNome" 
                        label="Nome da Marca" 
                        ajuda-id="ajudaNovoNome" 
                        ajuda-texto="Informe o nome da marca."
                    >
                        <input type="text" class="form-control" id="inputNovoNome" aria-describedby="ajudaNovoNome" v-model="marcaNome">
                    </input-container-component>

                    <input-container-component 
                        id="inputNovaLogo" 
                        label="Logo da Marca" 
                        ajuda-id="ajudaNovaLogo" 
                        ajuda-texto="Anexe o logo da marca."
                    >
                        <input type="file" class="form-control-file" id="inputNovaLogo" aria-describedby="ajudaNovaLogo" @change="carregarImagem($event)">
                    </input-container-component>

                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="storeMarca()">Salvar</button>
            </template>
        </modal-component>        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://127.0.0.1:8000/api/v1/marca',

                marcaNome: '',
                marcaLogo: [],
                marcas: [],
                camposTabela: {
                    id: {titulo: 'ID', tipo: 'texto'}, 
                    nome: {titulo: 'Nome', tipo: 'texto'}, 
                    imagem: {titulo: 'Logo', tipo: 'imagem'},
                    created_at: {titulo: 'Incluído em', tipo: 'timestamp'}
                },

                transacaoStatus: '',
                transacaoDetalhes: {},
                alertTipo: ''
            }
        },
        mounted() {
            this.carregarMarcas()
        },
        computed: {
            token() {
                let cookie = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })
                cookie = cookie.split('=')

                return cookie[1]
            },
            config() {
                return { headers: {
                        'Content-Type':'multipart/form-data',
                        'Accept':'application/json',
                        'Authorization': `Bearer ${this.token}`
                    }
                }
            },
            marcasFormatted() {
                return this.marcas.map(marca => {
                    // Assuming created_at is in ISO format
                    marca.created_at_formatted = new Date(marca.created_at).toLocaleDateString();
                    return marca;
                });
            }
        },
        methods: {
            carregarImagem(e) {
                this.marcaLogo = e.target.files
            },
            storeMarca(){
                let formData = new FormData()
                formData.append('nome', this.marcaNome)
                formData.append('imagem', this.marcaLogo[0])

                axios.post(this.urlBase, formData, this.config)
                    .then(response => {
                        this.alertTipo = 'success'
                        this.transacaoStatus = "Sucesso! Marca criada."
                        this.transacaoDetalhes = {
                            mensagem: `ID do novo registro: ${response.data.id}`
                        }
                        console.log(response)
                    })
                    .catch(errors => {
                        this.alertTipo = 'danger'
                        this.transacaoStatus = "Erro ao tentar criar a marca."
                        this.transacaoDetalhes = {
                            mensagem: errors.message,
                            erros: errors.response.data.errors
                        }
                        console.log(errors)
                    })
            },
            carregarMarcas() {
                axios.get(this.urlBase, this.config)
                    .then(response => {
                        this.marcas = response.data
                        console.log(this.marcas)
                    })
                    .catch(errors => 
                        console.log(errors)
                    )
            },
        }
    }
</script>
