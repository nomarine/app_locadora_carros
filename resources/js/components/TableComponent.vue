<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="campo, key in campos" :key="key">{{campo.titulo}}</th>
                <th v-if="actions" class="col-5">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj in dados" :key="obj.id">
                <td v-for="valor, chave in campos" :key="chave">
                    <span v-if="valor['tipo'] === 'imagem'">
                        <img :src="'/storage/'+obj[chave]" height="20%" width="20%">
                    </span>
                    <span v-else-if="valor['tipo'] === 'timestamp'">
                        {{ $filters.formatTimestampGlobal(obj[chave]) }}
                    </span>
                    <span v-else>
                        {{obj[chave]}}
                    </span>
                </td>
                <td v-if="actions">
                    <button v-if="actions.visualizar.visivel" class="btn btn-outline-primary btn-sm mr-1" :data-toggle="actions.visualizar.dataToggle" :data-target="actions.visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                    <button v-if="actions.editar" class="btn btn-outline-primary btn-sm mr-1" :data-toggle="actions.editar.dataToggle" :data-target="actions.editar.dataTarget" @click="setStore(obj)">Editar</button>
                    <button v-if="actions.remover" class="btn btn-outline-danger btn-sm mr-1" :data-toggle="actions.remover.dataToggle" :data-target="actions.remover.dataTarget" @click="setStore(obj)">Remover</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'campos',
            'dados',
            'actions'
        ],
        data() {
            return {
     
            }
        },
        mounted() {
            
        },
        computed: {

        },
        methods: {
            setStore(item){
                this.$store.state.item = item
                this.$store.state.transacao = {status: '', detalhes: {mensagem: '', erros: ''}}
            }
        }
    }
</script>
