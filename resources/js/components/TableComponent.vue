<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="campo, key in campos" :key="key">{{campo.titulo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj,key in dadosFiltrados" :key="key">
                <td v-for="campo, chave in campos" :key="chave">
                   <span v-if="campo.tipo === 'imagem'">
                        <img :src="'/storage/'+obj[chave]" width="30" height="30">
                   </span>
                   <span v-else-if="campo.tipo === 'timestamp'">
                        ...{{obj[chave]}}
                   </span>
                   <span v-else>
                        {{obj[chave]}}
                   </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'campos',
            'dados'
        ],
        computed: {
            dadosFiltrados() {
                let chavesCamposTabela = Object.keys(this.campos)
                let dadosFiltrados = []

                this.dados.map((dado, chave) => {
                    let dadoFiltrado = {}

                    chavesCamposTabela.forEach(campo => {
                        dadoFiltrado[campo] = dado[campo]
                    })
                    dadosFiltrados.push(dadoFiltrado)
                })
                console.log(dadosFiltrados)
            
                return dadosFiltrados
            }
        },
        methods: {
            formatTimestamp(timestamp) {
                const date = new Date(timestamp);
                const formattedDate = date.toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                return formattedDate;
            }
        }
    }
</script>
