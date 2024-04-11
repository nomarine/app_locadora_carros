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
                                    <input type="number" class="form-control" id="inputId" aria-describedby="ajudaId" v-model="atributos.id">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component 
                                    id="inputNome" 
                                    label="Nome da Marca" 
                                    ajuda-id="ajudaNome" 
                                    ajuda-texto="Opcional. Informe o nome da marca."
                                >
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="ajudaNome" v-model="atributos.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="buscarMarcas()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- final card pesquisa -->

                <!-- início card resultado -->
                <card-component titulo="Marcas encontradas">
                    <template v-slot:conteudo>                        
                        <table-component 
                            :dados="marcas.data" 
                            :campos="camposTabela"
                            :actions="{ 
                                visualizar: { visivel: true, dataToggle: 'modal', dataTarget: '#showMarca' }, 
                                editar: { visivel: true, dataToggle: 'modal', dataTarget: '#updateMarca' }, 
                                remover: { visivel: true, dataToggle: 'modal', dataTarget: '#destroyMarca' } 
                            }">
                        </table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <pagination-component>
                                    <li v-for="link, key in marcas.links" :key="key" :class="link.active ? 'page-item active' : 'page-item'" @click="paginar(link)">
                                        <a class="page-link" href="#" v-html="link.label"></a>
                                    </li>
                                </pagination-component>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#createMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- final card resultado -->
            </div>
        </div>

        <!-- Início Modais -->
        <modal-component id="createMarca" titulo="Adicionar marca">
            <template v-slot:alertas v-if="this.$store.state.transacao.status">
                <alert-component :tipo="alertTipo" :titulo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao.detalhes">
                </alert-component>
            </template>
            <template v-slot:conteudo>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="limparTransacao()">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="salvarMarca()">Salvar</button>
            </template>
        </modal-component>  
        
        <modal-component id="showMarca" titulo="Visualizar marca">
            <template v-slot:conteudo>
                <label for="marcaId">ID</label>
                <input type="text" class="form-control" id="marcaId" :value="$store.state.item.id" disabled>
                <label for="marcaNome">Nome</label>
                <input type="text" class="form-control" id="marcaNome" :value="$store.state.item.nome" disabled>
                <label for="marcaImagem">Imagem</label>
                <br><img v-if="$store.state.item.imagem" id="marcaImagem" :src="'storage/'+$store.state.item.imagem" width="40%" height="40%"><br>
                <label for="marcaDtCriacao">Data de Criação</label>
                <input type="text" class="form-control" id="marcaDtCriacao" :value="$store.state.item.created_at" disabled>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <modal-component id="updateMarca" titulo="Editar marca">
            <template v-slot:alertas v-if="this.$store.state.transacao.status">
                <alert-component :tipo="alertTipo" :titulo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao.detalhes">
                </alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component 
                        id="inputAlterarNome" 
                        label="Nome da Marca" 
                        ajuda-id="ajudaAlterarNome" 
                        ajuda-texto="Informe o nome da marca."
                    >
                        <input type="text" class="form-control" id="inputAlterarNome" aria-describedby="ajudaAlterarNome" v-model="$store.state.item.nome">
                    </input-container-component>
                    <br><img v-if="$store.state.item.imagem" id="marcaImagem" :src="'storage/'+$store.state.item.imagem" width="40%" height="40%"><br>
                    <input-container-component 
                        id="inputAlterarLogo" 
                        label="Logo da Marca" 
                        ajuda-id="ajudaAlterarLogo" 
                        ajuda-texto="Anexe o logo da marca."
                    >
                    <input type="file" class="form-control-file" id="inputAlterarLogo" aria-describedby="ajudaAlterarLogo" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="limparTransacao()">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="editarMarca()">Editar</button>
            </template>
        </modal-component> 

        <modal-component id="destroyMarca" titulo="Remover marca">
            <template v-slot:alertas v-if="this.$store.state.transacao.status">
                <alert-component :tipo="alertTipo" :titulo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao.detalhes">
                </alert-component>
            </template>
            <template v-slot:conteudo>
                <label for="marcaId">ID</label>
                <input type="text" class="form-control" id="marcaId" :value="$store.state.item.id" disabled>
                <label for="marcaNome">Nome</label>
                <input type="text" class="form-control" id="marcaNome" :value="$store.state.item.nome" disabled>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="limparTransacao()">Cancelar</button>
                <button type="button" class="btn btn-danger" @click="removerMarca()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim Modais -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://127.0.0.1:8000/api/v1/marca',
                paramPaginacao: '',
                paramFiltro: '',

                marcaNome: '',
                marcaLogo: [],
                marcas: [],
                camposTabela: {
                    id: {titulo: 'ID', tipo: 'texto'}, 
                    nome: {titulo: 'Nome', tipo: 'texto'}, 
                    imagem: {titulo: 'Logo', tipo: 'imagem'},
                    created_at: {titulo: 'Incluído em', tipo: 'timestamp'}
                },
                atributos: { id: '', nome: ''},

                transacaoStatus: '',
                transacaoDetalhes: {},
                alertTipo: ''
            }
        },
        mounted() {
            this.carregarMarcas()
        },
        computed: {
            config() {
                return { headers: {
                        'Content-Type':'multipart/form-data',
                    }
                }
            },
        },
        methods: {
            carregarImagem(e) {
                this.marcaLogo = e.target.files
            },
            salvarMarca(){
                let formData = new FormData()
                formData.append('nome', this.marcaNome)
                formData.append('imagem', this.marcaLogo[0])

                axios.post(this.urlBase, formData, this.config)
                    .then(response => {
                        this.alertTipo = 'success'
                        this.$store.state.transacao.status = "Sucesso!"
                        this.$store.state.transacao.detalhes = {
                            mensagem: `Marca ${response.data.nome} criada.`
                        }
                        console.log(response)
                        this.carregarMarcas()
                    })
                    .catch(errors => {
                        this.alertTipo = 'danger'
                        this.$store.state.transacao = {
                            status: "Erro ao tentar criar a marca.",
                            detalhes: { 
                                mensagem: errors.message,
                                erros: errors.response.data.errors
                            }
                        }
                    })
            },
            editarMarca(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)
                if(this.marcaLogo[0]){
                    formData.append('imagem', this.marcaLogo[0])
                }

                axios.post(url, formData, this.config)
                    .then(response => {
                        this.alertTipo = 'success'
                        this.$store.state.transacao.status = "Sucesso!"
                        this.$store.state.transacao.detalhes = {
                            mensagem: `Registro #${response.data.id} atualizado.`
                        }
                        inputAlterarLogo.value = ''
                        this.$store.state.item.imagem=response.data.imagem
                        console.log(response.data)
                        
                        this.carregarMarcas()
                    })
                    .catch(errors => {
                        this.alertTipo = 'danger'
                        this.$store.state.transacao = {
                            status: "Erro ao atualizar a marca.",
                            detalhes: { 
                                mensagem: errors.message,
                                erros: errors.response.data.errors
                            }
                        }
                        console.log(errors)
                    })
            },
            carregarMarcas() {
                let url = this.urlBase + '?' + this.paramPaginacao + this.paramFiltro
                axios.get(url)
                    .then(response => {
                        this.marcas = response.data
                    })
                    .catch(errors => 
                        console.log(errors)
                    )
            },
            removerMarca() {
                let confirmacao = confirm('Tem certeza que deseja excluir a marca?')
                if(!confirmacao){
                    return false
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData
                formData.append('_method', 'delete')
                axios.post(url, formData)
                    .then(response => { 
                        this.alertTipo = 'success'
                        this.$store.state.transacao.status = "Sucesso!"
                        this.$store.state.transacao.detalhes = {
                            mensagem: `Registro #${this.$store.state.item.id} excluído com sucesso.`
                        }
                        this.carregarMarcas()
                    })
                    .catch(errors => {
                        this.alertTipo = 'danger'
                        this.$store.state.transacao = {
                            status: "Erro ao tentar remover a marca.",
                            detalhes: { 
                                mensagem: errors.message,
                                erros: errors.response.data.errors
                            }
                        }
                        console.log(this.$store.state.transacao)
                        
                    })
            },
            paginar(link) {
                if(link.url){
                    this.paramPaginacao = link.url.split('?')[1]
                    this.carregarMarcas()
                }
            },
            buscarMarcas(){
                let filtro = ''

                for(let atributo in this.atributos){
                    if(this.atributos[atributo]){
                        if(filtro != ''){
                            filtro += ';';
                        }
                        filtro += atributo + ':like:%' + this.atributos[atributo] + '%'
                    }
                }
                if(filtro != ''){
                    this.paramPaginacao = 'page=1'
                    this.paramFiltro = '&filtros=' + filtro
                } else {
                    this.paramFiltro = ''
                }
                this.carregarMarcas()
            },
            limparTransacao(){
                this.marcaNome=''
                inputAlterarLogo.value=''
                inputNovaLogo.value=''
                this.$store.state.transacao = {status: '', detalhes: {mensagem: '', erros: ''}}
            }
        }
    }
</script>
